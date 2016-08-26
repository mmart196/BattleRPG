<?php
/*
MCCodes FREE
index.php Rev 1.1.0c
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
                "SELECT u.*,us.*,h.*,ud.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid LEFT JOIN userdna ud 
                ON u.userid=ud.userid LEFT JOIN houses h ON h.hWILL=u.maxwill WHERE u.userid=$userid",
                $c) or die(mysql_error());
$ir = mysql_fetch_array($is);
check_level();
check_class();
roll_dna();
$fm = money_formatter($ir['money']);
$cm = money_formatter($ir['crystals'], '');
$lv = date('F j, Y, g:i a', $ir['laston']);
$h->userdata($ir, $lv, $fm, $cm);
$h->menuarea();
print"You cant use this because of SAND RA KING. HE is in jail lol";




/*
if ($_GET['times'])
{
$name = $ir['username'];

$shout = mysql_query("SELECT * FROM shout",$c);
$shout = mysql_fetch_array($shout);
$text = $_GET['times'];
if ($shout['username'] != $ir['username'])
{
mysql_query("INSERT INTO shout VALUES('$name', '$text', 1)",$c);
print "Your shout has been heard!";
}
else
{
mysql_query("UPDATE shout SET text={$_GET['times']} WHERE username='{$name}'",$c);
print "You modified your current shout!<br>";
}
}

print
        "Enter your message! <br /><form action='test.php' method='get'>
<input type='text' name='times'/><br />
<input type='submit' value='Submit!' /></form>";


?>