#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe
import os
import sys
from subprocess import *
import time

print("Content-Type: text/html")
print()
import cgi, cgitb

cgitb.enable()  # for debugging
form = cgi.FieldStorage()
#search_loc = 'Kadubeesanahalli'
#days_ago = 7
search_loc = form.getvalue('location')
days_ago = form.getvalue('posted_since')
#now = (datetime.datetime.now()).strftime("%d%m%Y%H%M%S")+str(round(time.time() * 1000) )#current time +millisecond
now = time.strftime('%Y-%m-%d %H:%M:%S')

### option 1

#run child script 1
p = Popen([r'm_magic_brick.py ',search_loc,str(days_ago),now , "ArcView"], shell=True, stdin=PIPE, stdout=PIPE)
output = p.communicate()
print (output[0])

# run child script 2
p = Popen([r'99_acres.py ', search_loc, str(days_ago), now, "ArcView"], shell=True, stdin=PIPE, stdout=PIPE)
output = p.communicate()
print(output[0])

### option 2
# os.system("python 99_acres.py " + str(search_loc) + " " + str(days_ago) + "")
# os.system("python magic_brick.py " + str(search_loc) + " " + str(days_ago) + "")
# try:
#     # os.system("python childScript1.py "+str(search_loc)+" "+str(days_ago)+"")
#     # os.system("python childScript2.py "+str(search_loc)+" "+str(days_ago)+"")
#     os.system("start cmd /K childScript1.py " + str(search_loc) + " " + str(days_ago) + "")
#     os.system("start cmd /K childScript1.py " + str(search_loc) + " " + str(days_ago) + "")
#     print("no error" + "<br />")
# except:
#     print("Error" + "<br />")

### option 3

# #run child script 1
# p = Popen([sys.executable,'99_acres.py',search_loc,str(days_ago)])
# output = p.communicate()
# print (output[0])
#
# #run child script 1
# p = Popen([sys.executable,'magic_brick.py',search_loc,str(days_ago)])
# output = p.communicate()
# print (output[0])

### option 4

# # #run child script 1
# call('python 99_acres.py '+str(search_loc)+" "+str(days_ago))
# call('python magic_brick.py '+str(search_loc)+" "+str(days_ago))

print('<html>')
print('<head>')
print(
    '<meta HTTP-EQUIV="REFRESH" content="0; url=http://localhost/test/search_property.php?location=' + search_loc + '&posted_since=' + str(
        days_ago) + '">')
print('</head>')
print('</html>')
