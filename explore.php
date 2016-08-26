<?php
/*
MCCodes FREE
explore.php Rev 1.1.0c
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
$tresder = (int) rand(100, 999);
print
        "<b>You begin exploring the area you're in, you see a bit that interests you.</b><br />
<table width=75%><tr height=100><td valign=top>
<u>Market Place</u><br />
<a href='shops.php'>Shops</a><br />
<a href='itemmarket.php'>Item Market</a><br>
<a href='learnskill.php'>Urahara Shouten</a></td>
<td valign=top
<u>Serious Money Makers</u><br />
<a href='monorail.php'>Travel Agency</a><br />
<a href='estate.php'>Estate Agent</a><br />
</td>
<td valign=top>";

print
        "</td><td valign=top>
<u>Dark Side</u><br />
<a href='battlearena.php'>Battle Arena</a><br />
<a href='bounty.php'>Bounty</a><br />
<a href='fedjail.php'>Federal Jail</a><br />
<a href='slotsmachine.php?tresde=$tresder'>Slots Machine</a><br />
<a href='roulette.php?tresde=$tresder'>Roulette</a></td></tr><tr height=100>
<td valign=top>";

print
        "</td><td valign=top>
<u>Statistics Dept</u><br />
<a href='userlist.php'>User List</a><br />
<a href='stafflist.php'>Battlerpg Staff</a><br />
<a href='halloffame.php'>Leaderboards</a><br />
<a href='stats.php'>Game Stats</a><br />
<a href='usersonline.php'>Users Online</a></td><td valign=top>&nbsp;</td><td valign=top>
<u>Mysterious</u><br />
<a href='secrettemple.php'>Secret Temple</a><br />
<a href='crystalmachine.php'>Gachapon</a><br />
<a href='skillswap.php'>Super Kami Guru</a><br />
<a href='rebirth.php'>Rebirth</a><br />

";
if ($ir['location'] == 0)
{
    print "<a href='searcharea.php'>Search Area</a><br />";
}
$game_url = determine_game_urlbase();
print
        "</td></tr></table><br /><br />This is your referal link: http://{$game_url}/register.php?REF=$userid <br />
Every signup from this link earns you two AP! <br>
Referrals must be real! Else, you will be banned :)";
$h->endpage();