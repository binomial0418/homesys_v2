<html><body>
<!-- 取目前設定值 -->
<?php
$mysqli = new mysqli("10.0.4.15","duckegg","1234","duckegg");     //實例化mysqli
$query  = "select * from setval where typ ='miop1_pm25_on'";
$result = $mysqli->query($query);
if ($result) {
    if($result->num_rows>0){                                 //判斷結果集中行的數目是否大於0
        while($row = $result->fetch_array() ){              //循環輸出結果集中的記錄
            $miop1_on = $row[2];
        }
    }
}else {
    $miop1_on = "查詢失敗";
}
$result->free();
$mysqli->close();
?>

<!-- 取目前設定值 -->
<?php
$mysqli = new mysqli("10.0.4.15","duckegg","1234","duckegg");     //實例化mysqli
$query  = "select * from setval where typ ='miop1_pm25_off'";
$result = $mysqli->query($query);
if ($result) {
    if($result->num_rows>0){                                 //判斷結果集中行的數目是否大於0
        while($row = $result->fetch_array() ){              //循環輸出結果集中的記錄
            $miop1_off = $row[2];
        }
    }
}else {
    $miop1_off = "查詢失敗";
}
$result->free();
$mysqli->close();
?>

<!-- 取最近一筆資料 -->
<?php
$mysqli = new mysqli("10.0.4.15","duckegg","1234","duckegg");     //實例化mysqli
$query  = 'select * from envlog where dev_typ = "PMS3003-1-PM2.5" '.
          'order by log_tim desc limit 1 ';
$result = $mysqli->query($query);
if ($result) {
    if($result->num_rows>0){                          
        while($row = $result->fetch_array() ){     
            //$pms3003_1_last = $row[3].' '.$row[2];
            $pms3003_1_last = $row[2].' µg/m³(@'.$row[3].')';
        }
    }
}else {
    $pms3003_1_last =  "查詢失敗";
}

$mysqli = new mysqli("10.0.4.15","duckegg","1234","duckegg");     //實例化mysqli
$query  = 'select * from envlog where dev_typ = "PMS3003-2-PM2_5" '.
          'order by log_tim desc limit 1 ';
$result = $mysqli->query($query);
if ($result) {
    if($result->num_rows>0){                          
        while($row = $result->fetch_array() ){     
            $pms3003_2_last = $row[2].' µg/m³(@'.$row[3].')';
        }
    }
}else {
    $pms3003_2_last =  "查詢失敗";
}
$result->free();
$mysqli->close();
?>

<!-- 取db中opendata的pm2.5平均 -->
<?php
$mysqli = new mysqli("10.0.4.15","duckegg","1234","duckegg");     //實例化mysqli
$query  = "select * from setval where typ ='open_data_avg_pm2_5'";
$result = $mysqli->query($query);
if ($result) {
    if($result->num_rows>0){ 
        while($row = $result->fetch_array() ){
            $open_data_avg_pm2_5 = $row[2]. 'µg/m³ (@'.$row[4].')';
        }
    }
}else {
    $miop1_off = "查詢失敗";
}
$result->free();
$mysqli->close();
?>

<form action="setdata.php" method="post">
<div style="border:10px orange solid;padding:15px;font-size:40px">
<input type="hidden" name="decide" value="<? echo $_SESSION['decide']; ?>">
自動換氣系統設定檔<BR><BR>
選項:
<select name="typ" style="font-size:40px;">
<option>啟動換氣系統1下限值
<option>關閉換氣系統1上限值
<option>啟動換氣系統2下限值
<option>關閉換氣系統2上限值
</select><br><br>
PM2.5臨界值:
<input type ="text" name="val" value="" style="font-size:30px;">
<br>
密碼:
<input type ="password" name="passwd" value="" style="font-size:30px;">
<br>
<br>
<input type ="submit" value="設定" style="font-size:50px;" >
<input type ="button" value="更新數據" id="refresh">
<div id="msg"> </div>


<br>

</div>
<div style="border:10px orange solid;padding:10px;font-size:40px">
空氣品質紀錄:<BR>

