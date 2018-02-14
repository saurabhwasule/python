#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe
print("Content-Type: text/html")    
print()        
#!/usr/bin/env python
from bs4 import BeautifulSoup
from datetime import datetime
import time
import requests
import MySQLdb as my
import json
import sys
import databaseconfig as cfg

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )

start = time.time()
source1 = '99_acres'

import cgi,cgitb
cgitb.enable() #for debugging
form = cgi.FieldStorage()
data = []
search_loc = form.getvalue('location')
#search_loc = 'Kadubeesanahalli'

def get_region(col1_value):
    cursor2 = db.cursor()
    cursor2.execute("""SELECT distinct region FROM locality WHERE name = %s""", (col1_value,))
    # Fetch all the rows in a list of lists.
    results = cursor2.fetchall()
    for row in results:
        region1 = row[0]
    return region1

def get_url(search_loc):
    region = get_region(search_loc)
    url = str('https://www.99acres.com/rent-property-in-' + search_loc + str('-bangalore-') + region + '-ffid').replace(
        " ", "-")
    print(url)
    page = requests.get(url)
    print(page.status_code)
    soup = BeautifulSoup(page.content, 'html.parser')
    # getting last page number
    max_array = soup.find_all('a', class_='pgsel').__len__() - 1
    page_count = soup.find_all('a', class_='pgsel')[max_array]
    pge_cnt=page_count.get('value')
    return (pge_cnt,url)

def get_property_details(pge_cnt,url):

    print("Total number of Pages - " + str(pge_cnt))
    time.sleep(5)
    for num in range(1, int(pge_cnt) + 1):
        new_url = str(url) + '-page-' + str(num)
        print(new_url)
        page = requests.get(new_url)
        print(page.status_code)
        soup = BeautifulSoup(page.content, 'html.parser')
        # scrape offline site
        # f = open("D:\\Setup\\NEW SOFTWARE\\Python\\Programs\\rental_search\\99_acres\\Webpages\\Property for rent in Bangalore South - Rental properties in Bangalore South.html")
        # soup = BeautifulSoup(f, 'html.parser')
        all = soup.find_all("div", class_='srpWrap')
        time.sleep(5)
        # print(all)
        for item in all:
            try:
                a = all.find("div", {"class": "srpDataWrap"})
                print(a)
            except:
                pass
        l = []
        source = []
        price = []
        heading = []
        super_buildup = []
        society = []
        property_age = []
        property_type = []
        floor_info = []
        features = []
        locaton = []
        map1 = []
        description = []
        posted_date = []
        property_detail_link = []
        owner_dealer_name = []
        owner_dealer = []
        for item in all:

            try:
                source.append(source1)
            except:
                source.append('')

            try:
                price.append(item.find("b", itemprop="price").next.replace(",", "")
                             .replace(",", "").replace(".", "").replace(" Lac", "0000").replace('Call for Price',
                                                                                                '9999999'))  # cleaning , . alphabets etc.

            except:
                price.append('')

            try:
                heading.append(item.find("div", class_="wrapttl").find("a").text)
            except:
                heading.append('')
            try:
                super_buildup.append(item.find("div", class_="srpDataWrap").find("b").text)
            except:
                super_buildup.append('')
            try:
                society.append(item.find("span", class_="doElip").find("b").text.replace(" ", "").replace("\n", ""))
            except:
                society.append('')
            try:
                property_age.append(
                    item.find("div", class_='srpDataWrap').contents[9].contents[2].text.replace("/", "").replace("  ",
                                                                                                                 "").strip())
            except:
                property_age.append('')
            try:
                property_type.append(
                    item.find("div", class_='srpDataWrap').contents[9].contents[3].text.replace("/", "").replace("  ",
                                                                                                                 "").strip())
            except:
                property_type.append('')
            try:
                floor_info.append(
                    item.find("div", class_='srpDataWrap').contents[9].contents[4].text.replace("/", "").strip())
            except:
                floor_info.append('')
            try:
                features.append(item.find("div", class_='iconDiv fc_icons fcInit').contents[1].attrs.get('value'))
            except:
                features.append('')
            a = item.find("div", class_='lf f13 hm10 mb5').text.replace("\n", "").replace(" ", "")
            p, q, r = a.split(':')
            z, x = q.split()
            try:
                owner_dealer.append(p)
            except:
                owner_dealer.append('')

            try:
                owner_dealer_name.append(z)
            except:
                owner_dealer_name.append('')

            try:
                date_object = datetime.strptime(r, '%b%d,%Y')
                posted_date.append(date_object.strftime('%Y-%m-%d'))
            except:
                posted_date.append('')

            try:
                description.append(item.find("span", class_='srpDes').text.replace("\n", "")).replace("'", "")
            except:
                description.append('')

            try:
                property_detail_link.append(
                    str("https://www.99acres.com/") +
                    item.find("div", class_="wrapttl").find("a", itemprop="url").attrs[
                        'href'])
            except:
                property_detail_link.append('')

            try:
                map1.append(item.find("div", class_="wrapttl").find("i", class_="uline").attrs['data-maplatlngzm'])
            except:
                map1.append('')

            try:
                locaton.append(search_loc)
            except:
                locaton.append('')
            # print(price)
            # print(heading)
            # print(super_buildup)
            # print(society)
            # print(property_age)
            # print(property_type)
            # print(floor_info)
            # print(features)
            # print(locaton)
            # print(map1)
            # print(description)
            # print(posted_date)
            # print(owner_dealer_name)
            # print(owner_dealer)
        list = []
        for i in range(0, heading.__len__()):
            list.append(
                [source[i], heading[i], locaton[i], super_buildup[i], price[i], society[i], features[i],
                 floor_info[i], property_age[i], property_type[i], owner_dealer[i], owner_dealer_name[i],
                 posted_date[i], map1[i], property_detail_link[i]])
        # print(list.__len__())
        # print(list)
        # insert_property_details(list)
        cursor = db.cursor()
        try:
            sql = "insert into property_detail(SOURCE, HEADING, LOCATION, SUPER_BUILDUP, PRICE,SOCIETY, FEATURES, FLOOR_INFO, PROPERTY_AGE, PROPERTY_TYPE, OWNER_DEALER, OWNER_DEALER_NAME, POSTED_DATE, MAP,PROPERTY_DETAIL_URL)values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
            number_of_rows = cursor.executemany(sql, list)
            db.commit()
            print(str(number_of_rows) + " rows inserted successfully")
        except:
            print(cursor._last_executed)
            print("Error while inserting data")
        # finally:
        #     db.close()


# insert_bangalore_locality()

#search_loc = "Electronic City"
# search_loc = 'Kadubeesanahalli'
# search_loc = 'Bellandur' #UnicodeEncodeError: 'latin-1' codec can't encode character '\u2013' page number 5  due to description column
# search_loc = "Marathahalli"  # UnicodeEncodeError: 'latin-1' codec can't encode character '\u2022' in position 91 page 13 due to description column
#search_loc = sys.argv[1]
old_stdout = sys.stdout
log_file = open("message.log","a")
sys.stdout = log_file
page_count,url=get_url(search_loc)
get_property_details(page_count,url)
elapsed = (time.time() - start)
print(elapsed, " seconds")
sys.stdout = old_stdout

log_file.close()

print ('<html>')
print ('<head>')
print ('<meta HTTP-EQUIV="REFRESH" content="0; url=http://localhost/main/search_property.php?location='+search_loc+'">')
print ('</head>')
print ('</html>')
