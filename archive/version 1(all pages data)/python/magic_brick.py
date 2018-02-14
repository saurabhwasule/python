#download pages using the Python requests library
from bs4 import BeautifulSoup
from urllib.error import HTTPError
from urllib.error import URLError
import re
import time
import math
import pandas as pd
from datetime import datetime
import requests
from selenium import webdriver    #open webdriver for specific browser
from selenium.webdriver.common.keys import Keys   # for necessary browser action
from selenium.webdriver.common.by import By    # For selecting html code
import time
import os
import MySQLdb as my

start = time.time()
from datetime import date, timedelta
yesterday = date.today() - timedelta(1)
now=date.today()
source1='magic_brick'
city1 = 'Bangalore'
# search_loc = 'marathahalli'
# search_loc = 'kadubeesanahalli'
# search_loc = 'panathur'
search_loc = 'Bellandur'

db = my.connect(host="127.0.0.1",
                user="saurabh",
                passwd="saurabh",
                db="rental"
                )

start = time.time()
source1 = 'magic_brick'

base_url="https://www.magicbricks.com/property-for-rent/residential-real-estate?proptype=Multistorey-Apartment,Builder-Floor-Apartment,Penthouse,Studio-Apartment,Service-Apartment,Residential-House,Villa&"
url=base_url+str('Locality=')+search_loc+'&cityName='+city1
print(url)
page = requests.get(url)
print(page.status_code)
soup = BeautifulSoup(page.content, 'html.parser')
# #getting last page number
page_count = soup.find('span',id='pageCount')
print("Total number of Pages - " + str(page_count.text))
time.sleep(10)

os.environ['NO_PROXY'] = '127.0.0.1'
# options = webdriver.FirefoxOptions()
# options.add_argument('headless')
# driver = webdriver.Firefox(options=options)
# driver = webdriver.PhantomJS(executable_path=r'D:\Setup\NEW SOFTWARE\Python\phantomjs-2.1.1-windows\bin\phantomjs.exe')
# driver.set_window_size(1120, 550)
driver = webdriver.Firefox()
driver.get(url)

def url_scrp():
	for i in range(0,math.trunc(float(page_count.text)+2)):
		driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
		time.sleep(10)
url_scrp()

html = driver.page_source
soup = BeautifulSoup(html,'html.parser')


for i in ('m-srp-card SRCard m-srp-card--verified ','m-srp-card SRCard '):
    all = soup.find_all("div", class_=i)
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
    owner_dealer_name = []
    owner_dealer = []
    property_detail_link=[]
    for item in all:
        try:
            source.append(source1)
        except:
            source.append(None)

        try:
            price.append(item.find("div", class_="m-srp-card__info flex__item").find("span").text.strip()
                         .replace(",","").replace(".","").replace(" Lac","0000").replace('Call for Price','9999999')) #cleaning , . alphabets etc.
        except:
            price.append(None)

        try:
            temp_heading=item.find("div", class_="m-srp-card__heading clearfix").get_text().strip().replace("What's near by","").replace("\n"," ").replace("\t"," ")
            heading.append(re.sub('\s+', ' ', temp_heading))
        except:
            heading.append(None)
            print("error")

        try:
            locaton.append(search_loc)
        except:
            locaton.append(None)
            print("error")

        try:
            super_buildup.append(item.find("span",class_="font-type-3").get_text())
        except:
            super_buildup.append(None)

        try:
            property_type.append(item.find_all("div",class_="m-srp-card__summary__info")[1].get_text())
        except:
            property_type.append(None)
        try:
            floor_info.append(item.find_all("div",class_="m-srp-card__summary__info")[0].get_text())
        except:
            floor_info.append(None)

        try:
            owner_dealer.append(item.find_all("div",class_="m-srp-card__advertiser__type")[0].text)
        except:
            owner_dealer.append(None)

        try:
            owner_dealer_name.append(item.find_all("div",class_="m-srp-card__advertiser__name")[0].text)
        except:
            owner_dealer_name.append(None)

        try:
            a = item.find_all("div", class_="m-srp-card__post-date pull-right")[0].find("span").get_text()

            if a == "Yesterday":
                posted_date.append(yesterday.strftime('%y-%m-%d'))
            elif a == "Today":
                posted_date.append(now.strftime('%y-%m-%d'))
            else:
                posted_date1=a.replace("th",'-').replace(" ","").replace("st",'-').replace("rd",'-').replace("nd",'-')+str('-17')
                date_object = datetime.strptime(posted_date1, '%d-%b-%y')
                posted_date.append(date_object.strftime('%Y-%m-%d'))
        except:
            posted_date.append(None)

        try:
            property_detail_link.append('https://www.magicbricks.com'+item.find_all("meta")[1].attrs['content'])
        except:
            property_detail_link.append(None)
            print("error")

        try:
            description.append(item.find_all("meta")[0].attrs['content'])
        except:
            description.append(None)
            print("error")

        try:
            map1.append(
                item.find_all("meta")[5].attrs['content'] + str(',') + item.find_all("meta")[6].attrs['content'])
        except:
            map1.append(None)
            print("error")

    # print(source.__len__())
    # print(heading.__len__())
    # print(locaton.__len__())
    # print(locaton)
    # print(super_buildup.__len__())
    # print(price.__len__())
    # print(description.__len__())
    # print(floor_info.__len__())
    # print(property_type.__len__())
    # print(owner_dealer_name.__len__())
    # print(owner_dealer.__len__())
    # print(posted_date.__len__())
    # print(map1.__len__())
    # print(map1)
    # print(property_detail_link.__len__())
    #
    # print(source)
    # print(heading)
    # print(super_buildup)
    # print(price)
    #
    # print(floor_info)
    # print(property_type)
    # print(owner_dealer_name)
    # print(owner_dealer)
    # print(posted_date)
    list = []
    for i in range(0,heading.__len__()):
        list.append([source[i], heading[i], locaton[i], super_buildup[i], price[i], description[i],
                     floor_info[i],property_type[i], owner_dealer[i], owner_dealer_name[i],
                     posted_date[i], map1[i],property_detail_link[i]])
    print(list)
    try:
        cursor = db.cursor()
        sql="insert into property_detail(SOURCE, HEADING, LOCATION, SUPER_BUILDUP, PRICE, DESCRIPTION, FLOOR_INFO, PROPERTY_TYPE, OWNER_DEALER, OWNER_DEALER_NAME, POSTED_DATE, MAP,PROPERTY_DETAIL_URL)values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        number_of_rows = cursor.executemany(sql, list)
        db.commit()
        print(str(number_of_rows) + " rows inserted successfully")
    except:
        print(cursor._last_executed)
        print("Error while insterting data")

elapsed = (time.time() - start)
print(elapsed, " seconds")

#
# # with open('output/magic_brick_data1.txt', 'a') as f:
# filename = strftime("Output%Y%m%d%H%M%S.txt", gmtime())
# with open('output/' + filename, 'a') as f:
#     df.to_csv(f, header=False, sep='|', encoding='utf-8')