<div id="lastrec"> </div>
<br>
<div style="background-color:pink;padding:10px;">
<a href="https://thingspeak.com/channels/1026081"  target="_blank">管道間(樹莓+PMS3003)</a>
<br>
<br>
近期超標紀錄
<table style="width: 800px;" border="3"><tbody><tr>
<td><font size="10">時間</font></td><td><font size="10">數值(µg/m³)</font></td></tr>
<!-- 取超標值 -->
<?php
$mysqli = new mysqli("10.0.4.15","duckegg","1234","duckegg");     //實例化mysqli
$query  = "SELECT * FROM envlog where dev_typ = 'PMS3003-1-PM2.5' and val > $miop1_on ".
          "order by log_tim DESC limit 20";
$result = $mysqli->query($query);
if ($result) {
    if($result->num_rows>0){                   
       while($row = $result->fetch_array() ){ 
         echo '<tr><td><font size="10">'.$row[3].'</font></td><td><font size="10">'.$row[2].'</font></td></tr>';
       }
    }
}else {
    echo "查詢失敗";
}
$result->free();
$mysqli->close();
?>
</tr>
</tbody></table>
<br>

<br>
近期通風系統啟閉紀錄
<table style="width: 800px;" border="3"><tbody><tr>
<td><font size="10">時間</font></td><td><font size="10">狀態</font></td></tr>
<!-- 取超標值 -->
<?php
$mysqli = new mysqli("10.0.4.15","duckegg","1234","duckegg");     //實例化mysqli
$query  = "SELECT * FROM envlog where dev_typ = 'fan_1_power' ".
          " order by log_tim DESC limit 20";
$result = $mysqli->query($query);
if ($result) {
    if($result->num_rows>0){                   
       while($row = $result->fetch_array() ){ 
         if ($row[2]==1){
            $sts = "啟動";
            echo '<tr><td><font size="10">'.$row[3].'</font></td><td><font size="10">'.$sts.'</font></td></tr>';
         } else{
            $sts = "關閉";
            echo '<tr><td style="background-color:#BBFFBB"><font size="10">'.
                 $row[3].'</font></td><td td style="background-color:#BBFFBB">'.
                 '<font size="10">'.$sts.'</font></td></tr>';
         }
       }
    }
}else {
    echo "查詢失敗";
}
$result->free();
$mysqli->close();
?>
</tr>
</tbody></table>
<br>
PM1
<br>
<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/widgets/167285"></iframe>
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/charts/1?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM1&type=spline&yaxismax=100"></iframe>
<br>
PM2.5
<br>
<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/widgets/166622"></iframe>
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/charts/2?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM2.5&type=spline&yaxismax=100"></iframe>
<br>
PM10
<br>
<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/widgets/166623"></iframe>
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1026081/charts/3?bgcolor=%23ffffff&color=%23d62020&days=+3&dynamic=true&timescale=10&title=PM10&type=spline&yaxismax=100"></iframe>
<br>
<br>
<a href="https://thingspeak.com/channels/1031670"  target="_blank">客廳(Arduion+PMS3003)</a>
<br>
PM1
<br>
<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/widgets/167899"></iframe>
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/charts/1?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM1&type=spline"></iframe>
<br>
PM2.5
<br>
<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/widgets/167900"></iframe>
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/charts/2?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM2.5&type=spline"></iframe>
<br>
PM10
<br>
<iframe width="400" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/widgets/167901"></iframe>
<iframe width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/1031670/charts/2?bgcolor=%23ffffff&color=%23d62020&days=3&dynamic=true&timescale=10&title=PM2.5&type=spline"></iframe>
</div>
</div>
</form>
<style>
a {
    text-decoration:none;
}
</style>
</body></html>
<!-- 取目前設定值 -->
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#refresh').click(function (){
         $.ajax({url: 'getdata.php',cache: false,dataType: 'html',type:'GET',data: { name: $('#name').val()},
                 error: function(xhr) {alert('Ajax request 發生錯誤');},
                 success: function(response) {$('#msg').html(response);
                 $('#msg').fadeIn();}});});
$("#loadingImg").ajaxStart(function(){
   $(this).show();
});
})
</script>
<!-- 取記錄 -->
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#refresh').click(function (){
         $.ajax({url: 'getlastrec.php',cache: false,dataType: 'html',type:'GET',data: { name: $('#name').val()},
                 error: function(xhr) {alert('Ajax request 發生錯誤');},
                 success: function(response) {$('#lastrec').html(response);
                 $('#lastrec').fadeIn();}});});
$("#loadingImg").ajaxStart(function(){
   $(this).show();
});
})
</script>