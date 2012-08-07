/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */ 

#define F_CPU 8000000UL
#define BAUD_RATE(clockFreq, baud) ((uint16_t)(clockFreq/(16L*(baud))-1))

#include <avr/io.h>
#include <avr/interrupt.h>
#include <avr/sleep.h>
#include <avr/power.h>
#include <util/delay.h>
#include <stdlib.h>
#include <string.h>
#include <stdio.h>
#include "stdlib.h"
#include "adc.h"
#include "serial.h"
#include "main.h"

#define CH_TEMP0 0
#define CH_TEMP1 1

double AREF_MV = 3280.0;

/*  MAIN */
int main(void)
{
    uint8_t buf[50];
    uint8_t rxbuf[50];
    uint16_t amt;
		
	clock_prescale_set( clock_div_1 );
	serial_init( BAUD_RATE(F_CPU,9600) );
	serial_rx_buffer(rxbuf, sizeof(rxbuf)/sizeof(uint8_t));
	adc_init();
	sei();
		
	serial_easy_send("Started!\r\n");
	
	while(1)
	{
		snooze();
		
		amt = serial_rx_available();
		if (amt > 0) {
			serial_rx_get(buf, amt);
			// Echo back command
			// serial_easy_send((char*)buf);
			
			// Copy command and strip all whitespace
			char *command = malloc(amt+1);
			memset(command, '\0', amt+1);
			memcpy(command, buf, amt);
			command = trim_ws(command);
			
			uint8_t validAction = 0;
			
			// Mad configurable variable skillz
			if (strncmp(command, "SETVAR AREF_MV", 14) == 0)
			{
				char outs[50];
				
				serial_easy_send("OK, SEND VALUE\r\n");
				snooze();
				
				char input[7];
				serial_rx_get(input, 7);
				AREF_MV = atof(input);
				
				snprintf(outs,sizeof(outs),"AREF_MV %4.1f\r\n", AREF_MV);
				serial_easy_send(outs);
				validAction=1;
			}			
			if (!validAction && strcmp(command, "GETVAR AREF_MV") == 0)
			{
				char outs[50];
				snprintf(outs,sizeof(outs),"AREF_MV %4.1f\r\n", AREF_MV);
				serial_easy_send(outs);
				validAction=1;
			}
			
			// Sensor-Related Actions are handled next
			if (!validAction)
			{
				// Parse out global flags
				uint8_t doAll = (strcmp(command, "READ ALL") == 0);
				uint8_t doTemp = (strcmp(command, "READ TEMP") == 0);
			
				// Process TEMP0 sensor
				if (doAll || doTemp || ( !validAction && strcmp(command, "READ TEMP0") == 0) )
				{
					transmit_reading_temp(CH_TEMP0);
					validAction = 1;
				}
				// Process TEMP1 sensor
				if (doAll || doTemp || ( !validAction && strcmp(command, "READ TEMP1") == 0) )
				{
					transmit_reading_temp(CH_TEMP1);
					validAction = 1;
				}
			}
			
			// Echo back unrecognized commands
			if (!validAction)
			{
				char outs[100];
				snprintf(outs, "INVALID COMMAND: %s\r\n\r\n", command);
				serial_easy_send(outs);
			}
										
			serial_easy_send("--END\r\n");
			
			free(command);
			memset(buf, '\0', sizeof(buf));

		}				
	}
	return 0;
}

void snooze(void)
{
	_delay_ms(100);
	
	set_sleep_mode(SLEEP_MODE_IDLE);
	sleep_enable();
	power_adc_disable();
	power_spi_disable();
	power_timer0_disable();
	power_timer1_disable();
	power_timer2_disable();
	power_twi_disable();
	sleep_mode();
	wakeup();
}

void wakeup(void)
{
	sleep_disable();
	power_all_enable();
	
	_delay_ms(100);
}

void transmit_reading_temp(uint8_t channel)
{
	char outs[25];
	uint16_t adc_temp;

	adc_temp = adc_read(channel);		
	snprintf(outs,sizeof(outs),"TEMP%d %3.2f\r\n", 
		channel,
		((((int)adc_temp) * (AREF_MV / 1024.0)) / 10.0) - 273.0
	);
	serial_easy_send(outs);
}