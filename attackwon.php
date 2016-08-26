<?php
/*
MCCodes Lite
attackwon.php Rev 1.0.0
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
$h->userdata($ir,$lv,$fm,0);
$h->menuarea();

$_GET['ID']=abs((int) $_GET['ID']);
$_SESSION['attacking']=0;
$od=mysql_query("SELECT * FROM users WHERE userid={$_GET['ID']}",$c);
if($_SESSION['attackwon'] != $_GET['ID'])
{
die ("Want to go to jail?");
}
if(mysql_num_rows($od))
{
$r=mysql_fetch_array($od);
if($r['hp'] == 1)
{
print "What a cheater you are.";
}
else
{
$bounty = mysql_query("SELECT amount FROM bounty WHERE userid={$r['userid']}",$c);
if (mysql_num_rows($bounty) == 0)
{
$stole=(rand($r['money']/20,$r['money']/5));
print "You beat {$r['username']} and stole \$$stole. You have been rewarded with 1 Battle Point!";
$qe=$r['level']*$r['level']*$r['level'];
// $expgain=(2*$r['level']); $expperc=(int) ($expgain/$ir['exp_needed']*1000); print " and gained $expperc% EXP!";
mysql_query("UPDATE users SET money=money+$stole, batpts=batpts+1 WHERE userid=$userid",$c);
mysql_query("UPDATE users SET hp=1, location=6 WHERE userid={$r['userid']}",$c);
event_add($r['userid'],"<a href='viewuser.php?u=$userid'>{$ir['username']}</a> attacked you and stole $stole.",$c);
mysql_query("INSERT INTO attacklogs VALUES('',$userid,{$_GET['ID']},'won',unix_timestamp(),$stole,'$atklog');",$c);
$_SESSION['attackwon']=0;
}
else
{
$bounty = mysql_fetch_array($bounty);
$stole = ($bounty['amount'])/2;
print "You beat {$r['username']} and received \$$stole as a bounty. You have been rewarded with 1 Battle Point!";
$qe=$r['level']*$r['level']*$r['level'];
mysql_query("DELETE FROM bounty WHERE userid={$r['userid']}", $c);
// $expgain=(2*$r['level']); $expperc=(int) ($expgain/$ir['exp_needed']*1000); print " and gained $expperc% EXP!";
mysql_query("UPDATE users SET money=money+$stole, batpts=batpts+1 WHERE userid=$userid",$c);
mysql_query("UPDATE users SET hp=1, location=6 WHERE userid={$r['userid']}",$c);
event_add($r['userid'],"<a href='viewuser.php?u=$userid'>{$ir['username']}</a> attacked you and received a bounty of $stole.",$c);
$atklog=mysql_escape_string($_SESSION['attacklog']);
mysql_query("INSERT INTO attacklogs VALUES('',An assassin,'won',unix_timestamp(),$stole,'$atklog');",$c);
$_SESSION['attackwon']=0;
}


}
}
else
{
print "You beat Mr. non-existant!";
}
$h->endpage();
?>