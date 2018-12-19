#!/bin/bash

printf "Starting Script...\n"

# stop script on error
set -e

if [ ! -f ./start.sh ]; then
	printf "\nstart.sh not found. Please download from AWS....\n"
else
	printf "\nNeed to remove newlines at EOF if they exist...\n"
	if [ -z "$(tail -n 1 start.sh)" ]
	then
		printf "Newline found at end of file...\n"
		head -c -1 start.sh > start.tmp
		mv start.tmp start.sh
	fi
	printf "\nExtracting Credentials from AWS start.sh file...\n"
	AWSINFO="$(while read x; do [[ $x =~ '.py -e'.* ]] && echo ${BASH_REMATCH[0]}; done < start.sh)"
fi
# Check to see if root CA file exists, download if not
if [ ! -f ./root-CA.crt ]; then
  printf "\nDownloading AWS IoT Root CA certificate from AWS...\n"
  # Symantec certificate is not working so using the one from AWS
  #curl https://www.symantec.com/content/en/us/enterprise/verisign/roots/VeriSign-Class%203-Public-Primary-Certification-Authority-G5.pem > root-CA.crt
  curl https://www.amazontrust.com/repository/AmazonRootCA1.pem > root-CA.crt
fi

# install AWS Device SDK for Python if not already installed
if [ ! -d ./aws-iot-device-sdk-python ]; then
  printf "\nInstalling AWS SDK...\n"
  sudo pip install AWSIoTPythonSDK --upgrade
fi

# install netaddr code for Python if not already installed
sudo pip install netaddr
# install Scapy code for inspecting wifi packets
sudo pip install scapy --upgrade

# Check to see if monitor mode is enabled
printf "\nChecking to see if monitor mode is enabled...\n"
iwconfig > iwoutput.txt
MONMODE="$(grep -n 'Monitor' iwoutput.txt | cut -d: -f 1)"

# MONMODE will equal a line number if monitoring mode is enabled
# the following checks to see if a line number exists -- if yes, monitoring mode is enabled
if [ -n "${MONMODE}" ]; then
	printf "Monitoring Mode Enabled...\n"
else
	printf "Monitoring Mode Disabled...Enabling...\n"
	# Assuming wlan0 for now
	sudo ifconfig wlan0 down
	sudo iwconfig wlan0 mode monitor
	sudo ifconfig wlan0 up
fi
# Delete the temporary fil
rm iwoutput.txt

# run WiFi ScannerAapp using provided certificates
# will populate the python command from downloaded AWS connection package start.sh
printf "\nRunning WiFi Scanner Application...\n"
PYTHONFILE="aws_iot_pubsub${AWSINFO}"
# Initiate the python comman with the desired file and arguments
sudo python ${PYTHONFILE} -rssi "notedecodedpackets"
