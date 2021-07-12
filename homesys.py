#-*- coding:utf-8 -*-
import lineTool
import time
import os
import sys
import configparser
import mysql.connector
from miio.chuangmi_plug import ChuangmiPlug

sIniFile  = '/var/services/homes/duckegg/code/homesys/sensorsts.ini'
#-------------------------------------------------------------------------------
# write data to db
#-------------------------------------------------------------------------------
def WriteToDb(dev,val):
   res =  1
   try:
       # 啟動資料庫連線
       db = mysql.connector.connect(
            host = "",
            user = "",
            password = "",
            database = "duckegg"
            )
       cursor=db.cursor()
       # insert statement
       sql = 'INSERT INTO envlog(log_no, dev_typ,val,log_tim) VALUES \
             (%s, "%s", %s, now())' % (0, dev, val)
       print(sql)

       cursor.execute(sql)
       db.commit()
   except:
       # 發生錯誤rollback
       print('insert error')
       db.rollback()
       res =  0

   # 關閉資料庫連線
   db.close()

   return res
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
       database = ""
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
    #[{"pm25": 40, "pm10": 40, "time": "12.03.2020 21:23:32"}]
    localtime = time.asctime( time.localtime(time.time()))
    localtime = time.strftime("%m-%d-%Y %H:%M:%S")
    if dev =='PMS3003-1':
       pm2_5 = GetDevVal("PMS3003-1-PM2.5");
       pm10  = GetDevVal("PMS3003-1-PM10");
       filenam = "/var/services/homes/duckegg/code/homesys/aqi1.json"

    str = '[{"pm25": %s, "pm10": %s, "time": "%s"}]'% (pm2_5, pm10, localtime)

    f = open(filenam, "w")
    # 寫入資料
    f.write(str)
    # 關閉檔案
    f.close()
#-------------------------------------------------------------------------------
# write data to db
#-------------------------------------------------------------------------------
def WriteToDb_IR(dev,val):
   res =  1
   try:
       # 啟動資料庫連線
       db = mysql.connector.connect(
            host = "",
            user = "",
            password = "",
            database = "duckegg"
            )
       cursor=db.cursor()
       # insert statement
       sql = 'INSERT INTO ircmd(cmd_no, ir_no,cmd,tsc,rtt) VALUES \
             (%s, "%s", "%s", "%s", now())' % (0, dev, val,"1")
       print(sql)

       cursor.execute(sql)
       db.commit()
   except:
       # 發生錯誤rollback
       print('insert error')
       db.rollback()
       res =  0
   # 關閉資料庫連線
   db.close()

   return res
#-------------------------------------------------------------------------------
# 利用小米插座自動啟閉抽風
#-------------------------------------------------------------------------------
def SetMiPlugSts(sts,ip,token,on_msg,off_msg,dev_typ):
    #print(fan_typ)
    try:
       d = ChuangmiPlug(ip=ip, token=token)
       fan_typ = dev_typ+'_power'
       x=d.status() # 设备的状态
       #print(x.temperature)
       print(x)
       #x=d.info()
       #print(x.mac)
       if sts == 'OFF' and x.power == True:
          x=d.off() #power off
          if x == ['ok']:
             print(off_msg)
             SendmsgToLine(off_msg)
             if WriteToDb(fan_typ,0) == 0:
                time.sleep(1)
                WriteToDb(fan_typ,0)
             if dev_typ == 'fan_1':
                if WriteToDb_IR('bath1','StandBy') == 0:
                   time.sleep(1)
                   WriteToDb_IR('bath1','StandBy')

       if sts == 'ON' and x.power == False:
          x=d.on()  #power on
          if x == ['ok']:
             print(on_msg)
             SendmsgToLine(on_msg)
             if WriteToDb(fan_typ,1) ==0:
                time.sleep(1)
                WriteToDb(fan_typ,1)
             if dev_typ == 'fan_1':
                if WriteToDb_IR('bath1','AirH') == 0:
                   time.sleep(1)
                   WriteToDb_IR('bath1','AirH')
    except:
       print('miio device error')
