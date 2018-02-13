import databaseconfig as cfg
import MySQLdb as my

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )

def insert_property_details(col1_value):
    cursor = db.cursor()
    try:
        sql = "insert into property_detail(source, heading, location, super_buildup, price,society, features, floor_info, property_age, property_type, owner_dealer, owner_dealer_name, posted_date, map,property_detail_url,created_timestamp)values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        number_of_rows = cursor.executemany(sql, list)
        db.commit()
        print(str(number_of_rows) + " property details inserted successfully")
    except:
        print(cursor._last_executed)
        print("Error while inserting data")

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
    # cursor2.prepare('select trim(m_name) from locality where region=east and m_name is not null and name = :location')
    # cursor2.execute(None, {'location': loc})
    try:
        cursor2.execute("select trim(m_name) from locality where region='east' and m_name is not null and name = %s", (loc1,))
    # Fetch  magic brick location
        results = cursor2.fetchall()
        for row in results:
            m_name = row[0]
        print(m_name)
    except:
        print(cursor._last_executed)
        print("Error while fetching data")
    return m_name

# db.close()

