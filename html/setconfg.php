<?php 
//利用WS異動設定值到MySql
//呼叫範例：http://duckegg.duckdns.org:8088/setconfg.php?typ=miop1_pm25_on&val=30

DEFINE ('DBServer', '10.0.4.15'); 
DEFINE ('DBName', '');
DEFINE ('DBUser', ''); 
DEFINE ('DBPw', '');  

$conDb = mysqli_connect(DBServer,DBUser,DBPw);
if (!$conDb) {
    die("Can not connect to DB: " . mysqli_error($conDb));
    exit();
}

$selectDb = mysqli_select_db($conDb, DBName);
if (!$selectDb) {
    die("Database selection failed: " . mysqli_error($conDb));
    exit(); 
}


$typ = mysqli_real_escape_string($conDb, $_GET['typ']);
$val = mysqli_real_escape_string($conDb,$_GET['val']);
$query = "UPDATE setval set val = $val ,rtt = now() where typ = '$typ'";
$result = mysqli_query($conDb, $query) or trigger_error("query error " . mysqli_error($conDb)); 

mysqli_close($conDb); 

?>
