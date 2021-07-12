<?php
//利用WS傳送LINE
//參數：msg為訊息內容，做base64編碼
//呼叫範例：http://duckegg.duckdns.org:8088/sendtoline.php?msg=xxxxxxxxx

 $msg = $_GET['msg'];
 $msg =  base64url_decode($msg);
 $cmd = 'python3 sendtoline.py "'.$msg.'"';
 echo $cmd;
 system($cmd, $result);

function base64url_decode($data) {                                                                    return base64_decode(str_pad(strtr($data, '-_', '+/'), 
                       strlen($data) % 4, '=', STR_PAD_RIGHT));                   
}                                                                                                                                                                            

?>
