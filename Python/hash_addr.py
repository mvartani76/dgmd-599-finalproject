import hashlib

# hash_addr function takes a MAC address as a string and returns
# an encrypted string
def hash_addr(mac_addr):
	return hashlib.sha256(mac_addr.encode()).hexdigest()

# strip semi-colons from mac address string
def strip_semicolons(mac_addr):
	return mac_addr.replace(':','')

# hash the mac address and truncate to len characters
def trunc_hash(mac_addr, len):
	return hash_addr(strip_semicolons(mac_addr))[:len]

# Convert string to mac address format with semicolons every two characters
def conv_mac(str, sep=':'):
    return sep.join([str[i:i+2] for i in range(0, len(str), 2)])
