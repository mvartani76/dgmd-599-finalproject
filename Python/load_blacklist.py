#!/usr/bin/python

import sys

# function loads blacklisted mac addresses from a file into a set
# we will use this to have the system ignore devices such as printers
# and smart TVs to get a more accurate count of mobile device traffic
# the file should have one mac address per line
def load_blacklist(filename):
	blacklist = set(line.strip() for line in open(filename))
	return blacklist

# uncomment out the lines below if you want to test this function by itself
# if used with other python files, comment out as the sys.argv[1] will
# cause errors as the system arguments will not align

# use the command line to choose the specific file to load in
# blacklist = load_blacklist(sys.argv[1])
# print  blacklist
