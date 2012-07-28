/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */ 

// Inspiration: http://tom-itx.dyndns.org:81/~webpage/how_to/atmega168/mega168_adc_index.php

#include <avr/io.h>
#include <util/delay.h>
#include <stdio.h>
#include <stdint.h>

void adc_init(void)
{
	
   /** Setup and enable ADC **/
   ADMUX = (0<<REFS1)|    // Reference Selection Bits
           (1<<REFS0)|    // AVcc - external cap at AREF
           (0<<ADLAR)|    // ADC Left Adjust Result
           (0<<MUX2)|     // Analog Channel Selection Bits
           (0<<MUX1)|     // ADC0
           (0<<MUX0);
    
   ADCSRA = (1<<ADEN)|    // ADC ENable
           (0<<ADSC)|     // ADC Start Conversion
           (0<<ADATE)|    // ADC Auto Trigger Enable
           (0<<ADIF)|     // ADC Interrupt Flag
           (0<<ADIE)|     // ADC Interrupt Enable
           (0<<ADPS2)|    // ADC Prescaler Select Bits (ADC0)
           (0<<ADPS1)|
           (0<<ADPS0);
                   // Timer/Counter1 Interrupt Mask Register
    TIMSK1 |= (1<<TOIE1); // enable overflow interrupt
    
    
    TCCR1B |= (1<<CS11)|
           (1<<CS10);     // native clock
}

/* READ ADC PINS */
uint16_t adc_read(uint8_t ch)
{
	ADMUX = (0<<MUX2)|     // Reset ADC chanel selection
            (0<<MUX1)|     // ADC0
            (0<<MUX0);
	ch=ch&0b00000111;
	ADMUX |= ch;
	
	uint16_t reading;
	
	// First Reading
	ADCSRA|=(1<<ADSC);
	while(!(ADCSRA & (1<<ADIF)));
	ADCSRA|=(1<<ADIF);
	reading = ADC;
	
	// Take four more, averaging
	for(int i=0;i<4;i++) {
		ADCSRA|=(1<<ADSC);
		while(!(ADCSRA & (1<<ADIF)));
		ADCSRA|=(1<<ADIF);
		reading = (reading + ADC) / 2;
		_delay_ms(25);
	}	
	return reading;
}