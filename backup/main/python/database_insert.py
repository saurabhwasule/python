import databaseconfig as cfg
import MySQLdb as my

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )

def insert_property_details(list = []):
    if list.__len__()!=0:
        cursor = db.cursor()
        try:
            sql = "insert into property_detail(SOURCE, HEADING, LOCATION, SUPER_BUILDUP, PRICE,SOCIETY, FEATURES, FLOOR_INFO, PROPERTY_AGE, PROPERTY_TYPE, OWNER_DEALER, OWNER_DEALER_NAME, POSTED_DATE, MAP,PROPERTY_DETAIL_URL,CREATED_TIMESTAMP)values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
            number_of_rows = cursor.executemany(sql, list)
            db.commit()
            print(str(number_of_rows) + " rows inserted successfully. ")
        except:
            print(cursor._last_executed)
            print("Error while inserting data.")
    # finally:
    #     db.close()
    else:
        print("No record found.")

def search_details(id,loc,ct,psd):
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    # Prepare SQL query to INSERT a record into the database.
    # sql = "insert into search_detail VALUES(%s,%s,%s,%s)" % \
    #       (str(id), str(loc), str(ct),str(psd))
    sql = "INSERT INTO search_detail(id, \
           location, created_timestamp, posted_since_indays) \
           VALUES ('%s', '%s', '%s', '%s' )" % \
          (id, loc, ct,psd)
    try:
        # Execute the SQL command
        cursor.execute(sql)
        db.commit()
    except:
        print(cursor._last_executed)
        print("Error while inserting data")

def get_m_location(loc1):
    cursor2 = db.cursor()
    try:
        cursor2.execute("select trim(m_name) from locality where region='east' and m_name is not null and name = %s", (loc1,))
    # Fetch  magic brick location
        results = cursor2.fetchall()
        for row in results:
            m_name = row[0]
        #print(m_name)
    except:
        print(cursor._last_executed)
        print("Error while fetching data")
    if results.__len__()==0:
        m_name=None
    return m_name
# db.close()

