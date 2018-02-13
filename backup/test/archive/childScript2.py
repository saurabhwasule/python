#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe
# print("Content-Type: text/html")
# print()
# import cgi,cgitb
# cgitb.enable() #for debugging
# form = cgi.FieldStorage()
import sys
import time
old_stdout = sys.stdout
log_file = open("message.log","a")
sys.stdout = log_file
print ("Script2")
print(str(sys.argv[1]))
print(str(sys.argv[2]))
sys.stdout = old_stdout
log_file.close()