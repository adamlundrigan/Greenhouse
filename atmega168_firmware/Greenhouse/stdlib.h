/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */ 

#ifndef STDLIB_H_
#define STDLIB_H_


char* trim_ws(char *string);
char* trim_spaces(char *string);
char* trim_null(char *string);
char* ltrim(char *string, char junk);
char* rtrim(char *string, char junk);


#endif /* STDLIB_H_ */