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
$poster = mysql_fetch_array(mysql_query("SELECT * FROM gachapon WHERE userid=$userid", $c));
$amo = $poster['amount'];
if ($amo == NULL){$amo = 0;}
if (!$_GET['roll'])
{


print "<h3>Gachapon!</h3>
<img src='gachapon.png'>
<br><br>
<b>Put in a Crystal: (You have $amo in the Gachapon) 
</b><form action='crystalmachine.php' method='get'>
Put Crystals through your inventory! <br />
If you roll you lose all the crystals you put into the machine!
<br>
<font color='blue'><a href ='crystalmachine.php?roll=1'>CLICK HERE TO ROLL!</a></font><br /></form><hr />";

print "If you roll with less than 10 crystals, you get a random item! <br>
If you roll with over 10 crystals, you get a rare item! <br>
If you roll with over 25 crystals, you get a rare bag!";

}
else
{
$poster = $poster['amount'];
if ($_GET['roll'])
{
if ($poster > 0)
{
if ($poster >= 25)
{

mysql_query("INSERT INTO inventory VALUES (NULL , 143, $userid, 1)", $c);
print "You received a rare bag!";
}
else if ($poster >= 10)
{
$item = rand(135, 143);
mysql_query("INSERT INTO inventory VALUES (NULL , $item, $userid, 1)", $c);
print "Your received an item go check your items!";
}
else if ($poster < 10)
{
$item = rand(113, 143);
if ($item == 133)
{
$item = rand(109,110);
}
mysql_query("INSERT INTO inventory VALUES(NULL , $item, $userid, 1)", $c);
print "Your received an item go check your items!";
}
mysql_query("DELETE FROM gachapon WHERE userid=$userid",$c);
}
else
{
print "You have no crystals in the machine!";
}
}
}


$h->endpage();