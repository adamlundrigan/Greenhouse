import serial
import time

# Set Analog Reference Voltage
ser = serial.Serial('/dev/rfcomm0', 9600, timeout=5)
ser.write("SETVAR AREF_MV")
time.sleep(0.1);
print ser.readline();
time.sleep(0.1);
ser.write("3294.0");
time.sleep(0.1);
print ser.readline();
ser.write("");
time.sleep(0.1);
ser.close();
time.sleep(1);

## Continuously monitor the sensors
while 1:
    ser = serial.Serial('/dev/rfcomm0', 9600, timeout=5)

    ## Trigger a read on all sensors
    ## @TODO replace w/ READ ALL in final product
    ser.write("READ TEMP0")
    time.sleep(0.1);

    ## Parse through the output
    while 1:
        line = ser.readline().strip();
        print line;
        if ( line == "--END" ):
            break

    ## Close 'er up and go sleepytime
    ser.close()
    time.sleep(5)
