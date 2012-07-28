/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */ 

#include "avr/io.h"
#include <avr/interrupt.h>
#include <avr/sleep.h>
#include <stdint.h>
#include "serial.h"
#include "stdlib.h"
#include "main.h"

//#define USE_TX_INTERRUPTS

static struct {
    uint8_t *rx_buf;
    uint16_t rx_buf_sz;
    volatile uint16_t rx_buf_len;
    uint16_t head, tail;

    volatile uint8_t *tx_buf;
    volatile uint16_t tx_buf_sz;
    volatile uint16_t tx_buf_sent;
} serial;

void
serial_init( uint16_t baud )
{
    DDRD |= _BV(1) ; // enable tx output
    /* UBRR0H = HI(baud); UBRR0L = LO(baud) */
    UBRR0 = baud;
    /* Asynchronous mode, no parity, 1-stop bit, 8-bit data */
    UCSR0C = _BV(UCSZ01) | _BV(UCSZ00 );
    /* no 2x mode, no multi-processor mode */
    UCSR0A = 0x00;
    /* rx interrupts enabled, rx and tx enabled, 8-bit data */
    UCSR0B = _BV(RXCIE0) | _BV(RXEN0) | _BV(TXEN0);
}

#ifndef USE_TX_INTERRUPTS
uint8_t
serial_tx_put( uint8_t data[], uint16_t sz )
{
    if ( serial.tx_buf ) return 0;   // not required for polled io
    serial.tx_buf = data;            // not required for polled io
    serial.tx_buf_sz = sz;
    serial.tx_buf_sent = 0;

    while ( serial.tx_buf_sent < serial.tx_buf_sz ) {
        while ( (UCSR0A & _BV(UDRE0)) == 0 )
            ;
        UDR0 = serial.tx_buf[ serial.tx_buf_sent ];
        serial.tx_buf_sent++;
    }

    serial.tx_buf = 0;               // not required for polled io
    return 1;
}
#else
uint8_t
serial_tx_put( uint8_t data[], uint16_t sz )
{
    uint8_t sreg = SREG;
    cli();
    if ( serial.tx_buf ) {
        SREG = sreg;
        return 0;  
    }
    serial.tx_buf = data;          
    serial.tx_buf_sz = sz;
    serial.tx_buf_sent = 0;

    UCSR0B |= _BV( UDRIE0 ); // turn on data ready interrupts

    SREG = sreg;
    return 1;
}
#endif

uint16_t
serial_tx_sent( void )
{
    uint16_t sz;
    uint8_t sreg = SREG;
    cli();
    sz = serial.tx_buf_sent;
    SREG = sreg;
    return sz;
}

/*
 * Return the number of bytes available.
 */
uint16_t
serial_rx_available( void )
{
    uint16_t len;
    uint8_t sreg = SREG;
    cli();
    len = serial.rx_buf_len;
    SREG = sreg;
    return len;
}

void
serial_rx_buffer( uint8_t data[], uint16_t sz )
{
    serial.rx_buf = data;
    serial.rx_buf_sz = sz;
}

uint16_t
serial_rx_get( uint8_t data[], uint16_t amt )
{
    uint16_t amount = amt;
    uint16_t i = 0;
    uint8_t sreg = SREG;
    cli();
    while ( amt > 0 && serial.rx_buf_len > 0 ) {
        data[ i ] = serial.rx_buf[ serial.tail ];
        serial.tail++;
        if ( serial.tail >= serial.rx_buf_sz ) serial.tail = 0;
        amt--;
        serial.rx_buf_len--;
        i++;
    }
    SREG = sreg;
    return amount - amt;
}

/* -------------------------------------------------*/

ISR(USART_RX_vect)
{
    uint8_t b = UDR0;
    if ( serial.rx_buf_len >= serial.rx_buf_sz ) return; // error
    serial.rx_buf[serial.head] = b;
    serial.head++;
    if ( serial.head >= serial.rx_buf_sz ) serial.head = 0;
    serial.rx_buf_len++;

	if (b == 13) {
		wakeup();
	}
}

ISR(__vector_default){}

#ifdef USE_TX_INTERRUPTS
ISR(USART_UDRE_vect)
{
    if ( serial.tx_buf_sent >= serial.tx_buf_sz ) {
        UCSR0B &= ~_BV( UDRIE0 ); // turn off data ready interrupts
        serial.tx_buf = 0;        // transmit done
    }
    else {
        UDR0 = serial.tx_buf[ serial.tx_buf_sent ];
        serial.tx_buf_sent++;
    }
}
#endif


void serial_easy_send(char* text)
{
	char* trimmed = trim_spaces(trim_null(text));
	serial_tx_put((uint8_t*)trimmed, strlen(trimmed));
}