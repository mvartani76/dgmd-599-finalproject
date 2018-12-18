# stop script on error
set -e

# Check to see if root CA file exists, download if not
if [ ! -f ./root-CA.crt ]; then
  printf "\nDownloading AWS IoT Root CA certificate from Symantec...\n"
  curl https://www.symantec.com/content/en/us/enterprise/verisign/roots/VeriSign-Class%203-Public-Primary-Certification-Authority-G5.pem > root-CA.crt
fi

# install AWS Device SDK for Python if not already installed
if [ ! -d ./aws-iot-device-sdk-python ]; then
  printf "\nInstalling AWS SDK...\n"
#  git clone https://github.com/aws/aws-iot-device-sdk-python.git
#  pushd aws-iot-device-sdk-python
#  python setup.py install --user
#  pushd
  sudo pip install AWSIoTPythonSDK --upgrade
#  popd
fi

# install netaddr code for Python if not already installed
sudo pip install netaddr
# install Scapy code for inspecting wifi packets
sudo pip install scapy

# run WiFi ScannerAapp using provided certificates
# will populate the python command from downloaded AWS connection package start.sh
printf "\nRuning WiFi Scanner Application...\n"

