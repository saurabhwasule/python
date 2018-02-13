import requests
from bs4 import BeautifulSoup
import MySQLdb as my
import databaseconfig as cfg
import time

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )
def insert_bangalore_locality():
    city1 = 'Bangalore'
    bangalore_region = ['central-ffid', 'east-ffid', 'west-ffid', 'north-ffid', 'south-ffid']
    base_url = "https://www.99acres.com/rent-property-in-bangalore-"

    for num in range(0, bangalore_region.__len__()):
        new_url = str(base_url) + str(bangalore_region[num])
        page = requests.get(new_url)
        print(page.status_code)
        time.sleep(5)
        soup = BeautifulSoup(page.content, 'html.parser')

        locality = []
        id = []
        city = []
        region = []
        exp = soup.find_all('input', attrs={"id": "filter_data"})
        abc = exp[0].get('value')  # len(exp) = 1
        result = json.loads(str(abc))
        # get Top_Results_Array
        for i in range(0, (result['Locality']['Top_Results_Array'].__len__())):
            city.append(city1)
            region.append(bangalore_region[num].replace("-ffid", ""))
            id.append((result['Locality']['Top_Results_Array'][str(i)]['ID']))
            locality.append((result['Locality']['Top_Results_Array'][str(i)]['LABEL']))

        # get More_Locality_Array
        for j in range(0, (result['Locality']['More_Locality_Array'].__len__())):
            city.append(city1)
            region.append(bangalore_region[num].replace("-ffid", ""))
            id.append((result['Locality']['More_Locality_Array'][str(j)]['ID']))
            locality.append((result['Locality']['More_Locality_Array'][str(j)]['LABEL']))

        for i in range(0, id.__len__()):
            data.append([id[i], locality[i], region[i], city[i]])
        print(data)

    cursor = db.cursor()
    sql1 = "insert into locality(id,name,region,city) values (%s, %s,%s,%s)"

    number_of_rows = cursor.executemany(sql1, data)
    db.commit()

    db.close()

insert_bangalore_locality()