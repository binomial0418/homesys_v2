# -*- coding: UTF-8 -*-
import mysql.connector
import time
import datetime
import os
import http.client, urllib
import pms3003_p3v
import lineTool
import configparser

sIniFile  = '/home/duckegg/pms3003-g3/set.ini'
sAlarm = "N"
#-------------------------------------------------------------------------------
#獲取CPU溫度
#-------------------------------------------------------------------------------
def get_temp():
   with open("/sys/class/thermal/thermal_zone0/temp", "r") as f:
      temp = int(int(f.read()) / 1000)
      return temp

#-------------------------------------------------------------------------------
# red data from ini
#-------------------------------------------------------------------------------
def GetStsFromIni(sFile,sSection,sTag):
    try:
       rIni = configparser.ConfigParser()
       rIni.read(sFile)
       sMqsts = rIni[sSection][sTag]
    except:
       print('ini file read error')
       sMqsts = 50
    return sMqsts
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
    try:
       # 啟動資料庫連線
       db = mysql.connector.connect(
            #host = "duckegg.duckdns.org",
            host = "",
            user = "",
            password = "",
            database = ""
            )
       cursor=db.cursor()
    except:
       return

    # insert statement
    sql = 'INSERT INTO envlog(log_no, dev_typ,val,log_tim) VALUES \
          (%s, "%s", %s, now())' % (0, dev, val)
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
# 
#-------------------------------------------------------------------------------
if __name__ == '__main__':
   SendmsgToLine('pms3003偵測程式啟動(浴室)')
   print('pms3003偵測程式啟動(浴室)')
   iDelay = int(GetStsFromIni(sIniFile,'data','delay_val'))

   # 讀取感應器數值
   air=pms3003_p3v.g3sensor()
   mvCnt = 0
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
       alarm_val = int(GetStsFromIni(sIniFile,'data','alarm_val'))
       if pmdata[3] > alarm_val or pmdata[4] > alarm_val or pmdata[5] > alarm_val:
          msg = 'PMS3003-1: PM1.0[%s] PM2.5[%s] PM10[%s]' \
                %(pmdata[3],pmdata[4],pmdata[5])
          print(msg)
          if sAlarm == "N":
             SendmsgToLine(msg)
             sAlarm= "Y"
       else:
          if sAlarm == "Y":
             msg = 'PMS3003-1:alarm clear'
             print(msg)
             SendmsgToLine(msg)
          sAlarm = "N"

       # CPU temputer 
       temp = get_temp()
       print(temp)
       #thingspeak
       params = urllib.parse.urlencode({'field1': pmdata[3], 'field2': pmdata[4], 'field3': pmdata[5],'field4': temp, 'key':'MH7UQTEGLVH0RPFE'})
       headers = {"Content-type": "application/x-www-form-urlencoded","Accept": "text/plain"}
       try:
           tconn = http.client.HTTPConnection("api.thingspeak.com:80")
           tconn.request("POST", "/update", params, headers)
           response = tconn.getresponse()
           data = response.read()
           tconn.close()
       except:
           continue
       time.sleep(iDelay)
