#!/usr/bin/python

import sys
import time
import datetime
import netaddr

#import all scapy functions
from scapy.all import *
#import load_blacklist
from load_blacklist import load_blacklist

devices = set()

def build_packetHandler(time_format, blacklist):
	def packetHandler(pkt):
        	if pkt.haslayer(Dot11):
                	dot11_layer = pkt.getlayer(Dot11)

			# get the time format
			log_time = str(int(time.time()))
			if time_format == 'iso':
				log_time = datetime.now().isoformat()

			# the rssi value is contained in the dBm_AntSignal field
			# previously tried to get it from the notdecoded field but nothing was there using
			# the panda wireless USB dongle with the ralink chip
			rssi_val = pkt.dBm_AntSignal

			# find the vendor info from the mac address by parsing database
			try:
				parsed_mac = netaddr.EUI(pkt.addr2)
				vendor = parsed_mac.oui.registration().org
			except netaddr.core.NotRegisteredError, e:
				vendor = "UNKNOWN"

			# only add devices not already in the set
                	if dot11_layer.addr2 and (dot11_layer.addr2 not in devices):
                        	devices.add(dot11_layer.addr2)
				# print the number of unique devices followed by the MAC address
 				print len(devices), log_time, dot11_layer.addr2, dot11_layer.payload.name, rssi_val, vendor
	return packetHandler


# load in the blacklist of MAC addresses
blacklist = load_blacklist(sys.argv[4])
built_packetHandler = build_packetHandler(sys.argv[3], blacklist)
sniff(iface = sys.argv[1], count = int(sys.argv[2]), prn = built_packetHandler)

