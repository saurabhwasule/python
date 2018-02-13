import databaseconfig as cfg
import MySQLdb as my

db = my.connect(host=cfg.mysql['host'],
                user=cfg.mysql['user'],
                passwd=cfg.mysql['passwd'],
                db=cfg.mysql['db']
                )

def insert_property_details(col1_value):

