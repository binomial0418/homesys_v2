<?php
$serve    = 'mysql:host=10.0.4.15:3306;dbname=duckegg;charset=utf8';
$username = 'duckegg';
$password = '1234';
$typ      = $_GET['typ'];
$val      = $_GET['val'];
$passwd   = $_GET['passwd'];

if ($passwd != '3119'){
    echo "密碼錯誤";
    exit;
}

if ($val == '' or $val <= 0){
    echo "數值不可為:".$val."，請重新輸入";
    exit;
   }
if ($typ == '啟動換氣系統1下限值') {
   $typ = 'miop1_pm25_on';
   }
if ($typ == '關閉換氣系統1上限值') {
   $typ = 'miop1_pm25_off';
   }
if ($typ == '啟動換氣系統2下限值') {
   $typ = 'miop2_pm25_on';
   }
if ($typ == '關閉換氣系統2上限值') {
   $typ = 'miop2_pm25_off';
   }

try {
    $PDO = new PDO($serve,$username,$password);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "update setval set val = $val ,rtt = now() where typ = '$typ'";
    echo $sql;
    // Prepare statement
    $stmt = $PDO->prepare($sql);

    // execute the query
    $stmt->execute();

    // echo a message to say the UPDATE succeeded
    echo '<br>'.$stmt->rowCount() . " records UPDATED successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$PDO = null;
?>



