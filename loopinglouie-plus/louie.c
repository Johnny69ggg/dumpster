#define F_CPU       8000000UL

/*
portc 1..4  leds
portb 1,3   onoff/mode buttons connected to gnd
adc4        variostat, 10k, 5v/gnd
oc1a        motor
*/

#include <avr/io.h>
#include <avr/interrupt.h>
#include <avr/pgmspace.h>
#include <string.h>
#include <util/delay.h>
#include <stdlib.h>
#include <stdio.h>

//////////////////////////////////////////////////////////////////////

uint16_t poti,temp,time;

uint8_t mode=0;
uint8_t ison=0;

uint16_t pos=0;

//////////////////////////////////////////////////////////////////////

void adc_init(void) 
{
        // (ADC4 / PC4) select
        ADMUX = 64+4;
        // turn on a/d, prescale=64
        ADCSRA = (1<<ADEN)|(1<<ADPS2)|(1<<ADPS1)|(1<<ADPS0);
}

void servo_init(void) {
	// OC1A output
	DDRB = (1 << PB1 );
	TCCR1A = (1<<COM1A1) | (1<<WGM11);
	TCCR1B = (1<<WGM13) | (1<<WGM12) | (1<<CS10);
	
    //pwm frequency
  	ICR1 = 0x0180;
}

void read_poti() {
	// start conversion
    ADCSRA |= (1<<ADSC);
    // wait for conversion end
    while ( !(ADCSRA & (1<<ADIF)) )

	poti = 80+(ADC/4);
}



//////////////////////////////////////////////////////////////////////

void reset() {
	pos=0;
	time=20+(rand()%80);
}

// -400
int main(void) {
	DDRC = 15; //1=out
	PORTB |= 1+4; //pullup for keys

	servo_init();
	adc_init();
    
	while(1) {
		_delay_ms(50);
		read_poti();

		if (!ison) {
			PORTC &= 240;
			OCR1A=1;

			pos=(pos+ 1) % 80;
			if (pos<20) {
				PORTC |= 8;
			} else if (pos<40) {
				PORTC |= 4;
			} else if (pos<60) {
				PORTC |= 2;
			} else {
				PORTC |= 1;
			}
		} else {
			PORTC |= 15;
			switch (mode) {
				case 0:
					OCR1A=poti;
					break;
				case 1:
					time--;
					if (time<2) {
						temp=rand()%50;
						time=20+(rand()%80);
					}
					uint8_t s=(poti-50)+temp;
					OCR1A = s < 80 ? 80 : s;
					break;
				
				case 2:
					time--;
					if (time<2) {
						time=20+(rand()%80);
					} else if (time<15) {
						OCR1A = 1;		
					} else {
						OCR1A = poti;
					}
					break;
			}
		}
	
		if ((PINB & 4) == 0) {
			_delay_ms(50);
			while ((PINB & 4) == 0);
			ison=(ison+1)%2;
			reset();
			continue;
		}

		if ((PINB & 1)==0) {
			_delay_ms(50);
			while ((PINB & 1)==0);
			mode=(mode+1)%3;
			reset();
			continue;
		}
		
	}

}//main
