#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe
print("Content-Type: text/html")    
print()                             
import cgi,cgitb
cgitb.enable() #for debugging
form = cgi.FieldStorage()
print("Location:",form.getvalue('location')+"<br/>")
print("Posted_since:",form.getvalue('posted_since')+"<br />")
