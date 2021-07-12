<?php
$serve = 'mysql:host=10.0.4.15:3306;dbname=duckegg;charset=utf8';
$username = 'duckegg';
$password = '1234';
$query  = "select * from setval where typ in ('miop1_pm25_on','miop1_pm25_off','miop2_pm25_on','miop2_pm25_off') order by ser_no asc";
//echo "目前設定值：<br>";
echo '<div style="background-color:pink;padding:10px;font-size:36px">';
// PDO連線資料庫若錯誤則會丟擲一個PDOException異常
try{
   $PDO = new PDO($serve,$username,$password);
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC);
   foreach($data as $val) {
      if ($val['typ'] == 'miop1_pm25_on'){
        echo "啟動換氣系統1下限值=".$val['val'].'<br>';
        }
      if ($val['typ'] == 'miop1_pm25_off'){
        echo "關閉換氣系統1上限值=".$val['val'].'<br>';
        }
      if ($val['typ'] == 'miop2_pm25_on'){
        echo "啟動換氣系統2下限值=".$val['val'].'<br>';
        }
      if ($val['typ'] == 'miop2_pm25_off'){
        echo "關閉換氣系統2上限值=".$val['val'].'<br>';
        }
      }
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }
echo '</div>';
sleep(1);
?>

