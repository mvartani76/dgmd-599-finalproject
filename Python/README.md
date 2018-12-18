# Embedded Wifi Access Point Scanner Code
## Pre Install Requirements
In order to successfully run the WiFi Access Point Scanning Software, you will need the following:
- AWS Account (to use AWS IoT and DynamoDB)
- IoT Device Security Credentials (public/private keys/certificates). Can create from https://us-west-2.console.aws.amazon.com/iot/home?region=us-west-2#/connectdevice/ 

## Configure AWS IoT Setup
The WiFi Access Point Scanner Code communicates to the cloud using AWS IoT. For the Raspberry Pi to communicate with AWS IoT, we will need to include the AWS IoT Python SDK on the device. This will be taken care of using the included script file (start.sh). This section talks about what needs to happen within the AWS IoT Console to get the necessary credentials/keys configured.
## Configure a Device
Within the AWS IoT Platform, navigate to the Onboard section and select "Configuring a device"
![Register Device](../Documentation/Images/dgmd-599-aws-iot-config-device-dec2018.png)

## Register IoT Device

![Register Device](../Documentation/Images/dgmd-599-aws-iot-register-device-dec2018.png)


## Installing Necessary Software
Please run the provided startup script `start.sh` to make sure that you have all the necessary libraries to run the scanner code.
1. Add your AWS IoT custom endpoint, Certificate, and private key in the designated spots in `start.sh`
2. Make sure that `start.sh` is executable
```sudo chmod +x start.sh```

