<?php
session_start();
require "global_func.php";
if($_SESSION['loggedin']==0) { header("Location: login.php");exit; }
$userid=$_SESSION['userid'];
require "header.php";
$h = new headers;
$h->startheaders();
include "mysql.php";
global $c;
$is=mysql_query("SELECT u.*,us.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid WHERE u.userid=$userid",$c) or die(mysql_error());
$ir=mysql_fetch_array($is);
check_level();
$fm=money_formatter($ir['money']);
$lv=date('F j, Y, g:i a',$ir['laston']);
$h->userdata($ir,$lv,$fm, $is);
$h->menuarea();
if ($ir['level'] >=5)
{
if(!$_GET['enter'])
{
print "<b>WARNING!</b><br />You are about to enter the Battle Arena! In the battle arena, you can be attacked by any race. You will, however, receive a battle point every hour if your lucky.
It is not guaranteed that you will receive a battle point every hour. <br />
You can only be attacked if your powerlevel is more than half of the attackers powerlevel. Bounty is added to your powerlevel when you are being attacked. 
<br /><b><a href='battlearena.php?enter=1337'>Enter</a></b><br />or<br /><b><a href='searcharea.php'>Check Arena</a></b><br />or<br /><b><a href='index.php'>Go back</a></b><br />


";
}
else
{
mysql_query("UPDATE users SET location=0 WHERE userid=$userid",$c);
print "You entered Battle Arena!";
}
}
else
{
print "You must be at least level 5 to enter the Battle Arena.";
}
$h->endpage();
?>




?>