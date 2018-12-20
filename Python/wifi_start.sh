#!/bin/bash

printf "Starting Script...\n"

# stop script on error
set -e

# Check to see if user downloaded the AWS start.sh file to the python directory
if [ ! -f ./start.sh ]; then
	printf "\nstart.sh not found. Please download from AWS....\n"
	exit 0
else
	printf "\nNeed to remove any extra newlines at EOF if they exist...\n"
	while [ -z "$(tail -c 1 start.sh)" ]
	do
		printf "Newline found at end of file...\n"
		head -c -1 start.sh > start.tmp
		mv start.tmp start.sh
	done
	printf "\nExtracting Credentials from AWS start.sh file...\n"
	# The credentials that we are looking for are located after the call to the python function in the AWS start.sh file
	# grep appears to be more stable than the previous while loop
	AWSINFO="$(grep -o ".py -e.*" start.sh)"
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

	# Find the WLAN interfaces available on this device
	WNAMES="$(iw dev | awk '$1=="Interface"{print $2}')"

	# Split the multi-line variable into an array for easier access
	IFS=$'\n' lines=($WNAMES)

	# Find the length of the array
	len=${#lines[@]}

	# loop through the array
	# Enable monitoring mode on the first interface that supports it
	# was noticing that some devices work on wlan0 and others on wlan1
	for (( i=0; i<$len; i++));
        do
                sudo ifconfig ${lines[$i]} down
	        if [ -z "$(sudo iwconfig ${lines[$i]} mode monitor 2>&1)" ]; then
        		WORKWLAN=${lines[$i]}
                fi
	        sudo ifconfig ${lines[$i]} up;
        done
	printf "Enabled for %s \n" $WORKWLAN
fi
# Delete the temporary fil
rm iwoutput.txt

# run WiFi ScannerAapp using provided certificates
# will populate the python command from downloaded AWS connection package start.sh
printf "\nRunning WiFi Scanner Application...\n"
PYTHONFILE="aws_iot_pubsub${AWSINFO}"
echo ${PYTHONFILE}
# Initiate the python command with the desired file and arguments
sudo python ${PYTHONFILE} -rssi "notedecodedpackets"
