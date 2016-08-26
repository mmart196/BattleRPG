<?php
/*
MCCodes Lite
attack.php Rev 1.0.1
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
$is=mysql_query("SELECT u.*,us.*,ud.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid LEFT JOIN userdna ud ON u.userid=ud.userid WHERE u.userid=$userid",$c) or die(mysql_error());
$ir=mysql_fetch_array($is);
check_level();
$fm=money_formatter($ir['money']);
$lv=date('F j, Y, g:i a',$ir['laston']);
$h->userdata($ir,$lv,$fm, $is, 0);
$_GET['ID'] == (int) $_GET['ID'];
if(!$_GET['ID'])
{
print "WTF you doing, bro?";
$h->endpage();
exit;
}
else if($_GET['ID'] == $userid)
{
print "You cant attack yourself.";
$h->endpage();
exit;
}
else if ($_SESSION['attacklost'] == 1)
{
print "Only the losers of all their EXP attack when they've already lost.<br />
<a href='index.php'>Back</a>";
$_SESSION['attacklost']=0;
$h->endpage();
exit;
}
//get player data
$youdata=$ir;
$q=mysql_query("SELECT u.*,us.*,ud.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid LEFT JOIN userdna ud ON u.userid=ud.userid WHERE u.userid={$_GET['ID']}",$c);
$odata=mysql_fetch_array($q);
if($odata['hp'] == 1)
{
print "This player is unconscious.<br />
<a href='index.php'>&gt; Back</a>";
$h->endpage();
$_SESSION['attacking']=0;
exit;
}
if ($ir['location']!=0 AND $odata['location'] !=0)
{
print "Both of you must be in Battle Arena to do battle.";
exit;
}
$opl = $odata['strength'] + $odata['agility'] + $odata['guard'];
$opb = mysql_query("SELECT * FROM bounty WHERE userid={$_GET['ID']}", $c);
$opb = mysql_fetch_array($opb);
$opl = $opl + $opb['amount'];
$ypl = ($youdata['strength'] +$youdata['agility'] + $youdata['guard'])/2;
if ($ypl >= $opl)
{
print "You cant attack noobs.";
exit;
}
else
{
print "<table width=100%><tr><td colspan=2 align=center>";
if($_GET['wepid'])
{
if($_SESSION['attacking']==0)
{
if ($youdata['energy'] >= $youdata['maxenergy']/4)
{

$youdata['energy']-= $youdata['maxenergy']/4;
$me=$youdata['maxenergy']/4;
$_SESSION['attacklog']="";
}
else
{
print "You can only attack someone when you have 25% energy";
$h->endpage();
exit;
}
}
$_SESSION['attacking']=1;
$_GET['wepid'] = (int) $_GET['wepid'];
$_GET['nextstep'] = (int) $_GET['nextstep'];
//damage
$qr=mysql_query("SELECT * FROM inventory WHERE inv_itemid={$_GET['wepid']} and inv_userid=$userid",$c);
if(mysql_num_rows($qr)==0)
{
print "Stop trying to abuse a game bug. You can lose all your EXP for that.<br />
<a href='index.php'>&gt; Home</a>";
mysql_query("UPDATE users SET exp=0 where userid=$userid",$c);
die("");
}
// add $io=mysql_query("SELECT * FROM ranks rankname=$youdata['rank']",$c); for rank damage and in line 111 add $io['rankpower']
$qo=mysql_query("SELECT i.*,w.* FROM items i LEFT JOIN weapons w ON i.itmid=w.item_id WHERE w.item_id={$_GET['wepid']}",$c);
$r1=mysql_fetch_array($qo);
$mydamage=(int) ($r1['damage']*$youdata['strength']/$odata['guard'])*(rand(8000,12000)/10000);
$hitratio=min(50*$ir['agility']/$odata['agility'],95);
if(rand(1,100) <= $hitratio)
{
$q3=mysql_query("SELECT a.Defence FROM inventory iv LEFT JOIN items i ON iv.inv_itemid = i.itmid LEFT JOIN armour a ON i.itmid=a.item_ID WHERE i.itmtype=7 AND iv.inv_userid={$_GET['ID']} ORDER BY rand()", $c);
if(mysql_num_rows($q3))
{
$mydamage-=mysql_result($q3,0,0);
}
if($mydamage < 1) { $mydamage=1; }
$odata['hp']-=$mydamage;
//Your vamp calc
if ($youdata['Vampire'] == 100)
{
$vamp = $mydamage*0.25;
mysql_query("UPDATE users SET hp=hp+$vamp WHERE userid=$userid", $c);
print "<font color='green'>Your attack vamped {$vamp}.</font> <br>";
}
//hyuuga
if ($youdata['Hyuga'] == 100)
{
print"<font color='#F2F2F2'>You activated Byakugan to find weak points in your opponent.</font><br>";
$critran = rand(0, 100) > 90;
if ($youdata['Diclonius'] == 100 && $youdata['gender'] == 'Female')
{
$critran+=10;
print "<font color='green'> Your diclonius powers increased your critical chance!</font> <br>";
}
if ($critran > 90)
{
$mydamage = $mydamage*2;
print "<font color='red'><b>YOU CRIT!</font></b>";
}
}

if($odata['hp']==1) { $odata['hp']=0;$mydamage+=1; }
mysql_query("UPDATE users SET hp=hp-$mydamage WHERE userid={$_GET['ID']}",$c);
print "<font color=red>{$_GET['nextstep']}. Using your {$r1['itmname']} you hit {$odata['username']} doing $mydamage damage ({$odata['hp']})</font><br />\n";
$_SESSION['attacklog'].="<font color=red>{$_GET['nextstep']}. Using his {$r1['itmname']} {$ir['username']} hit {$odata['username']} doing $mydamage damage ({$odata['hp']})</font><br />\n";
}
else
{
print "<font color=red>{$_GET['nextstep']}. You tried to hit {$odata['username']} but missed ({$odata['hp']})</font><br />\n";
$_SESSION['attacklog'].="<font color=red>{$_GET['nextstep']}. {$ir['username']} tried to hit {$odata['username']} but missed ({$odata['hp']})</font><br />\n";
}
if($odata['hp'] <= 0)
{
$odata['hp']=0;
$_SESSION['attackwon']=$_GET['ID'];
mysql_query("UPDATE users SET hp=0 WHERE userid={$_GET['ID']}",$c);
print "<form action='attackwon.php?ID={$_GET['ID']}' method='post'><input type='submit' value='Mug Them' /></form>";
}
else {
//choose opp gun
$eq=mysql_query("SELECT iv.*,i.*,w.* FROM inventory iv LEFT JOIN items i ON iv.inv_itemid=i.itmid LEFT JOIN weapons w ON iv.inv_itemid=w.item_id WHERE iv.inv_userid={$_GET['ID']} AND ( i.itmtype=3 OR i.itmtype=4 )",$c);
if(mysql_num_rows($eq) == 0)
{
$wep="Fists";
$dam=(int)((((int) ($odata['strength']/100)) +1)*(rand(8000,12000)/10000))*($odata['rankpow']);
}
else
{
$cnt=0;
while($r=mysql_fetch_array($eq))
{
$enweps[]=$r;
$cnt++;
}
$weptouse=rand(0,$cnt-1);
$wep=$enweps[$weptouse]['itmname'];
$dam=(int) (($enweps[$weptouse]['damage']*$odata['strength']/$youdata['guard'])*(rand(8000,12000)/10000));
}
$hitratio=min(50*$odata['agility']/$ir['agility'],95);
if(rand(1,100) <= $hitratio)
{
$q3=mysql_query("SELECT a.Defence FROM inventory iv LEFT JOIN items i ON iv.inv_itemid = i.itmid LEFT JOIN armour a ON i.itmid=a.item_ID WHERE i.itmtype=7 AND iv.inv_userid=$userid ORDER BY rand()", $c);
if(mysql_num_rows($q3))
{
$dam-=mysql_result($q3,0,0);
}
// Their vamp calc
if ($odata['Vampire'] == 100)
{
$vamp = $dam*0.25;
mysql_query("UPDATE users SET hp=hp+$vamp WHERE userid={$_GET['ID']}", $c);
print "<font color='green'> {$odata['username']}'s attack vamped {$vamp}.</font> <br>";
}
//their hyuuga
if ($odata['Hyuga'] == 100)
{
print"<font color='#F2F2F2'> {$odata['username']} activated Byakugan to find your weak points.</font><br>";
$critran = rand(0, 100) > 90;
if ($odata['Diclonius'] == 100 && $odata['gender'] == 'Female')
{
$critran+=10;
print "<font color='green'> {$odata['username']}'s diclonius powers increased her critical chance!</font> <br>";
}
if ($critran > 90)
{
$dam = $dam*2;
print "<font color='red'><b>{$odata['username']} CRITS!</font></b><br>";
}
}

if($dam < 1) { $dam=1; }
$youdata['hp']-=$dam;
mysql_query("UPDATE users SET hp=hp-$dam WHERE userid=$userid",$c);
$ns=$_GET['nextstep']+1;
print "<font color=blue>{$ns}. Using his $wep {$odata['username']} hit you doing $dam damage ({$youdata['hp']})</font><br />\n";
$_SESSION['attacklog'].="<font color=blue>{$ns}. Using his $wep {$odata['username']} hit {$ir['username']} doing $dam damage ({$youdata['hp']})</font><br />\n";
}
else
{
$ns=$_GET['nextstep']+1;
print "<font color=blue>{$ns}. {$odata['username']} tried to hit you but missed ({$youdata['hp']})</font><br />\n";
$_SESSION['attacklog'].="<font color=blue>{$ns}. {$odata['username']} tried to hit {$ir['username']} but missed ({$youdata['hp']})</font><br />\n";
}
if($youdata['hp'] <= 0)
{
$youdata['hp']=0;
mysql_query("UPDATE users SET hp=0 WHERE userid=$userid",$c);
print "<form action='attacklost.php?ID={$_GET['ID']}' method='post'><input type='submit' value='Continue' />";
}
}
}
else if ($odata['hp'] < $odata['maxhp']/4)
{
print "You can only attack those who have at least 1/4 their max health";
$h->endpage();
exit;
}
else if ($youdata['energy'] < $youdata['maxenergy']/4)
{
print "You can only attack someone when you have 25% energy";
$h->endpage();
exit;
}
else if ($youdata['location'] != $odata['location'])
{
print "You can only attack someone in the same location!";
$h->endpage();
exit;
}
else
{
}
print "</td></tr>";
if($youdata['hp'] <= 0 || $odata['hp'] <= 0)
{
print "</table>";
}
else
{
print "<tr><td><img src='{$youdata['display_pic']}' width='150' height='150' alt='User Display Pic' title='User Display Pic' /><img src='anime-hp heart.png'>: {$youdata['hp']}/{$youdata['maxhp']}</td><td><img src='anime-hp heart.png'>: {$odata['hp']}/{$odata['maxhp']}<img src='{$odata['display_pic']}' width='150' height='150' alt='User Display Pic' title='User Display Pic' /></td></tr>";
$mw=mysql_query("SELECT iv.*,i.* FROM inventory iv LEFT JOIN items i ON iv.inv_itemid=i.itmid WHERE iv.inv_userid=$userid AND (i.itmtype = 3 || i.itmtype = 4)",$c);
print "<tr><br><br><td colspan=2 align='left'><br><B><font size='30'>Battle Menu:<br />";
while($r=mysql_fetch_array($mw))
{
if(!$_GET['nextstep']) { $ns=1; } else { $ns=$_GET['nextstep']+2; }
print "<a href='attack.php?nextstep=$ns&amp;ID={$_GET['ID']}&amp;wepid={$r['itmid']}'>{$r['itmname']}</a><br />";
}
print "</table>";
}
}

$h->endpage();
?>