/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */ 

#ifndef ADC_H_
#define ADC_H_

void adc_init(void);
uint16_t adc_read(uint8_t ch);

#endif /* ADC_H_ */