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
$_GET['u'] = abs((int) $_GET['u']);
if (!$_GET['u'])
{
    print "Invalid use of file";
}
else
{
    $q =
            mysql_query(
                    "SELECT u.*,ud.* FROM users u LEFT JOIN userdna ud ON u.userid=ud.userid WHERE u.userid={$_GET['u']}",
                    $c);
    if (mysql_num_rows($q) == 0)
    {
        print 
                "Sorry, we could not find a user with that ID, check your source.";
    }
    else
    {
        $r = mysql_fetch_array($q);
        print "<h3>{$r['username']}'s DNA</h3> <br>";
     
      ?>
<table x:str border=0 cellpadding=0 cellspacing=0 width=256 style='border-collapse:
 collapse;table-layout:fixed;width:192pt'>
 <col width=64 span=4 style='width:48pt'>
 <tr height=17 style='height:12.75pt'>
  <td height=17 width=64 style='height:12.75pt;width:48pt'>Race</td>
  <td width=64 style='width:48pt'>Percentage</td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Saiyan</td>
  <td colspan=3 style='mso-ignore:colspan'> <?php print"{$r['Saiyan']}"; ?>%</td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Senju</td>
  <td colspan=3 style='mso-ignore:colspan'> <?php print"{$r['Senju']}"; ?>%</td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Uchiha</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['Uchiha']}"; ?>%</td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Hyuga</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['Hyuga']}"; ?>%</td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Diclonius</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['Diclonius']}"; ?>%</td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Demon</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['Demon']}"; ?>%</td>
 </tr>
 <tr height=17 style='height:12.75pt'>
  <td height=17 style='height:12.75pt'>Vampire</td>
  <td colspan=3 style='mso-ignore:colspan'><?php print" {$r['Vampire']}"; ?>%</td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
 </tr>
 <![endif]>
</table>
<?php
$next = $_GET['u']+1;
print "
<br>
They are currently level: {$r['level']} <br><br /><b><a href='viewuser.php?u={$_GET['u']}'>[Check profile]</a><br /><br>
[<a href ='dnabook.php?u={$next}'>Check next user</a>]<br>
<br>";

}
}

$h->endpage();
?>