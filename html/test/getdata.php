<?php
$serve = 'mysql:host=10.0.4.15:3306;dbname=duckegg;charset=utf8';
$username = 'duckegg';
$password = '1234';
$query  = "select * from setval where typ ='miop1_pm25_on'";

// PDO連線資料庫若錯誤則會丟擲一個PDOException異常
try{
   $PDO = new PDO($serve,$username,$password);
   $result = $PDO->query($query);
   $data = $result->fetchAll(PDO::FETCH_ASSOC); 
   foreach($data as $val) {  
      echo $val['cmt'];
      }  
   } catch (PDOException $error){
   echo 'connect failed:'.$error->getMessage();
   }

?>

