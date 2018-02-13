#!/usr/bin/python
import sys, json

try:
    data = json.loads(sys.argv[1])
    print (json.dumps({'status': 'Yes!'}))
except Exception as e:
    print (str(e))