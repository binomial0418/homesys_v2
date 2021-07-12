# -*- coding: UTF-8 -*-
import mysql.connector
import time
import datetime
import os
import httplib, urllib
import g3
import lineTool

#-------------------------------------------------------------------------------
# sendmsg to line notify
#-------------------------------------------------------------------------------
def SendmsgToLine(msg):
    token = "h8efNbVZZDTpcDKG7ET5Dz9Rk91qS6gvMDcznEab9pT"
    lineTool.lineNotify(token, msg)

#-------------------------------------------------------------------------------
# write data to db
#-------------------------------------------------------------------------------
def WriteToDb(dev,val):
    # 啟動資料庫連線
    db = mysql.connector.connect(
         #host = "duckegg.duckdns.org",
         host = ",
         user = "",
         password = "",
         database = "duckegg"
         )
    cursor=db.cursor()
    # insert statement
    sql = 'INSERT INTO envlog(log_no, dev_typ,val,log_tim) VALUES \
          (%s, "%s", %s, now())' % (0, dev, val)
    try:
       cursor.execute(sql)
       db.commit()
    except:
       # 發生錯誤rollback
       print "insert error"
       db.rollback()
    # 關閉資料庫連線
    db.close()

#-------------------------------------------------------------------------------
# 
#-------------------------------------------------------------------------------
air=g3.g3sensor()
while True:
    try:
        pmdata=air.read("/dev/ttyAMA0")
    except:
        pmdata=[0,0,0,0,0,0]
	continue
    #To Db
    WriteToDb('PMS3003-1-PM1.0',pmdata[3])
    WriteToDb('PMS3003-1-PM2.5',pmdata[4])
    WriteToDb('PMS3003-1-PM10' ,pmdata[5])
    # To Line 
    if pmdata[3] > 500 or pmdata[4] > 500 or pmdata[5] > 500:
       msg="PMS3001-1：PM1.0:["+str(pmdata[3])+ "]  PM2.5:[" + str(pmdata[4])+ "]  PM10:[" + str(pmdata[5]) + "]"
       SendmsgToLine(msg)
    # thingspeak
    params = urllib.urlencode({'field1': pmdata[3], 'field2': pmdata[4], 
                               'field3': pmdata[5], 'key':'MH7UQTEGLVH0RPFE'})
    headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain"}
    try:
        tconn = httplib.HTTPConnection("api.thingspeak.com:80")
        tconn.request("POST", "/update", params, headers)
        response = tconn.getresponse()
        data = response.read()
        tconn.close()
    except:
        continue
    time.sleep(15)


