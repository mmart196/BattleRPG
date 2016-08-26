<?php
include "mysql.php";
global $c, $mykey;
//$path=$_SERVER['HTTP_HOST'].str_replace('cron_battlearena.php','',6399465);
//if($_GET['code'] = md5($path.$mykey))
//{
//exit;
//}
$query="UPDATE users SET batpts=batpts+30 WHERE location=0";
$query2="UPDATE users SET batpts=batpts+30 WHERE location=0 AND raceID>=10";
$query3="UPDATE users SET batpts=batpts+30 WHERE location=0 AND raceID>=20";
$query4="UPDATE users SET batpts=batpts+160 WHERE location=0 AND membership=2";
mysql_query($query,$c) or die("\nError Executing Query 1 for updating users $i to $next\n$query\n".mysql_error()."\nError Code:".mysql_errno());
mysql_query($query2,$c) or die("\nError Executing Query 1 for updating users $i to $next\n$query\n".mysql_error()."\nError Code:".mysql_errno());
mysql_query($query3,$c) or die("\nError Executing Query 1 for updating users $i to $next\n$query\n".mysql_error()."\nError Code:".mysql_errno());
mysql_query($query4,$c) or die("\nError Executing Query 1 for updating users $i to $next\n$query\n".mysql_error()."\nError Code:".mysql_errno());
?>