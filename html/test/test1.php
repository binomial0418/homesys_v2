<?php 
//�Q��WS
//�ѼơGir_no
//�I�s�d�ҡGhttp://duckegg.duckdns.org:8088/getircmd.php?ir_no=bath1
$typ      = $_GET['ir_no'];
$serve    = 'mysql:host=10.0.4.15:3306;dbname=duckegg;charset=utf8';
$username = 'duckegg';
$password = '1234';
// PDO�s�u��Ʈw�Y���~�h�|���Y�@��PDOException���`

$PDO = new PDO($serve,$username,$password);
$query  = "update ircmd set tsc = '1' ,rtt = now() where cmd_no = 6";
$stmt = $PDO->prepare($query);

// execute the query
$stmt->execute();
$PDO = null;
?>