#-------------------------------------------------------------------------------
# 讀取設定檔數值
#-------------------------------------------------------------------------------
def GetConfgDbVal(typ,defval):
    try:
       db = mysql.connector.connect(
       host = "",
       user = "",
       password = "",
       database = "duckegg"
       )
       cursor=db.cursor()
    except:
       return defval

    sql = 'select val  from setval where typ = "%s" ' %(typ)
    #print(sql)
    cursor.execute(sql)
    res=cursor.fetchall()
    for r in res:
        s = r[0]
    s = r[0]
    return s
#-------------------------------------------------------------------------------
# 讀取設備在資料中最新一筆數據
#-------------------------------------------------------------------------------
def GetLastDbVal(dev):
    s = 0;
    alarm_val = int(GetStsFromIni(sIniFile,'val','alarm_val'))
    if dev =='PMS3003-1-PM2.5':
       fan_on     = int(GetConfgDbVal('miop1_pm25_on',200))
       fan_off    = int(GetConfgDbVal('miop1_pm25_off',100))
       miop_ip    = GetConfgDbVal('miop1_ip',200) #小米插座IP
       miop_token = GetConfgDbVal('miop1_token',200) # 小米插座token
       off_msg    = GetConfgDbVal('miop1_off_msg',200)
       on_msg     = GetConfgDbVal('miop1_on_msg',200)
       dev_typ    = 'fan_1'

    #if dev =='PMS3003-2-PM2_5':
    if dev =='PMS5003t-1-PM2_5':
       fan_on     = int(GetConfgDbVal('miop2_pm25_on',100))
       fan_off    = int(GetConfgDbVal('miop2_pm25_off',40))
       miop_ip    = GetConfgDbVal('miop2_ip',200) #小米插座IP
       miop_token = GetConfgDbVal('miop2_token',200) # 小米插座token
       on_msg     = GetConfgDbVal('miop2_on_msg',200)
       off_msg    = GetConfgDbVal('miop2_off_msg',200)
       dev_typ    = 'fan_2'

    #取設備值
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
    #print(sql)
    cursor.execute(sql)
    #res=cursor.fetchmany(999)
    res=cursor.fetchall()
    for r in res:
        s = r[2]
    #print(s)
    db.commit()
    db.close()
    cursor.close
    if s > fan_on:
       SetMiPlugSts('ON',miop_ip,miop_token,on_msg,off_msg,dev_typ)
    if s < fan_off:
       SetMiPlugSts('OFF',miop_ip,miop_token,on_msg,off_msg,dev_typ)
#-------------------------------------------------------------------------------
# get local time str
#-------------------------------------------------------------------------------
def GetLocalTimeStr():
    localtime = time.asctime( time.localtime(time.time()))
    localtime = time.strftime("%Y-%m-%d %H:%M:%S")
    return localtime
#-------------------------------------------------------------------------------
# sendmsg to line notify
#-------------------------------------------------------------------------------
def SendmsgToLine(msg):
    token = "h8efNbVZZDTpcDKG7ET5Dz9Rk91qS6gvMDcznEab9pT"
    msg = msg + '_From DS220+'
    lineTool.lineNotify(token, msg)
#-------------------------------------------------------------------------------
# read data from ini
#-------------------------------------------------------------------------------
def GetStsFromIni(sFile,sSection,sTag):
    sMqsts = 40
    try:
       rIni =snfigparser.ConfigParser()
       rIni.read(sFile)
       sMqsts = rIni[sSection][sTag]
    except:
       sMqsts = 40
    return sMqsts
#-------------------------------------------------------------------------------
# write data to ini
#-------------------------------------------------------------------------------
def SetStsToIni(sFile,sSection,sTag,sVal):
    cf = configparser.ConfigParser()
    cf.read(sFile)
    cf.set(sSection,sTag,sVal)
    cf.write(open(sFile,'w'))
#-------------------------------------------------------------------------------
# Main function :Get state
#-------------------------------------------------------------------------------
if __name__ == '__main__':
   print('環境監控程式啟動_DS220+')
   try:
    SendmsgToLine('環境監控程式啟動')
    while True:
       GetLastDbVal('PMS3003-1-PM2.5')
       #WriteJsonToHomeKitAir('PMS3003-1')
       #GetLastDbVal('PMS3003-2-PM2_5')
       #GetLastDbVal('PMS5003t-1-PM2_5')
       time.sleep(30)
   except KeyboardInterrupt:
      print('close application')
