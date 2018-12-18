# Embedded Wifi Access Point Scanner Code
## Pre Install Requirements
In order to successfully run the WiFi Access Point Scanning Software, you will need the following:
- AWS Account (to use AWS IoT and DynamoDB)
- IoT Device Security Credentials (public/private keys/certificates). Can create from https://us-west-2.console.aws.amazon.com/iot/home?region=us-west-2#/connectdevice/ 

## Register IoT Device

![Register Device](../Documentation/Images/dgmd-599-aws-iot-register-device-dec2018.png)


## Installing Necessary Software
Please run the provided startup script `start.sh` to make sure that you have all the necessary libraries to run the scanner code.
1. Add your AWS IoT custom endpoint, Certificate, and private key in the designated spots in `start.sh`
2. Make sure that `start.sh` is executable
```sudo chmod +x start.sh```

