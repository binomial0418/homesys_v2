<html>
<head>
　<title>Homesys</title>
<head>
<body>
<!-- <form action="setdata.php" method="post"> -->
<form>
<div style="border:10px orange solid;padding:15px;font-size:40px">
自動換氣系統設定檔<BR><BR>
選項:
<select name="typ" id="typ" style="font-size:40px;">
<option>啟動換氣系統1下限值
<option>關閉換氣系統1上限值
<option>啟動換氣系統2下限值
<option>關閉換氣系統2上限值
<input type ="text" name="val" id="val" value="" style="font-size:30px;">
<br>
密碼:
<input type ="password" name="passwd" id="passwd" value="" style="font-size:40px;">
<input type ="button" value="設定" id="setbtn" style="font-size:40px;" ><br>
</span>
<br>
<br>
<input type ="button" value="取得目前設定值" id="configbtn" style="font-size:40px;" >
<!-- <div id="loadingImg1" style="display:none"><img src="https://media.giphy.com/media/feN0YJbVs0fwA/giphy.gif"> </div> -->
<div id="configval"> </div>
<div id="loadingImg1" style="display:none"><img src="http://duckegg.duckdns.org:8088/homesys/001gif.gif"> </div>
<br>
</div>
<div style="border:10px orange solid;padding:10px;font-size:40px">
<input type ="button" value="更新數據" id="refresh" style="font-size:50px;" >
<div id="loadingImg" style="display:none"><img src="https://media.giphy.com/media/feN0YJbVs0fwA/giphy.gif"> </div>
<!-- <div id="loadingImg" style="display:none"><img src="http://duckegg.duckdns.org:8088/homesys/001gif.gif"> </div> -->
<!-- <div id="loadingImg" style="display:none"><img src="https://media.giphy.com/media/IwSG1QKOwDjQk/giphy.gif"> </div> -->
<div id="lastrec"> </div>
</div>
</form>
<style>
a {
    text-decoration:none;
}
#loadingImg{
top:150px;
left:30%;
text-align:center;
padding:7px 0 0 0;
font:bold 11px Arial, Helvetica, sans-serif;
}
#loadingImg1{
top:150px;
left:30%;
text-align:center;
padding:7px 0 0 0;
font:bold 11px Arial, Helvetica, sans-serif;
}
</style>
</body></html>
<!-- 取值 -->
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
<script type="text/javascript">
window.onload=s;
function s(){
  $('#refresh').click();
  $('#configbtn').click();
}

$(document).ready(function () {
  $('#setbtn').click(function (){
         $.ajax({url: 'setdata_jq.php',cache: false,dataType: 'html',type:'GET',
                 data: { typ: $('#typ').val(),val: $('#val').val(),passwd: $('#passwd').val()},
                 error: function(xhr) {alert('發生錯誤');},
                 success: function(response) {$('#configval').html(response);
                 $('#configval').fadeIn(1000);}});
         $('#configbtn').click();
           });
  $('#configbtn').click(function (){
         $("#configval").hide();
         $('#loadingImg1').show();
         $.ajax({url: 'getdata.php',cache: false,dataType: 'html',type:'GET',data: { name: $('#name').val()},
                 error: function(xhr) {alert('設定檔取回發生錯誤');},
                 success: function(response) {$('#configval').html(response);
                 $('#configval').fadeIn(1000);}});
           });
  $('#refresh').click(function (){
         $("#lastrec").hide();
         $('#loadingImg').show();
         $.ajax({url: 'getlastrec.php',cache: false,dataType: 'html',type:'GET',data: { name: $('#name').val()},
                 error: function(xhr) {alert('紀錄值取回發生錯誤');},
                 success: function(response) {$('#lastrec').html(response);
                 $('#lastrec').fadeIn(1000);}});
          });
  $("#loadingImg").ajaxStop(function(){$('#loadingImg').hide();
                                       $('#loadingImg1').hide();});
  $("#lastrec").ajaxStop(function(){$('#lastrec').show();
                                    $('#configval').show();});
})
</script>