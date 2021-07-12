<?php
$serve = 'mysql:host=10.0.4.15:3306;dbname=duckegg;charset=utf8';
$username = 'duckegg';
$password = '1234';
$query  = 'select * from envlog where dev_typ = "PMS3003-1-PM2.5" order by log_tim desc limit 1 ';
//echo '空氣品質紀錄:<br>';
echo '<div style="background-color:pink;padding:10px;font-size:28px">';
echo "最近紀錄 <br>";
echo '<table style="width: 1000px;" border="1"><tbody><tr>';
echo '<td><font size="6">裝置</font></td><td><font size="6">感應器</font></td><td><font size="6">數值</font></td>'.
     '<td><font size="6">更新時間</font></td><td><font size="6">備註</font></td></tr>';
// PDO連線資料庫若錯誤則會丟擲一個PDOException異常
try{
   $PDO = new PDO($serve,$username,$password);
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      //echo "管道間PMS3003-1 PM2.5：".$val['val'].' µg/m³ (@'.$val['log_tim'].')<br>';
      echo '<tr><td><font size="6">PMS3003+樹莓</td><td><font size="6">PM2.5</td><td><font size="6">'.
           $val['val'].' µg/m³ </font></td><td><font size="6">'.$val['log_tim'].'</font></td><td><font size="6">管道間</td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
try{
   $query  = 'select * from envlog where dev_typ = "PMS3003-2-PM2_5" order by log_tim desc limit 1 ';
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      //echo "偵測器PMS3003-1 PM2.5：".$val['val'].' µg/m³ (@'.$val['log_tim'].')<br>';
      echo '<tr><td><font size="6">PMS3003+NodeMCU</td><td><font size="6">PM2.5</td><td><font size="6">'.
           $val['val'].' µg/m³ </font></td><td><font size="6">'.$val['log_tim'].'</font></td><td><font size="6"></td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
try{
   $query  = 'select * from envlog where dev_typ = "PMS5003t-1-PM2_5" order by log_tim desc limit 1 ';
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      //echo "偵測器PMS5003-1 PM2.5：".$val['val'].' µg/m³ (@'.$val['log_tim'].')<br>';
      echo '<tr><td><font size="6">PMS5003T+NodeMCU</td><td><font size="6">PM2.5</td><td><font size="6">'.
           $val['val'].' µg/m³ </font></td><td><font size="6">'.$val['log_tim'].'</font></td><td><font size="6">1號</td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
try{
   $query  = 'select * from envlog where dev_typ = "PMS5003t-1-Temp" order by log_tim desc limit 1 ';
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
//      echo "偵測器PMS5003-1 溫度：".$val['val'].' ˚C    (@'.$val['log_tim'].')<br>';
      echo '<tr><td><font size="6">PMS5003T+NodeMCU</td><td><font size="6">溫度</td><td><font size="6">'.
           $val['val'].' ˚C</font></td><td><font size="6">'.$val['log_tim'].'</font></td><td><font size="6">1號</td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
try{
   $query  = 'select * from envlog where dev_typ = "PMS5003t-1-Humi" order by log_tim desc limit 1 ';
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      //echo "偵測器PMS5003-1 濕度：".$val['val'].' %     (@'.$val['log_tim'].')<br>';
      echo '<tr><td><font size="6">PMS5003T+NodeMCU</td><td><font size="6">濕度</td><td><font size="6">'.
           $val['val'].' %</font></td><td><font size="6">'.$val['log_tim'].'</font></td><td><font size="6">1號</td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
try{
   $query  = 'select * from setval where typ = "open_data_avg_pm2_5"';
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      //echo "周圍衛生署監測站：".$val['val'].' µg/m³ (@'.$val['rtt'].')<br>';
      echo '<tr><td><font size="6">周圍衛生署監測站</td><td><font size="6">PM2.5</td><td><font size="6">'.
           $val['val'].' µg/m³ </font></td><td><font size="6">'.$val['rtt'].'</font></td><td><font size="6"></td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '</tr>';
echo '</tbody></table>';
echo '<br>';

echo '</div>';
echo '<br>';
echo '<a href="https://thingspeak.com/channels/1026081"  target="_blank">管道間(樹莓+PMS3003)</a>';
//近期超標紀錄
//先取標準值
try{
   $query  = "select * from setval where typ ='miop1_pm25_on'";
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      $stand = $val['val'];
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '<div style="background-color:pink;padding:10px;">';
echo '近期超標紀錄';
echo '<table style="width: 800px;" border="3"><tbody><tr>';
echo '<td><font size="10">時間</font></td><td><font size="10">數值(µg/m³)</font></td></tr>';
try{
   $query = "SELECT * FROM envlog where dev_typ = 'PMS3003-1-PM2.5' and val > $stand ".
            "order by log_tim DESC limit 20";
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
       echo '<tr><td><font size="10">'.$val['log_tim'].'</font></td><td><font size="10">'.
             $val['val'].'</font></td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '</tr>';
echo '</tbody></table>';
echo '<br>';


//近期通風系統啟閉紀錄
echo '近期通風系統啟閉紀錄<br>';
echo '<table style="width: 800px;" border="3"><tbody><tr>';
echo '<td><font size="10">時間</font></td><td><font size="10">狀態</font></td></tr>';
try{
   $query = "SELECT * FROM envlog where dev_typ = 'fan_1_power' ".
            " order by log_tim DESC limit 20";
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      if ($val['val']==1){
            $sts = "啟動";
            echo '<tr><td><font size="10">'.$val['log_tim'].'</font></td><td><font size="10">'.$sts.'</font></td></tr>';
         } else{
            $sts = "關閉";
            echo '<tr><td style="background-color:#BBFFBB"><font size="10">'.
                 $val['log_tim'].'</font></td><td td style="background-color:#BBFFBB">'.
                 '<font size="10">'.$sts.'</font></td></tr>';
         }
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '</tr>';
echo '</tbody></table>';
echo '<br>';
echo 'PM1';
echo '<br>';
echo '<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/widgets/167285"></iframe>';
echo '<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/charts/1?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM1&type=spline&yaxismax=100"></iframe>';
echo '<br>';
echo 'PM2.5';
echo '<br>';
echo '<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/widgets/166622"></iframe>';
echo '<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/charts/2?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM2.5&type=spline&yaxismax=100"></iframe>';
echo '<br>';
echo 'PM10';
echo '<br>';
echo '<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/widgets/166623"></iframe>';
echo '<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/charts/3?bgcolor=%23ffffff&color=%23d62020&days=+3&dynamic=true&timescale=10&title=PM10&type=spline&yaxismax=100"></iframe>';
echo '<br>';
echo '</div>';




echo '<br>';
echo '<a href="https://thingspeak.com/channels/1031670"  target="_blank">PMS3003 + NodeMCU</a>';
//近期超標紀錄
//先取標準值
try{
   $query  = "select * from setval where typ ='miop2_pm25_on'";
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      $stand = $val['val'];
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '<div style="background-color:pink;padding:10px;">';
echo '近期超標紀錄';
echo '<table style="width: 800px;" border="3"><tbody><tr>';
echo '<td><font size="10">時間</font></td><td><font size="10">數值(µg/m³)</font></td></tr>';
try{
   $query = "SELECT * FROM envlog where dev_typ = 'PMS3003-2-PM2_5' and val > $stand ".
            "order by log_tim DESC limit 20";
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
       echo '<tr><td><font size="10">'.$val['log_tim'].'</font></td><td><font size="10">'.
            $val['val'].'</font></td></tr>';
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '</tr>';
echo '</tbody></table>';
echo '<br>';


//近期通風系統啟閉紀錄
echo '近期通風系統啟閉紀錄<br>';
echo '<table style="width: 800px;" border="3"><tbody><tr>';
echo '<td><font size="10">時間</font></td><td><font size="10">狀態</font></td></tr>';
try{
   $query = "SELECT * FROM envlog where dev_typ = 'fan_2_power' ".
            " order by log_tim DESC limit 20";
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      if ($val['val']==1){
            $sts = "啟動";
            echo '<tr><td><font size="10">'.$val['log_tim'].'</font></td><td><font size="10">'.$sts.'</font></td></tr>';
         } else{
            $sts = "關閉";
            echo '<tr><td style="background-color:#BBFFBB"><font size="10">'.
                 $val['log_tim'].'</font></td><td td style="background-color:#BBFFBB">'.
                 '<font size="10">'.$sts.'</font></td></tr>';
         }
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '</tr>';
echo '</tbody></table>';
echo '<br>';
echo 'PM1';
echo '<br>';
echo '<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/widgets/167899"></iframe>';
echo '<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/charts/1?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM1&type=spline"></iframe>';
echo '<br>';
echo 'PM2.5';
echo '<br>';
echo '<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/widgets/167900"></iframe>';
echo '<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/charts/2?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM2.5&type=spline"></iframe>';
echo '<br>';
echo 'PM10';
echo '<br>';
echo '<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/widgets/167901"></iframe>';
echo '<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/charts/2?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM2.5&type=spline"></iframe>';



echo '<br>';
echo '</div>';
sleep(1);
?>
