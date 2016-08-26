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
print "<h3>General Info:</h2>";
$exp=(int)($ir['exp']/$ir['exp_needed']*100);
print "<table><tr><td><b>Name:</b> {$ir['username']}</td><td><b>Property:</b> {$ir['hNAME']}</td></tr><tr>
<td><img src='anime-helmet.png'><b>Level:</b> {$ir['level']}</td>
<td><img src='anime-medal.png'><b>Exp:</b> {$exp}%</td></tr><tr>
<td><img src='anime-green letter s.png'><b>Money:</b> $fm</td>
<td><img src='anime-hp heart.png'><b>HP:</b> {$ir['hp']}/{$ir['maxhp']}</td></tr></table>";
print "<hr><h3>Stats Info:</h3>";
$ts=$ir['strength']+$ir['agility']+$ir['guard'];
$ir['strank']=get_rank($ir['strength'],'strength');
$ir['agirank']=get_rank($ir['agility'],'agility');
$ir['guarank']=get_rank($ir['guard'],'guard');
$tsrank=get_rank($ts,'strength+agility+guard');
$ir['strength']=number_format($ir['strength']);
$ir['agility']=number_format($ir['agility']);
$ir['guard']=number_format($ir['guard']);
$ts=number_format($ts);
print "<table><tr><td><img src='anime-sword.png'><b> Strength:</b> {$ir['strength']} [Ranked: {$ir['strank']}]</td><td><img src='anime-winged feet.png'><b> Agility:</b> {$ir['agility']} [Ranked: {$ir['agirank']}]<td><img src='anime-shield.png'><b> Guard:</b> {$ir['guard']} [Ranked: {$ir['guarank']}]</td></td></tr>
<tr><td><img src='scouter.jpg'><b> Power level:</b> {$ts} [Ranked: $tsrank]</td></tr></table>

<hr><h3>Genetic Info:</h3> 
<table><tr><td><b>Saiyan:</b> {$ir['Saiyan']}% </td></tr>
<table><tr><td><b>Senju:</b> {$ir['Senju']}% </td></tr>
<table><tr><td><b>Uchiha:</b> {$ir['Uchiha']}% </td></tr>
<table><tr><td><b>Hyuga:</b> {$ir['Hyuga']}% </td></tr>
<table><tr><td><b>Diclonius:</b> {$ir['Diclonius']}% </td></tr>
<table><tr><td><b>Demon:</b> {$ir['Demon']}% </td></tr>
<table><tr><td><b>Vampire:</b> {$ir['Vampire']}% </td></tr>
</table>
<br>
";
print"<font color='darkblue'><a href='dnabook.php?u=1'>Click here to go through other people's genetic information.</a></font><br><br>";
$h->endpage();
?>