#!/bin/bash
PATH=/sbin:/bin:/usr/sbin:/usr/bin:/usr/syno/sbin:/usr/syno/bin:/usr/local/sbin:/usr/local/bin
blesensor=`ps -ef|grep 'blesensor'|grep 'python'|grep -v 'grep'|wc -l`
if [ $blesensor -eq 0 ];then
  #sudo nohup python3 /home/duckegg/blesensor.py &
  echo 'run blesensor'
fi

homesys=`ps -ef|grep 'docker'|grep 'homesys'|grep -v 'grep'|wc -l`
if [ $homesys -eq 0 ];then
  sudo /var/packages/Docker/target/usr/bin/docker run -u 0 -i --rm --name python3.7-miio -v "/var/services/homes/duckegg/code/homesys/":/tmp p37 python /tmp/homesys.py
#  sudo nohup python3 /home/duckegg/homesys.py >/dev/null 2>&1 &
  echo 'run homesys'
fi

tungwatch=`ps -ef|grep 'tungwatch'|grep 'python'|grep -v 'grep'|wc -l`
if [ $tungwatch -eq 0 ];then
  nohup python3 /var/services/homes/duckegg/code/homesys/tungwatch.py >/dev/null 2>&1 &
  echo 'run tungwatch'
fi

homekitdata=`ps -ef|grep 'homekitdata'|grep 'python'|grep -v 'grep'|wc -l`
if [ $homekitdata -eq 0 ];then
  sudo python3 /var/services/homes/duckegg/code/homesys/homekitdata.py >/dev/null 2>&1 &
  echo 'run homekitdata'
fi
