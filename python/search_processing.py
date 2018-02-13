import database_insert as db_insert
import sys
import databaseconfig as cfg
import MySQLdb as my
db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )
# id = sys.argv[1]
# loc = sys.argv[2]
# created_timestamp = sys.argv[3]
# posted_since = sys.argv[4]
def insert_search_info(id,loc,now,posted_since):
    db_insert.search_details(id,loc,now,posted_since)

def is_recent(loc,posted_since):
    cursor2 = db.cursor()
    try:
        sql="SELECT max(p.created_timestamp) FROM property_detail p inner join search_detail s on(p.created_timestamp=s.created_timestamp) WHERE p.location = %s AND s.posted_since_indays >= %s"
        cursor2.execute(sql, (loc,posted_since))
        # Fetch all the rows in a list of lists.
        latest_date = cursor2.fetchone()
    except:
        print(cursor2._last_executed)
        print("Error while inserting data")
    # print(latest_date)
    return latest_date




