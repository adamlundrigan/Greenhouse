/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */ 

// Inspiration: http://tom-itx.dyndns.org:81/~webpage/how_to/atmega168/mega168_rs232_index.php

#include <avr/io.h>
#include <stdio.h>
#include "usart.h"

/* Initializes the USART (RS232 interface) */ 
void usart_init( unsigned int ubrr ) 
{ 
	UBRR0H = (unsigned char)(ubrr>>8);
	UBRR0L = (unsigned char)ubrr; 
    UCSR0B = (1 << RXCIE0) | (1 << RXEN0) | (1 << TXEN0);      // Enable receiver and transmitter
	UCSR0C = (3 << UCSZ00);    //asynchronous 8 N 1
} 

/* Send some data to the serial port */
void usart_write( char *data )
{
	while ((*data != '\0'))
	{
		while (!(UCSR0A & (1 <<UDRE0)));
		UDR0 = *data;
		data++;
	}   
}