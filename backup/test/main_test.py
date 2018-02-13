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
# search_loc = 'Panathur'
# days_ago = 3
search_loc = form.getvalue('location')
days_ago = form.getvalue('posted_since')
now = time.strftime('%Y-%m-%d %H:%M:%S')
id = time.strftime('%Y%m%d%H%M%S')


def main_program():
    # option 1
    loc = db_insert.get_m_location(search_loc)
    print(loc)
    if loc is None or loc == "":
        print(search_loc+"Unknown location in MagicBrick....")
        pass
    else:
        # run child script 1
        print("search_loc"+search_loc)
        p = Popen([r'm_magic_brick.py ', str(search_loc), str(days_ago), now, "ArcView"], shell=True, stdin=PIPE, stdout=PIPE)
        output = p.communicate()
        print(output[0])

    # # run child script 2
    # p = Popen([r'99_acres.py ', search_loc, str(days_ago), now, "ArcView"], shell=True, stdin=PIPE, stdout=PIPE)
    # output = p.communicate()
    # print(output[0])

start = time.time()
old_stdout = sys.stdout
log_file = open("message.log", "a")
sys.stdout = log_file
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
        days_ago) + '&created_date_time=' + str(created_date_time1).replace(" ", ";")
    + '">')
print('</head>')
print('</html>')
