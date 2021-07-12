<?php 
//利用WS
//參數：ir_no
//呼叫範例：http://duckegg.duckdns.org:8088/getircmd.php?ir_no=bath1
$typ      = $_GET['ir_no'];
$serve    = 'mysql:host=10.0.4.15:3306;dbname=duckegg;charset=utf8';
$username = 'duckegg';
$password = '1234';
// PDO連線資料庫若錯誤則會丟擲一個PDOException異常

$PDO = new PDO($serve,$username,$password);
$query  = "update ircmd set tsc = '1' ,rtt = now() where cmd_no = 6";
$stmt = $PDO->prepare($query);

// execute the query
$stmt->execute();
$PDO = null;
?>