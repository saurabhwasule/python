#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe
import os
import sys
from subprocess import *
from datetime import datetime, timedelta
import time
import search_processing as sp
import database_insert as db_insert

print("Content-Type: text/html")
print()
import cgi, cgitb

cgitb.enable()  # for debugging
form = cgi.FieldStorage()
search_loc = 'panathur'
days_ago = 7
# search_loc = form.getvalue('location')
# days_ago = form.getvalue('posted_since')
# now = (datetime.datetime.now()).strftime("%d%m%Y%H%M%S")+str(round(time.time() * 1000) )#current time +millisecond
now = time.strftime('%Y-%m-%d %H:%M:%S')
id = time.strftime('%Y%m%d%H%M%S')



def main_program():
    # option 1
    loc = db_insert.get_m_location(search_loc)
    if loc is None or loc == "":
        print(search_loc+" location in not present in MagicBrick....")
        pass
    else:
        # run child script 1
        p = Popen([r'm_magic_brick.py ', search_loc, str(days_ago), now, "rentalsearch"], shell=True, stdin=PIPE, stdout=PIPE)
        output = p.communicate()
        print(output[0])

    # run child script 2
    p = Popen([r'99_acres.py ', search_loc, str(days_ago), now, "rentalsearch"], shell=True, stdin=PIPE, stdout=PIPE)
    output = p.communicate()
    print(output[0])

start = time.time()
old_stdout = sys.stdout
log_file = open("logs\message.log", "a")
sys.stdout = log_file
print(id+" "+search_loc+" "+str(days_ago))
latest_timestamp = sp.is_recent(search_loc, str(days_ago))
sp.insert_search_info(id, search_loc, now, str(days_ago))
if latest_timestamp[0] is None:
	main_program()
	created_date_time1 = now
else:
	if latest_timestamp[0] >= datetime.now() - timedelta(hours=4):
		created_date_time1 = latest_timestamp[0]
		print(search_loc+" location searched in past 4 hour.Latest known timestamp: "+str(created_date_time1))
	else:
		main_program()
		created_date_time1 = now
elapsed = (time.time() - start)
print(elapsed, " seconds")
sys.stdout = old_stdout

log_file.close()
print('<html>')
print('<head>')
print(
   '<meta HTTP-EQUIV="REFRESH" content="0; url=http://rentalsearch.hopto.org:1234/search_property.php?location=' + search_loc + '&posted_since=' + str(
       days_ago) + '&created_date_time=' + str(created_date_time1).replace(" ", ";")
   + '">')
# print(
# 	'<meta HTTP-EQUIV="REFRESH" content="0; url=http://localhost:1234/main/search_property.php?location=' + search_loc + '&posted_since=' + str(
# 		days_ago) + '&created_date_time=' + str(created_date_time1).replace(" ", ";")
# 	+ '">')
print('</head>')
print('</html>')
