#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe
# download pages using the Python requests library
from bs4 import BeautifulSoup
from urllib.error import HTTPError
from urllib.error import URLError
import re
import time
import math
from datetime import datetime
import requests
from selenium import webdriver  # open webdriver for specific browser
from selenium.webdriver.common.keys import Keys  # for necessary browser action
from selenium.webdriver.common.by import By  # For selecting html code
from selenium.webdriver.firefox.options import Options
import time
import os
import sys
import databaseconfig as cfg
import MySQLdb as my
from datetime import date, timedelta
import os
import sys
import database_insert as db_insert

# print("Content-Type: text/html")
# print()
# import cgi, cgitb
#
# cgitb.enable()  # for debugging
# form = cgi.FieldStorage()

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )


source1 = 'magic_brick'
city = "Bangalore"
# search_loc = 'Panathur'
# days_ago = 3
# current_timestamp=time.strftime('%Y-%m-%d %H:%M:%S')

search_loc = str(sys.argv[1])
days_ago=int(sys.argv[2])
current_timestamp=sys.argv[3]

loc = db_insert.get_m_location(search_loc)
print(loc)
def get_url(pge_cnt):
    if pge_cnt == 1:
        url = "https://m.magicbricks.com/mbs/property-for-rent/residential-real-estate?proptype=Apartment,Builder-Floor,Penthouse,Studio-Apt." \
              "&cityName=" + city + "&Locality=" + str(search_loc) + "&&pageOption=B&parameter=recent"
        print(url)
    else:
        url = "https://m.magicbricks.com/mbs/property-for-rent/residential-real-estate?proptype=Apartment,Builder-Floor,Penthouse,Studio-Apt." \
              "&cityName=" + city + "&Locality=" + str(search_loc) + "&&pageOption=B&parameter=recent/Page-" + str(pge_cnt-1)
        print(url)
    return (url)


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
created_timestamp=[]

def get_property_details():
    pge_cnt = 1
    while (pge_cnt < 10):
        url = get_url(pge_cnt)
        try:
            page = requests.get(url)
            pge_cnt = pge_cnt + 1
        except Exception as e:
            pge_cnt = 99
            print("Page not found")
            break;
        print(page.status_code)
        soup = BeautifulSoup(page.content, 'html.parser')
        all = soup.find_all("div", class_='resultBox srp-card-opt-3')
        for item in all:
            try:
                dt = item.find("div", class_='c3-posted').contents[1].text.replace("\n", "").replace("\t", "").lstrip()
                q, r = dt.split(',')
                year = r.replace("'", "").strip()
                # print(a)
                posted_dt = datetime.strptime(q + year, '%b %d%y')
                if datetime.now() - timedelta(days=days_ago) <= posted_dt:
                    posted_date.append(posted_dt.strftime('%Y-%m-%d'))
                    # pge_cnt = pge_cnt + 1
                else:
                    pge_cnt = 99
                    break;
            except:
                print("posted_dt error")
                posted_date.append('')
            try:
                source.append(source1)
            except:
                print("source error")
                source.append('')

            try:
                price.append(
                    item.find("div", class_='c3-price-rent').contents[0].strip().replace("\n", "").replace("\t", "") \
                        .replace("₹", "").replace(" Lac", "0000").replace('Call for Price', '9999999').replace(",",
                                                                                                               ""))  # cleaning , . alphabets etc.
            except:
                print("price error")
                price.append('')

            try:
                a = item.find(
                    "span", class_='c3-apartment fw-semi-bold').get_text().lstrip()+item.find(
                    "div", class_='c3-pri-row text-truncate').get_text().lstrip() + item.find("div",
                                                                                              class_='c3-locailty text-truncate').get_text().lstrip()
                heading.append(re.sub('\s+', ' ', a))
            except:
                print("heading error")
                heading.append('')

            try:
                locaton.append(search_loc)
            except:
                print("location error")
                locaton.append('')

            try:
                map1.append('')
            except:
                print("null field error")
                map1.append('')

            s = item.find("a", class_='c-btn mr2').attrs['onclick']
            ss = re.findall('"(.*?)"', s)[1]
            d = {p[:p.index('=')]: p[p.index('=') + 1:] for p in ss.split('&')}
            try:
                owner_dealer.append(d['owner'])
            except:
                owner_dealer.append(None)

            try:
                owner_dealer_name.append(d['name'])
            except:
                owner_dealer_name.append(None)

            try:
                super_buildup.append(d['area'])
            except:
                super_buildup.append(None)

            try:
                property_detail_link.append(
                    'https://m.magicbricks.com' + item.find("div", class_='imgSrp-c3').contents[1].attrs['href'])
            except:
                property_detail_link.append(None)
                print("error")

            try:
                created_timestamp.append(current_timestamp)
            except:
                created_timestamp.append('')

    print(posted_date)
    print(posted_date.__len__())
    print(source.__len__())
    print(heading.__len__())
    print(locaton.__len__())
    print(price.__len__())

    list = []
    for i in range(0, heading.__len__()):
        list.append(
            [source[i], heading[i], locaton[i], super_buildup[i], price[i], map1[i], map1[i],
             map1[i], map1[i], map1[i], owner_dealer[i], owner_dealer_name[i],
             posted_date[i], map1[i], property_detail_link[i],created_timestamp[i]])
    print(list)
    cursor = db.cursor()
    # try:
    sql = "insert into property_detail(SOURCE, HEADING, LOCATION, SUPER_BUILDUP, PRICE,SOCIETY, FEATURES, FLOOR_INFO, PROPERTY_AGE, PROPERTY_TYPE, OWNER_DEALER, OWNER_DEALER_NAME, POSTED_DATE, MAP,PROPERTY_DETAIL_URL,CREATED_TIMESTAMP)values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
    number_of_rows = cursor.executemany(sql, list)
    db.commit()
    print(str(number_of_rows) + " rows inserted successfully")
    # except:
    #     print(cursor._last_executed)
    #     print("Error while inserting data")

get_property_details()
