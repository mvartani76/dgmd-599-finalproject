# An Open Source Wi-Fi Device Detection System
Repository for presentation, code, and documentation for my DGMD-599 Final Capstone Project, "An Open Source Wi-Fi Device Detection System"

For the Fall 2018 semester, I created a system that utilizes Raspberry Pis to emulate Wi-Fi Access Points and stream the scanned data to a cloud platform using AWS IoT Core and AWS DynamoDB.

## Requirements
To be able to fully test the system, you will need the following:
* Raspberry Pi 3
* Wi-Fi Device/Adapter that can accept Monitoring Mode - Please see this [link](https://null-byte.wonderhowto.com/how-to/buy-best-wireless-network-adapter-for-wi-fi-hacking-2018-0178550/) for a list of adapters.
* AWS Account with Credentials (will be provided if wanting to use with platform)
* Platform Account (will be provided)
## System Components
There are two primary components to the system:
* Wi-Fi Access Point(s)
* Cloud Platform

## Getting Started
In order to stream device data to the system and visualize the results, you will need to do the following:
* [Configure Wi-Fi Access Point](Python/)
* [Start Wi-Fi Scanning](Python/README.md#Run-Scanner-Code)
* [Configure Platform](CakePHP/)
