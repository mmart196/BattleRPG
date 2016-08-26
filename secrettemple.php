<?php
/*
MCCodes FREE
crystaltemple.php Rev 1.1.0c
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
if (!$_GET['spend'])
{
    print
   
            "
      
            <br>
            <br>
            Welcome to the Secret Temple!<br />
You have <b>{$ir['crystals']}</b> Anime points!<br />
What would you like to spend your AP on?<br />
<br />
<a href='secrettemple.php?spend=item'>Rare Item - 1 AP</a><br />
<a href='secrettemple.php?spend=beans'>Three Senzu Beans - 1 AP</a><br />";
}
else
{
    if ($_GET['spend'] == 'item')
    {
        if ($ir['crystals'] < 1)
        {
            print "You don't have enough AP!";
        }
        else
        {
$item = rand(135, 143);
mysql_query("INSERT INTO inventory VALUES (NULL , $item, $userid, 1)", $c);
mysql_query("UPDATE users SET crystals=crystals-1 WHERE userid=$userid", $c);
print "Your received a rare item! Check your inventory!";
}
        if ($_GET['spend'] == 'beans')
    {
        if ($ir['crystals'] < 1)
        {
            print "You don't have enough AP!";
        }
        else
        {
$item = 113;
mysql_query("INSERT INTO inventory VALUES (NULL , $item, $userid, 3)", $c);
mysql_query("UPDATE users SET crystals=crystals-1 WHERE userid=$userid", $c);
print "Your received three senzu beans! Check your inventory!";
}
    }
}
}

$h->endpage();