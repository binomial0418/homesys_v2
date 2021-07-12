# homesys_v2
家庭自動化系統V2版<br>

1.本系統用來自動化家庭中設備，目前主要為兩系統：<br>
  a.自動換氣系統。<br>
  b.HomeBridge資料更新。<br>
<br>  
2.本系統用到的相關技術：python3,MySQL,arduino,php(既然有php那javascript/css/ajax這些理理摳摳的東西都會有)<br>
<br>
3.自動換氣系統：<br>
a.用來操控管道間及浴室排風扇。<br>
b.系統換自動偵測來自管道間的PM2.5數據，超過臨界值時自動啟動管道間排風機及浴室抽風機，避免有害氣體進入室內。（抓其他樓層住戶抽菸用）<br>
c.程式分為：<table><tr><td>
    homesys.py --->此為主要程式，用來監控資料庫中的數據以啟動或是關閉管道間的小米插座，藉以操控管道間抽風機，並且發出對應的指令給相關設備。
    </td></tr>
    <tr><td>
    powerir.py --->將指令已藍芽+IR方式傳送給浴室抽風機，啟動或關閉浴室抽風。
    </td></tr>
    <tr><td>  
	  getircmd.php--->供arduino設備利用http的方式取得目前紅外線指令。
    </td></tr>  
    <tr><td>  
	  getenvlog.php --->供arduino設備利用http的方式寫入感測器數據到後台DB。
    </td></tr>  
    <tr><td>  
	  /html/homesys--->以網頁方式顯示後台BD中的數據資料，以及調整通風系統啟閉臨界值。
    </td></tr>  
    <tr><td>  
	  arduino程式：
       1.ESP8266+PMS5003：將管道間的PM2.5數據傳回後端DB，供homesys.py分析使用。
	     2.ESP8266+IR發射元件：定期查詢DB中的控制指令，並且以IR元件發送命令來啟動/關閉浴室抽風機。
    </td></tr>  
   </table>
<br>
4.homekit.py:將DB中PM2.5數據送進HomeBridge中。<br>
  
