#!C:/Users/Saurabh/AppData/Local/Programs/Python/Python36/python.exe
# !/usr/bin/env python
from bs4 import BeautifulSoup
from datetime import datetime, timedelta
import time
import requests
import MySQLdb as my
import json
import sys
import databaseconfig as cfg
from selenium import webdriver  # open webdriver for specific browser
from selenium.webdriver.common.keys import Keys  # for necessary browser action
from selenium.webdriver.common.by import By  # For selecting html code
import os
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

start = time.time()
source1 = '99_acres'
# search_loc = 'panathur'
# days_ago = 3

search_loc = str(sys.argv[1])
days_ago=int(sys.argv[2])
current_timestamp=sys.argv[3]

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
list = []
def get_property_details(url):
    # driver = webdriver.Firefox()
    driver = webdriver.PhantomJS(
        executable_path=r'D:\Setup\NEW SOFTWARE\Python\phantomjs-2.1.1-windows\bin\phantomjs.exe')

    pge_cnt = 1

    # for num in range(1, int(pge_cnt) + 1):
    while (pge_cnt < 10):

        if pge_cnt == 1:
            driver.get(url)
            #print("before sort click")
            driver.find_element_by_css_selector("i.fiter-icons-sprite.filter-arrowDown").click()
            driver.find_element_by_xpath("//ul[@id='lf_sortBy_lst']/li[2]/label").click()
            driver.find_element_by_id("lf-sort-by").click()
            time.sleep(3)
            #print("after sort click")
            pge_cnt = pge_cnt + 1
        else:
            try:
                # print("before next click")
                # driver.implicitly_wait(10)  # seconds
                driver.set_window_size(1024, 768)
                # driver.save_screenshot('before_next.jpg')
                driver.find_element_by_xpath("//table[@id='ff_TRANSACTION_TYPE_56_ul']/tbody/tr[3]").click()
                driver.find_element_by_link_text("Next >>").click()
                # driver.implicitly_wait(10)  # seconds
                time.sleep(3)
                # print("after next click")
            except Exception as e:
                pge_cnt = 99
                print(e)
                break;
                # if 'NoSuchElementException' in str(e):
                #     print('NoSuchElementException found Existing...')

        html = driver.page_source
        soup = BeautifulSoup(html, 'html.parser')
        all = soup.find_all("div", class_='srpWrap')


        for item in all:

            a = item.find("div", class_='lf f13 hm10 mb5').text.replace("\n", "").replace(" ", "")
            p, q, r = a.split(':')
            z, x = q.split()

            if datetime.now() - timedelta(days=days_ago) <= datetime.strptime(r, '%b%d,%Y'):
                # pge_cnt = pge_cnt + 1
                try:
                    date_object = datetime.strptime(r, '%b%d,%Y')
                    posted_date.append(date_object.strftime('%Y-%m-%d'))
                    # print(date_object.strftime('%Y-%m-%d'))
                except:
                    posted_date.append('')
            else:
                pge_cnt = 99
                break;

            try:
                owner_dealer.append(p.replace('Dealer','Agent'))
            except:
                owner_dealer.append('')

            try:
                owner_dealer_name.append(z)
            except:
                owner_dealer_name.append('')

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
                super_buildup.append(item.find("div", class_="srpDataWrap").find("b").text.replace("Sq.Ft.","sqft"))
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

            try:
                created_timestamp.append(current_timestamp)
            except:
                created_timestamp.append('')


        for i in range(0, heading.__len__()):
            list.append(
                [source[i], heading[i], locaton[i], super_buildup[i], price[i], society[i], features[i],
                 floor_info[i], property_age[i], property_type[i], owner_dealer[i], owner_dealer_name[i],
                 posted_date[i], map1[i], property_detail_link[i],created_timestamp[i]])

    # db_insert.insert_property_details(list)

    if list.__len__()!=0:
        cursor = db.cursor()
        try:
            sql = "insert into property_detail(SOURCE, HEADING, LOCATION, SUPER_BUILDUP, PRICE,SOCIETY, FEATURES, FLOOR_INFO, PROPERTY_AGE, PROPERTY_TYPE, OWNER_DEALER, OWNER_DEALER_NAME, POSTED_DATE, MAP,PROPERTY_DETAIL_URL,CREATED_TIMESTAMP)values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
            number_of_rows = cursor.executemany(sql, list)
            db.commit()
            print(str(number_of_rows) + " rows inserted successfully")
        except:
            print(cursor._last_executed)
            print("Error while inserting data")
    # finally:
    #     db.close()
    else:
        print("No record found in 99acres.")


# insert_bangalore_locality()

# search_loc = "Electronic City"
# search_loc = 'Kadubeesanahalli'
# search_loc = 'Bellandur' #UnicodeEncodeError: 'latin-1' codec can't encode character '\u2013' page number 5  due to description column
# search_loc = "Marathahalli"  # UnicodeEncodeError: 'latin-1' codec can't encode character '\u2022' in position 91 page 13 due to description column
# search_loc = sys.argv[1]

url = get_url(search_loc)
get_property_details(url)

