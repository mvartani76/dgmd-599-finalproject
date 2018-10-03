#!/usr/bin/python

import sys
import time
import datetime


from scapy.all import *

devices = set()

def build_packetHandler(time_format):
	def packetHandler(pkt):
        	if pkt.haslayer(Dot11):
                	dot11_layer = pkt.getlayer(Dot11)

			# get the time format
			log_time = str(int(time.time()))
			if time_format == 'iso':
				log_time = datetime.now().isoformat()

			# only add devices not already in the set
                	if dot11_layer.addr2 and (dot11_layer.addr2 not in devices):
                        	devices.add(dot11_layer.addr2)
				# print the number of unique devices followed by the MAC address
                        	print len(devices), log_time, dot11_layer.addr2, dot11_layer.payload.name
	return packetHandler


built_packetHandler = build_packetHandler(sys.argv[3])
sniff(iface = sys.argv[1], count = int(sys.argv[2]), prn = built_packetHandler)

