<?php 
//利用WS新增感應器數據到MySql
//參數：devtyp對應欄位envlog.dev_typ,val對應欄位envlog.val
//呼叫範例：http://duckegg.duckdns.org:8088/getenvlog.php?devtyp=PMS3003-3-PM2.5&val=30

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


$devtyp = mysqli_real_escape_string($conDb, $_GET['devtyp']);
$val = mysqli_real_escape_string($conDb,$_GET['val']);
$query = "INSERT INTO duckegg.envlog (log_no, dev_typ, val, log_tim) VALUES (0,'$devtyp','$val',now() )";

$result = mysqli_query($conDb, $query) or trigger_error("query error " . mysqli_error($conDb)); 

mysqli_close($conDb); 

?>
