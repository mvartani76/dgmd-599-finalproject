import hashlib

# hash_addr function takes a MAC address as a string and returns
# an encrypted string
def hash_addr(mac_addr):
	return hashlib.sha256(mac_addr.encode()).hexdigest()
  
