#!/usr/bin/python

import sys

from scapy.all import *

devices = set()

def PacketHandler(pkt):
        if pkt.haslayer(Dot11):
                dot11_layer = pkt.getlayer(Dot11)
		# only add devices not already in the set
                if dot11_layer.addr2 and (dot11_layer.addr2 not in devices):
                        devices.add(dot11_layer.addr2)
			# print the number of unique devices followed by the MAC address
                        print len(devices), dot11_layer.addr2

sniff(iface = sys.argv[1], count = int(sys.argv[2]), prn = PacketHandler)

