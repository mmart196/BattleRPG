<?php
/*
MCCodes Lite
userlist.php Rev 1.0.0
Copyright (C) 2006 Dabomstew

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
$h->userdata($ir,$lv,$fm,$is);
$h->menuarea();
$_GET['st'] = abs((int) $_GET['st']);
$st=($_GET['st']) ? $_GET['st'] : 0;
$by=($_GET['by']) ? $_GET['by'] : level;
$ord=($_GET['ord']) ? $_GET['ord'] : 'ASC';
print "<h3>Userlist</h3>";
$cnt=mysql_query("SELECT userid FROM users",$c);
$membs=mysql_num_rows($cnt);
$pages=(int) ($membs/100)+1;
if($membs % 100 == 0)
{
$pages--;
}
print "Pages: ";
for($i=1;$i <= $pages;$i++)
{
$stl=($i-1)*100;
print "<a href='searcharea.php?st=$stl&by=$by&ord=$ord'>$i</a>&nbsp;";
}
print "<br />
Order By: <a href='searcharea.php?st=$st&by=username&ord=$ord'>Username</a>&nbsp;| <a href='searcharea.php?st=level&by=$ts&ord=$ord'>Level</a>&nbsp;| <a href='searcharea.php?st=$st&by=money&ord=$ord'>Money</a><br />
<a href='searcharea.php?st=$st&by=$by&ord=asc'>Ascending</a>&nbsp;| <a href='searcharea.php?st=$st&by=$by&ord=desc'>Descending</a><br /><br />";
$q=mysql_query("SELECT u.*,us.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid WHERE u.location=0 ORDER BY $by $ord LIMIT $st,100",$c);
$no1=$st+1;
$no2=$st+100;
print "Showing users $no1 to $no2 by order of $by $ord.
<table width=75% border=2><tr style='background:gray'><th>ID</th><th>Name</th><th>Money</th><th>Level</th><th>PowerLevel</th><th>Online</th></tr>";
while($r=mysql_fetch_array($q))
{
//
$ts=$r['strength']+$r['agility']+$r['guard'];
$tsrank=get_rank($ts,'strength+agility+guard');
$ts=number_format($ts);
$rank = $ts;
//
$d="";

print "<tr><td>{$r['userid']}</td><td><a href='viewuser.php?u={$r['userid']}'>{$r['gangPREF']} {$r['username']} $d</a></td><td>\${$r['money']}</td><td>{$r['level']}</td><td>{$ts}</td><td>";
if($r['laston'] >= time()-15*60)
{
print "<font color=green><b>Online</b></font>";
}
else
{
print "<font color=red><b>Offline</b></font>";
}
print "</td></tr>";
}
print "</table>";

$h->endpage();
?>