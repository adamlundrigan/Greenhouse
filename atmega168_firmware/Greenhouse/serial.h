#ifndef SERIAL_H
#define SERIAL_H

#include <stdint.h>

/*
 * Initialize the USART to the given baud rate.
 */
void serial_init( uint16_t baud );

/*
 * Transmit the sz bytes in data.
 * Return 0, if serial_tx is still busy, otherwize return 1.
 */
uint8_t serial_tx_put( uint8_t data[], uint16_t sz );

/*
 * Return the number of bytes transmited,
 * Not currently used.
 */
uint16_t serial_tx_sent( void );

/*
 * Return the number of bytes available.
 */
uint16_t serial_rx_available( void );

/*
 * Set the receiver buffer. sz is the buffer's size.
 */
void serial_rx_buffer( uint8_t data[], uint16_t sz );

/*
 * Copy amt bytes into data from the receiver's buffer.
 * The receivers buffer was set in serial_rx_buffer.
 */
uint16_t serial_rx_get( uint8_t data[], uint16_t amt );

void serial_easy_send(char* text);

#endif
