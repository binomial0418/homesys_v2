#-*- coding:utf-8 -*-
import lineTool
import sys

#-------------------------------------------------------------------------------
# sendmsg to line notify
#-------------------------------------------------------------------------------
def SendmsgToLine(msg):
    token = "h8efNbVZZDTpcDKG7ET5Dz9Rk91qS6gvMDcznEab9pT"
    lineTool.lineNotify(token, msg)

if __name__ == '__main__':
   msg = sys.argv[1]
   SendmsgToLine(msg)
