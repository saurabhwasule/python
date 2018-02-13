import database_insert as db_insert
import sys
import databaseconfig as cfg
import MySQLdb as my
import requests
import time
from bs4 import BeautifulSoup

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )
city = "Bangalore"

def check_mlocation(search_loc):
    loc=search_loc#.replace("  ","-").replace(" ","-")
    url = "https://m.magicbricks.com/mbs/property-for-rent/residential-real-estate?proptype=Apartment,Builder-Floor,Penthouse,Studio-Apt." \
          "&cityName=" + city + "&Locality=" + loc + "&&pageOption=B&parameter=recent"
    # print(url)
    try:
        page = requests.get(url)
        time.sleep(15)
        status=page.status_code
        # print(status)
        soup = BeautifulSoup(page.content, 'html.parser')
        status = soup.find("h1", id='searchResultTextId').get_text().strip()

        print(search_loc + '#' + status)
        # print(elapsed, " seconds")
        # insert_mlocation(loc,status)
    except:
        status="Error in processing"
        print(search_loc + '#' + status)
        pass

def insert_mlocation(name,status):
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    # Prepare SQL query to INSERT a record into the database.
    sql = "INSERT INTO m_locality(name,status) \
               VALUES ('%s', '%s')" % \
             (name, status)
    try:
        # Execute the SQL command
        cursor.execute(sql)
        db.commit()
    except:
        print(cursor._last_executed)
        print("Error while inserting data")
        db.close()


def get_region():
    cursor2 = db.cursor()
    cursor2.execute("select trim(m_name) from locality where region='east' and m_name is not null")
    # Fetch all the rows in a list of lists.
    results = cursor2.fetchall()
    for row in results:
        m_name = row[0]
        # print(m_name)
        old_stdout = sys.stdout
        log_file = open("mbrick_url_check.log", "a")
        sys.stdout = log_file
        check_mlocation(m_name)
        sys.stdout = old_stdout
        log_file.close()
    return m_name

# old_stdout = sys.stdout
# log_file = open("mbrick_url_check.log", "a")
# sys.stdout = log_file
r1=get_region()
# print(elapsed, " seconds")
# sys.stdout = old_stdout
# log_file.close()
