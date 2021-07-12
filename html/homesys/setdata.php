<form action="index.php" method="post">
<div style="border:12px orange solid;padding:15px;font-size:30px">
<input type ="submit" value="返回設定頁"  style="font-size:40px;">
<br>
</form>

<?php
$servername = "10.0.4.15";
$username = "duckegg";
$password = "1234";
$dbname = "duckegg";

$passwd = $_POST['passwd'];
if ($passwd != '3119'){
    echo "密碼錯誤";
    exit;
}
$typ = $_POST['typ'];
$val = $_POST['val'];
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
$sql = "update setval set val = $val ,rtt = now() where typ = '$typ'";

echo $sql.' <br><br>';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
