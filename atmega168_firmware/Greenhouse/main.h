/*
 * main.h
 *
 * Created: 7/28/2012 10:35:00 AM
 *  Author: adam
 */ 


#ifndef MAIN_H_
#define MAIN_H_


void snooze(void);
void wakeup(void);
void transmit_reading_temp(char* name, uint8_t channel);
void transmit_reading_soil_humidity(char* name, uint8_t channel);


#endif /* MAIN_H_ */