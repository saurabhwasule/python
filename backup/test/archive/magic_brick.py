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

start = time.time()
city1 = 'Bangalore'
source1 = 'magic_brick'
# search_loc = 'marathahalli'
# search_loc = 'kadubeesanahalli'
# search_loc = 'panathur'
# search_loc = 'Bellandur'
# import cgi, cgitb
#
# #cgitb.enable()  # for debugging
# form = cgi.FieldStorage()
data = []
#search_loc = form.getvalue('location')
#days_ago = form.getvalue('posted_since')
# search_loc = str(sys.argv[1])
# days_ago=int(sys.argv[2])
search_loc = 'Kadubeesanahalli'
days_ago=3
base_url = "https://www.magicbricks.com/property-for-rent/residential-real-estate?proptype=Multistorey-Apartment,Builder-Floor-Apartment,Penthouse,Studio-Apartment,Service-Apartment,Residential-House,Villa&"
url = base_url + str('Locality=') + search_loc + '&cityName=' + city1

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )

print(url)


# options = webdriver.FirefoxOptions()
# options.add_argument('headless')
# driver = webdriver.Firefox(options=options)
# driver = webdriver.PhantomJS(executable_path=r'D:\Setup\NEW SOFTWARE\Python\phantomjs-2.1.1-windows\bin\phantomjs.exe')
# driver.set_window_size(1120, 550)



def get_property_details():
    pge_cnt = 1
    while (pge_cnt < 5):
        driver = webdriver.PhantomJS(
            executable_path=r'D:\Setup\NEW SOFTWARE\Python\phantomjs-2.1.1-windows\bin\phantomjs.exe')
        driver.set_window_size(1024, 768)  # set browser size.

        # os.environ['NO_PROXY'] = '127.0.0.1'
        # driver = webdriver.Firefox()
        # options = Options()
        # options.add_argument("--headless")
        #driver = webdriver.Firefox(firefox_options=options,executable_path="C:\\Utility\\BrowserDrivers\\geckodriver.exe")
        # print("Firefox Headless Browser Invoked")
        # driver.get('http://google.com/')
        # driver.quit()

        # browser.get("http:example.com")  # Load page
        if pge_cnt == 1:
            driver.get(url)
            driver.save_screenshot('screen1.png')
            try:
                driver.implicitly_wait(10)  # seconds
                driver.find_element_by_css_selector("span.sortedLabel").click()
                driver.implicitly_wait(10)  # seconds
                driver.save_screenshot('screen2.png')
                driver.find_element_by_id("sort4").click()
                driver.save_screenshot('screen3.png')
                time.sleep(5)
                html = driver.page_source
            except Exception as e:
                print(str(e))
        else:
            try:
                driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
                time.sleep(5)
                html = driver.page_source
            except Exception as e:
                pge_cnt = 99
                break;
            # if 'NoSuchElementException' in str(e):
            #     print('NoSuchElementException found Existing...')


        soup = BeautifulSoup(html, 'html.parser')
        for i in ('m-srp-card SRCard m-srp-card--verified ', 'm-srp-card SRCard '):
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
            property_detail_link = []
            for item in all:
                a = item.find_all("div", class_="m-srp-card__post-date pull-right")[0].find("span").get_text()

                if a == "Yesterday":
                    posteddt = ((date.today() - timedelta(1)).strftime('%Y-%m-%d'))
                elif a == "Today":
                    posteddt = (date.today().strftime('%Y-%m-%d'))
                else:
                    posted_date1 = a.replace("th", '-').replace(" ", "").replace("st", '-').replace("rd", '-').replace(
                        "nd", '-') + str('-18')
                    date_object = datetime.strptime(posted_date1, '%d-%b-%y')
                    posteddt = date_object.strftime('%Y-%m-%d')

                if (datetime.now() - timedelta(days=days_ago))<= datetime.strptime(posteddt,'%Y-%m-%d'):
                    pge_cnt = pge_cnt + 1
                    try:
                        posted_date.append(posteddt)
                    except:
                        posted_date.append(None)
                else:
                    pge_cnt = 99
                    break;

                try:
                    source.append(source1)
                except:
                    source.append(None)

                try:
                    price.append(item.find("div", class_="m-srp-card__info flex__item").find("span").text.strip()
                                 .replace(",", "").replace(".", "").replace(" Lac", "0000").replace('Call for Price',
                                                                                                    '9999999'))  # cleaning , . alphabets etc.
                except:
                    price.append(None)

                try:
                    temp_heading = item.find("div", class_="m-srp-card__heading clearfix").get_text().strip().replace(
                        "What's near by", "").replace("\n", " ").replace("\t", " ")
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
                    super_buildup.append(item.find("span", class_="font-type-3").get_text())
                except:
                    super_buildup.append(None)

                try:
                    property_type.append(item.find_all("div", class_="m-srp-card__summary__info")[1].get_text())
                except:
                    property_type.append(None)
                try:
                    floor_info.append(item.find_all("div", class_="m-srp-card__summary__info")[0].get_text())
                except:
                    floor_info.append(None)

                try:
                    owner_dealer.append(item.find_all("div", class_="m-srp-card__advertiser__type")[0].text)
                except:
                    owner_dealer.append(None)

                try:
                    owner_dealer_name.append(item.find_all("div", class_="m-srp-card__advertiser__name")[0].text)
                except:
                    owner_dealer_name.append(None)

                try:
                    property_detail_link.append(
                        'https://www.magicbricks.com' + item.find_all("meta")[1].attrs['content'])
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
                        item.find_all("meta")[5].attrs['content'] + str(',') + item.find_all("meta")[6].attrs[
                            'content'])
                except:
                    map1.append(None)
                    print("error")

        list = []
        for i in range(0, heading.__len__()):
            list.append([source[i], heading[i], locaton[i], super_buildup[i], price[i], description[i],
                         floor_info[i], property_type[i], owner_dealer[i], owner_dealer_name[i],
                         posted_date[i], map1[i], property_detail_link[i]])
        # print(list)
        try:
            cursor = db.cursor()
            sql = "insert into property_detail(SOURCE, HEADING, LOCATION, SUPER_BUILDUP, PRICE, DESCRIPTION, FLOOR_INFO, PROPERTY_TYPE, OWNER_DEALER, OWNER_DEALER_NAME, POSTED_DATE, MAP,PROPERTY_DETAIL_URL)values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
            number_of_rows = cursor.executemany(sql, list)
            db.commit()
            print(str(number_of_rows) + " rows inserted successfully")
        except:
            print(cursor._last_executed)
            print("Error while inserting data")

old_stdout = sys.stdout
log_file = open("message.log","a")
sys.stdout = log_file
get_property_details()
elapsed = (time.time() - start)
print(elapsed, " seconds")
sys.stdout = old_stdout
log_file.close()

