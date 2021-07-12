#!/usr/bin/env python3
# -*- coding: UTF-8 -*-
import urllib, json
import mysql.connector
from urllib import request
#-------------------------------------------------------------------------------
# 抓取衛生署opendata資料
#-------------------------------------------------------------------------------
def getdata():
   k     = 0
   pm2_5 = 0
   s     = 0

   url = "http://opendata2.epa.gov.tw/AQI.json"
   response = request.urlopen(url)
   content = response.read()
   content = content.decode('utf-8')
   data_list = json.loads(content)
   #------------------------------------------------------
   # County:縣市 SiteName:站點 想知道所有屬性:print(data_list[i])
   #------------------------------------------------------
   for i in range(0,81):
     if (data_list[i]["SiteName"] == "沙鹿") or \
        (data_list[i]["SiteName"] == "線西") or \
        (data_list[i]["SiteName"] == "彰化") :
       try:
          pm2_5 = int(data_list[i]["PM2.5"])
       except:
          pm2_5 = 0
       if pm2_5 > 0:
          s = s + pm2_5
          k=k+1
   if k == 0:
     print("查無資料")
   else:
     val = round(s/k)
     WriteToDb('open_data_avg_pm2_5',val)
     print("共有" + str(k) + "個測站，平均 PM 2.5 是 " + str(round(s/k)))
#-------------------------------------------------------------------------------
# write data to db
#-------------------------------------------------------------------------------
def WriteToDb(dev,val):
    # 啟動資料庫連線
    db = mysql.connector.connect(
         host = "",
         user = "",
         password = "",
         database = "duckegg"
         )
    cursor=db.cursor()
    # insert statement
    if val == "Y":
       data = 1
    if val == "N":
       data = 0
    sql = 'update setval set val = %s, rtt = now()  where typ = "%s"' % (val,dev)
    print(sql)
    try:
       cursor.execute(sql)
       db.commit()
    except:
       # 發生錯誤rollback
       print('insert error')
       db.rollback()

    # 關閉資料庫連線
    db.close()
#-------------------------------------------------------------------------------
# Main function :Get state
#-------------------------------------------------------------------------------
if __name__ == '__main__':
   getdata()
