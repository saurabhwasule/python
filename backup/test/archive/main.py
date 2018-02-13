#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe

# from subprocess import *
#
# #run child script 1
# p = Popen([r'C:\TEMP\childScript1.py', "ArcView"], shell=True, stdin=PIPE, stdout=PIPE)
# output = p.communicate()
# print (output[0])
#
# #run child script 2
# p = Popen([r'C:\TEMP\childScript2.py', "ArcEditor"], shell=True, stdin=PIPE, stdout=PIPE)
# output = p.communicate()
# print (output[0])

import os
print("Content-Type: text/html")
print()
import cgi,cgitb
cgitb.enable() #for debugging
form = cgi.FieldStorage()
# search_loc = form.getvalue('location')
# days_ago = form.getvalue('posted_since')
search_loc = 'Kadubeesanahalli'
days_ago=3
os.system("python 99_acres.py "+str(search_loc)+" "+str(days_ago)+"")
os.system("python magic_brick.py "+str(search_loc)+" "+str(days_ago)+"")

print('<html>')
print('<head>')
print(
    '<meta HTTP-EQUIV="REFRESH" content="0; url=http://localhost/test/search_property.php?location=' + search_loc + '&posted_since=' + str(
        days_ago) + '">')
print('</head>')
print('</html>')


