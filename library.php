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
$is=mysql_query("SELECT u.*,us.*,h.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid LEFT JOIN houses h ON h.hWILL=u.maxwill WHERE u.userid=$userid",$c) or die(mysql_error());
$ir=mysql_fetch_array($is);
check_level();
$fm=money_formatter($ir['money']);
$lv=date('F j, Y, g:i a',$ir['laston']);
$h->userdata($ir,$lv,$fm, $is);
$h->menuarea();
if(!$_GET['read'])
{


print "<h3><b>Welcome to the Library!</h3></b><br /> Would you like to read some books in the library? It restores your will but it will make you tired.<br />
<br /><b><a href='library.php?read=w'>Read Will Power books!</a></b><br />or<br /><b><a href='index.php'>Leave the Library</a></b><br />";
}
else if ($ir['level'] > 50)
{
if ($ir['energy'] == $ir['maxenergy'])
{
$half = $ir['energy'];
mysql_query("UPDATE users SET energy=0, will=(maxwill) WHERE userid=$userid",$c);
print "You run out of the library, ready to take on the world! <br>
<a href ='inventory.php'>>Click here to go to your items</a>";
}
else
{

print "Sorry you need at least 100% energy to read. Your too tired to read. Get some sleep, bro!";
}
}
else 
{
if ($ir['energy'] >= (($ir['maxenergy'])/2))
{
$half = $ir['maxenergy']/2;
mysql_query("UPDATE users SET energy=$half, will=(maxwill) WHERE userid=$userid",$c);
print "You run out of the library, ready to take on the world! <br>
<a href ='inventory.php'>>Click here to go to your items</a>";
}
else
{

print "Sorry you need at least 100% energy to read. Your too tired to read. Get some sleep, bro!";
}
}
$h->endpage();
?>