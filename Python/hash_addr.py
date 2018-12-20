import hashlib

# hash_addr function takes a MAC address as a string and returns
# an encrypted string
def hash_addr(mac_addr):
	return hashlib.sha256(mac_addr.encode()).hexdigest()

def strip_semicolons(mac_addr):
	return mac_addr.replace(':','')

def trunc_hash(mac_addr, len):
	return hash_addr(strip_semicolons(mac_addr))[:len]
