#-*- coding:utf-8 -*-
import time
import os
import sys
import mysql.connector

#------------------------------------------------------------------------------
# 取設備值
#------------------------------------------------------------------------------
def GetDevVal(dev):
    s = ""
    try:
       db = mysql.connector.connect(
       host = "",
       user = "",
       password = "",
       database = "duckegg"
       )
       cursor=db.cursor()
    except:
       return

    sql = 'select * from envlog where dev_typ = "%s" and log_tim = \
          (select max(log_tim) from envlog where dev_typ = "%s")'%(dev,dev)
    cursor.execute(sql)
    res=cursor.fetchall()
    for r in res:
        s = r[2]
    db.commit()
    db.close()
    cursor.close
    return s
#-------------------------------------------------------------------------------
# write data to home kit file 寫入檔案給homekit元件用
#-------------------------------------------------------------------------------
def WriteJsonToHomeKitAir(dev):
    pm2_5    = 0
    pm10     = 0
    temp     = 0
    humi     = 0
    filenam  = ""
    rfilenam = ""
    #[{"pm25": 40, "pm10": 40, "time": "12.03.2020 21:23:32"}]
    localtime = time.asctime( time.localtime(time.time()))
    localtime = time.strftime("%m.%d.%Y %H:%M:%S")
    id = os.popen("sudo docker ps -aqf 'name=homebridge'").read()
    if dev =='PMS3003-1':
       pm2_5    = GetDevVal("PMS3003-1-PM2.5");
       pm10     = GetDevVal("PMS3003-1-PM10");
       filenam  = "/var/services/homes/duckegg/code/homesys/aqi1.json"
       rfilenam = "aqi1.json"
       str      = '[{"pm25": %s, "pm10": %s, "time": "%s"}]'% (pm2_5, pm10, localtime)
    if dev =='PMS5003T-1':
       pm2_5    = GetDevVal("PMS5003T-1-PM2_5");
       pm10     = GetDevVal("PMS5003T-1-PM2_5");
       filenam  = "/var/services/homes/duckegg/code/homesys/aqi2.json"
       rfilenam = "aqi2.json"
       str      = '[{"pm25": %s, "pm10": %s, "time": "%s"}]'% (pm2_5, pm10, localtime)
    if dev =='PMS5003T-1-Temp':
       temp     = GetDevVal("PMS5003T-1-Temp");
       filenam  = "/var/services/homes/duckegg/code/homesys/bath_temp.txt"
       rfilenam = "bath_temp.txt"
       str      = '%s'%(temp)
    if dev =='PMS5003T-1-Humi':
       humi     = GetDevVal("PMS5003T-1-Humi");
       filenam  = "/var/services/homes/duckegg/code/homesys/bath_humi.txt"
       rfilenam = "bath_humi.txt"
       str      = '%s'%(humi)


    f = open(filenam, "w")
    # 寫入資料
    f.write(str)
    # 關閉檔案
    f.close()
    cmd = 'sudo docker cp %s %s:%s' % (filenam,id.strip(),rfilenam)
    print(cmd)
    res = os.system(cmd)
#-------------------------------------------------------------------------------
# Main function :Get state
#-------------------------------------------------------------------------------
if __name__ == '__main__':
   try:
    while True:
       WriteJsonToHomeKitAir('PMS3003-1')
       WriteJsonToHomeKitAir('PMS5003T-1')
       WriteJsonToHomeKitAir('PMS5003T-1-Temp')
       WriteJsonToHomeKitAir('PMS5003T-1-Humi')
       time.sleep(30)
   except KeyboardInterrupt:
      print('close application')
