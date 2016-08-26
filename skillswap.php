<?php
/*
MCCodes FREE
search.php Rev 1.1.0c
Copyright (C) 2005-2012 Dabomstew

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

session_start();
require "global_func.php";
if ($_SESSION['loggedin'] == 0)
{
    header("Location: login.php");
    exit;
}
$userid = $_SESSION['userid'];
require "header.php";
$h = new headers;
$h->startheaders();
include "mysql.php";
global $c;
$is =
        mysql_query(
                "SELECT u.*,us.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid WHERE u.userid=$userid",
                $c) or die(mysql_error());
$ir = mysql_fetch_array($is);
check_level();
$fm = money_formatter($ir['money']);
$cm = money_formatter($ir['crystals'], '');
$lv = date('F j, Y, g:i a', $ir['laston']);
$h->userdata($ir, $lv, $fm, $cm);
$h->menuarea();
$poster = mysql_fetch_array(mysql_query("SELECT * FROM potential WHERE userid=$userid", $c));
$amo = $poster['amount'];
if ($amo <1){$amo = 0;}
if (!$_GET['roll'])
{


print "<h3>Super Kami Guru!</h3>
<img src='http://tfsabridged.wikispaces.com/file/view/thumbnail.aspx.jpeg/260681580/thumbnail.aspx.jpeg'>
<br>
<br>
<b>Show me what you have learned and I will unlock your potential! <br></b>
Don't worry I wont scam you like the Gachapon! <br> It will only take the potential it needs! (You have $amo potential!) 
</b><form action='skillswap.php' method='get'>
Turn your skills into potential! <br />
<br>
<font color='blue'><a href ='skillswap.php?roll=1'>CLICK HERE FOR POTENTIAL!</a></font><br /></form><hr />";

print "B Rank costs 10 Potential <br>
A Rank costs 100 potential <br>
S Rank costs 1000 potential";
}
else
{
$poster = $poster['amount'];
if ($_GET['roll'])
{
if ($poster > 0)
{
//S Rank
if ($poster >= 1000)
{
if ($ir['rankID'] < 8)
{
$pick = rand(159, 160);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an S rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 14)
{
$pick = rand(166);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an S rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 20)
{
$pick = 170;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an S rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] < 26)
{
$pick = rand(177, 179);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an S rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] == 37)
{
$pick = rand(188);
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an S rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
elseif ($ir['rankID'] == 26)
{
$pick = 189;
mysql_query("INSERT INTO inventory VALUES(NULL, $pick, $userid, 1)", $c);
print"You learned an S rank skill!
<a href='inventory.php'><b>Check Items</b></a> or <a href='learnskill.php?learn=w'><b>Try again</b></a>?";
}
mysql_query("UPDATE potential SET amount=amount-500 WHERE userid=$userid",$c);
}


//A Rank
elseif ($poster >= 100)
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
mysql_query("UPDATE potential SET amount=amount-100 WHERE userid=$userid",$c);
}

//B Rank
elseif ($poster >= 10)
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
mysql_query("UPDATE potential SET amount=amount-10 WHERE userid=$userid",$c);
}
else if ($poster < 10)
{
print "You don't have enough potential!";
}

}
else
{
print "You have no potential!";
}
}
}


$h->endpage();