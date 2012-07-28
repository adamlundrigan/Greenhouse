/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */ 

#ifndef USART_H_
#define USART_H_

void usart_init( unsigned int ubrr );
void usart_write( char *data ); 

#endif /* USART_H_ */