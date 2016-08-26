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
$h->userdata($ir,$lv,$fm,$is);
$h->menuarea();
$_GET['st'] = abs((int) $_GET['st']);
$st=($_GET['st']) ? $_GET['st'] : 0;
$ord=($_GET['ord']) ? $_GET['ord'] : 'DESC';
print "<h3>Leaderboards</h3>";
$cnt=mysql_query("SELECT userid FROM users",$c);
$membs=mysql_num_rows($cnt);
$pages=(int) ($membs/100)+1;
$stl=($i-1)*100;
print "<a href='userlist.php?st=$stl&by=$by&ord=$ord'>$i</a>&nbsp;";
//print "<br />
//Order By: <a href='userlist.php?st=$st&by=userid&ord=$ord'>User ID</a>&nbsp;| <a href='userlist.php?st=$st&by=username&ord=$ord'>Username</a>&nbsp;| <a href='userlist.php?st=$st&by=level&ord=$ord'>Level</a>&nbsp;| <a href='userlist.php?st=$st&by=money&ord=$ord'>Money</a><br />
//<a href='userlist.php?st=$st&by=$by&ord=asc'>Ascending</a>&nbsp;| <a href='userlist.php?st=$st&by=$by&ord=desc'>Descending</a><br /><br />";
$q=mysql_query("SELECT u.*,uv.* FROM users u LEFT JOIN userstats uv ON u.userid=uv.userid ORDER BY strength+agility+guard $ord LIMIT $st,10",$c);
$no1=$st+1;
$no2=$st+10;
print "Top $no2 users.
<table width=75% border=2><tr style='background:gray'><th>ID</th><th>Name</th><th>Money</th><th>Power Level</th><th>Race</th><th>Rank</th><th>Online</th></tr>";
while($r=mysql_fetch_array($q))
{

$ts=$r['strength']+$r['agility']+$r['guard'];
$tsrank=get_rank($ts,'strength+agility+guard');
$ts=number_format($ts);
$rank = $ts;

$d="";

print "<tr><td>{$r['userid']}</td><td><a href='viewuser.php?u={$r['userid']}'>{$r['gangPREF']} {$r['username']} $d</a></td><td>\${$r['money']}</td><td>{$ts}</td><td>{$r['race']}</td><td>{$r['rank']}</td><td>";
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