/*
 * Greenhouse
 *
 * Created: 7/25/2012 7:51:50 PM
 * Author: Adam Lundrigan <adam@lundrigan.ca>
 */

#include <stdio.h>
#include <string.h>

char* ltrim(char *string, char junk)
{
    char* original = string;
    char *p = original;
    int trimmed = 0;
    do
    {
        if (*original != junk || trimmed)
        {
            trimmed = 1;
            *p++ = *original;
        }
    }
    while (*original++ != '\0');
    return string;
}

char* rtrim(char* string, char junk)
{
    char* original = string + strlen(string);
    while(*--original == junk);
    *(original + 1) = '\0';
    return string;
}

char* trim_spaces(char *string)
{
	return ltrim(rtrim(string, ' '), ' ');
}

char* trim_null(char *string)
{
	return ltrim(rtrim(string, '\0'), '\0');
}

char* trim_ws(char *string)
{
	return trim_null(trim_spaces(rtrim(rtrim(string, '\n'), '\r')));
}