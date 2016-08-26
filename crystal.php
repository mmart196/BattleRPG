<?php
/*
MCCodes FREE
inventory.php Rev 1.1.0c
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
if(!$_GET['ID'])
{
print"What are u doing?";
}
if($_GET['ID'])
{
$i =
            mysql_query(
                    "SELECT iv.*,i.*,it.* FROM inventory iv LEFT JOIN items i ON iv.inv_itemid=i.itmid LEFT JOIN itemtypes it ON i.itmtype=it.itmtypeid WHERE iv.inv_id={$_GET['ID']} AND iv.inv_userid=$userid",
                    $c);

 if (mysql_num_rows($i) == 0)
    {
        print "Invalid item ID";
    }
    else
    {
        $r = mysql_fetch_array($i);
        if ($r['itmname']=='Red Crystal')
        {
        	mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
           
           $gain = rand(1, 5);
           $type = rand(1, 7);
           if ($type==1) {
           $typ = 'Senju';
           mysql_query("UPDATE userdna SET Senju=Senju+$gain WHERE userid=$userid", $c);
           }
           if ($type==2) {
           $typ = 'Uchiha';
           mysql_query("UPDATE userdna SET Uchiha=Uchiha+$gain WHERE userid=$userid", $c);
           }
           if ($type==3) {
           $typ = 'Saiyan';
           mysql_query("UPDATE userdna SET Saiyan=Saiyan+$gain WHERE userid=$userid", $c);
           }
           if ($type==4) {
           $typ = 'Hyuga';
           mysql_query("UPDATE userdna SET Hyuga=Hyuga+$gain WHERE userid=$userid", $c);
           }
           if ($type==5) {
           $typ = 'Diclonius';
           mysql_query("UPDATE userdna SET Diclonius=Diclonius+$gain WHERE userid=$userid", $c);
           }
           if ($type==6) {
           $typ = 'Demon';
           mysql_query("UPDATE userdna SET Demon=Demon+$gain WHERE userid=$userid", $c);
           }
           if ($type==7) {
           $typ = 'Vampire';
           mysql_query("UPDATE userdna SET Vampire=Vampire+$gain WHERE userid=$userid", $c);
           }
$mydna = mysql_query("SELECT * FROM userdna WHERE userid=$userid", $c);
$mydna = mysql_fetch_array($mydna);
$M1 = ($mydna['Saiyan']);
$M2 = ($mydna['Hyuga']);
$M3 = ($mydna['Senju']);
$M4 = ($mydna['Uchiha']);
$M5 = ($mydna['Diclonius']);
$M6 = ($mydna['Demon']);
$M7 = ($mydna['Vampire']);
$Y1 = ($mydna['Saiyan']);
$Y2 = ($mydna['Hyuga']);
$Y3 = ($mydna['Senju']);
$Y4 = ($mydna['Uchiha']);
$Y5 = ($mydna['Diclonius']);
$Y6 = ($mydna['Demon']);
$Y7 = ($mydna['Vampire']);           
if($M1 > 100) {$M1=100;}
if($M2 > 100) {$M2=100;}
if($M3 > 100) {$M3=100;}
if($M4 > 100) {$M4=100;}
if($M5 > 100) {$M5=100;}
if($M6 > 100) {$M6=100;}
if($M7 > 100) {$M7=100;}
if($Y1 > 100) {$Y1=100;}
if($Y2 > 100) {$Y2=100;}
if($Y3 > 100) {$Y3=100;}
if($Y4 > 100) {$Y4=100;}
if($Y5 > 100) {$Y5=100;}
if($Y6 > 100) {$Y6=100;}
if($Y7 > 100) {$Y7=100;}

mysql_query("UPDATE userdna SET Saiyan=$M1, Uchiha=$M4, Hyuga=$M2, Senju=$M3, Diclonius=$M5, Demon=$M6, Vampire=$M7 WHERE userid=$userid", $c);
mysql_query("UPDATE userdna SET Saiyan=$Y1, Uchiha=$Y4, Hyuga=$Y2, Senju=$Y3, Diclonius=$Y5, Demon=$Y6, Vampire=$Y7 WHERE userid=$FIDin", $c);
           
           print "You used a Red Crystal! Your {$typ} increased by {$gain}%! ";
        
        }
}


$h->endpage();
}