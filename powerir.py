#-*- coding:utf-8 -*-
import time
import mysql.connector
import socket
import bluetooth
from miio.chuangmi_plug import ChuangmiPlug

#------------------------------------------------------------------------------
# 取設定值
#------------------------------------------------------------------------------
def GetInfVal(typ):
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

    sql = 'select val from setval where typ = "%s"'%(typ)
    cursor.execute(sql)
    res=cursor.fetchall()
    for r in res:
        s = r[0]
    db.commit()
    db.close()
    cursor.close
    return s
#-------------------------------------------------------------------------------
# 取得命令
#-------------------------------------------------------------------------------
def GetIrCmd(typ):
    s = ""
    try:
       db = mysql.connector.connect(
       host = "10.0.4.123",
       user = "duckegg",
       password = "",
       database = ""
       )
       cursor=db.cursor()
    except:
       return

    sql = 'select cmd  from ircmd where ir_no = "%s" \
           and tsc = "1" and eff_tim <= now()' %(typ)
    cursor.execute(sql)
    res=cursor.fetchall()
    for r in res:
        s = r[0]
    return s
#-------------------------------------------------------------------------------
# 命令
#-------------------------------------------------------------------------------
def updatecmdsts(dev):
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

    sql  = 'update ircmd set tsc = "6" , rtt = now() where ir_no = "%s"\
            and tsc  = "1" and eff_tim <= now() ' % (dev)
    cursor.execute(sql)
    db.commit()
    db.close()
    cursor.close
#-------------------------------------------------------------------------------
# 轉換紅外線指令
#-------------------------------------------------------------------------------
def chg_ir_cod_to_hex(s):
    if s == 'StandBy':
       return b'\xe3\x01'
    if s == 'AirH':
       return b'\xe3\x02'
    return ''
#-------------------------------------------------------------------------------
# 發送命令至藍芽設備
#-------------------------------------------------------------------------------
def IrSend(dev):
    cmd     = GetIrCmd(dev)
    cmd     = chg_ir_cod_to_hex(cmd)
    mpip    = ''
    mptoken = ''
    recv    = ''

    if dev == 'bath1':
       bd_addr = GetInfVal('Ble-L1-MAC')
       mpip    = GetInfVal('miop2_ip')
       mptoken = GetInfVal('miop2_token')
    if dev == 'bath2':
       bd_addr = GetInfVal('Ble-L2-MAC')

    #bd_addr = "19:12:24:19:10:40" # server 端的 addr
    if len(bd_addr) == 0 or len(cmd) == 0:
       return

    d = ChuangmiPlug(ip=mpip, token=mptoken)
    x=d.status()
    x=d.on() #power off
    port = 1
    link = 0
    time = 0
    while time < 10 and link == 0:
        time = time + 1
        try:
           sock=bluetooth.BluetoothSocket( bluetooth.RFCOMM )
           sock.connect((bd_addr, port))
           link = 1
           break
        except:
          print("link bluetooth error,try again" + str(time))
    print(cmd)
    print(bd_addr)
    if link == 1 and len(cmd) > 1:
       sock.send(cmd)
       recv = sock.recv(8)
       print(recv)
       if recv != b'\xe3':
          print('ir send error')
       else:
         print('ir send ok')
         sock.close()
         updatecmdsts(dev)
         x=d.off() #power off
#-------------------------------------------------------------------------------
# Main function :Get state
#-------------------------------------------------------------------------------
if __name__ == '__main__':
   try:
    while True:
       s = IrSend('bath1')
       s = IrSend('bath2')
       time.sleep(10)
   except KeyboardInterrupt:
      print('close application')
