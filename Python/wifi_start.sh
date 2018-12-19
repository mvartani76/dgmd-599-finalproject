printf "Starting Script...\n"
# stop script on error
set -e

# Grab the credentials from the downloaded AWS SDK Package start.sh file
#grep "python aws-iot" start.sh >> wifi_start.sh
#sed -i 's/aws-iot-device-sdk-python

#LINENUMBER="$(grep -n 'python test' wifi_start.sh | cut -d: -f 1)"
#echo "${LINENUMBER}"

if [ ! -f ./start.sh ]; then
	printf "\nstart.sh not found. Please download from AWS....\n"
else
	printf "\nExtracting Credentials from AWS start.sh file...\n"
	AWSINFO="$(while read x; do [[ $x =~ '.py -e'.* ]] && echo ${BASH_REMATCH[0]}; done <start.sh)"
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
#  popd
fi

# install netaddr code for Python if not already installed
sudo pip install netaddr
# install Scapy code for inspecting wifi packets
sudo pip install scapy --upgrade

# run WiFi ScannerAapp using provided certificates
# will populate the python command from downloaded AWS connection package start.sh
printf "\nRuning WiFi Scanner Application...\n"
PYTHONFILE="aws_iot_pubsub${AWSINFO}"
# Initiate the python comman with the desired file and arguments
sudo python ${PYTHONFILE} -rssi "notedecodedpackets"
