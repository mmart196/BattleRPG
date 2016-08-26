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
$skreq = mysql_query("SELECT * FROM coursesdone WHERE userid=$userid", $c);
$sk = mysql_fetch_array($skreq);
if(!$_GET['learn']) // && $sk['courseid']==2)
{
print "<h3><b>Urahara Shouten</h3></b><br />
<br>
<br>
 Hello Hello! Welcome! Wanna learn something new? Only $5,000 will do. <br> Teach yourself a Skill!<br />
<hr>
<b>
0.01% for A Rank Skills (A Rank skills are worth 100 potential)
<br>
0.5% for B Rank Skills (B Rank skills are worth 10 potential)
<br>
5% for C Rank Skills (C Rank skills are worth 1 potential)
<br>
10% for D Rank Skills (only ninjas have them at the moment)
</b>
<hr>

<br/> Note: Names for pirate attacks are under construction! <br>
<br /><b><a href='learnskill.php?learn=w'>Learn a new Technique!</a></b><br />or<br /><b><a href='index.php'>Back</a></b><br />";
}
else if ($_GET['learn'] && $ir['money'] > 4999)
{
mysql_query("UPDATE users SET money=money-5000 WHERE userid=$userid", $c);
//out of 100,000 tries!
$luck = rand(0,100000);
//0.01% chance for A
if ($luck < 10)
{
if ($ir['rankID'] < 8)
{
$pick = rand(156, 158);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an A rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 14)
{
$pick = rand(164, 165);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an A rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 20)
{
$pick = 169;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an A rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 26)
{
$pick = rand(175, 176);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an A rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] == 37)
{
$pick = rand(183, 185);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an A rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] == 26)
{
$pick = 187;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an A rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}



}

//0.5% chance for B
elseif ($luck < 500)
{
if ($ir['rankID'] < 8)
{
$pick = rand(153, 155);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);

print"You learned an B rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}

elseif ($ir['rankID'] < 14)
{
$pick = rand(162, 163);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned a B rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 20)
{
$pick = 168;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned a B rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 26)
{
$pick = rand(173, 174);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an B rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] == 37)
{
$pick = rand(181, 182);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an B rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] == 26)
{
$pick = 186;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an B rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}


}

//5% chance for C
elseif ($luck < 5000)
{
if ($ir['rankID'] < 8)
{
$pick = rand(151, 152);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an C rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}

elseif ($ir['rankID'] < 14)
{
$pick = 161;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned a C rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 20)
{
$pick = 167;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned a C rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 26)
{
$pick = rand(171, 172);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an C rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
else 
{
print"You couldn't learn anything. <br> <a href='learnskill.php?learn=w'>TRY AGAIN(Cost: $5,000)</a>";
}
}

//10% chance for D
elseif ($luck < 10000)
{
if ($ir['rankID'] < 8)
{
$pick = rand(148, 150);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);

print"You learned an D rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
else 
{
print"You couldn't learn anything. <br> <a href='learnskill.php?learn=w'>TRY AGAIN(Cost: $5,000)</a>";
}
}
else 
{
print"You couldn't learn anything. <br> <a href='learnskill.php?learn=w'>TRY AGAIN(Cost: $5,000)</a>";
}

}
else
{
print "You need at least $5,000!";
}
$h->endpage();
?>