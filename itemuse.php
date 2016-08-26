<?php
/*
MCCodes FREE
itemuse.php Rev 1.1.0c
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
$_GET['ID'] = abs((int) $_GET['ID']);
//Food
if (!$_GET['ID'] && !$_GET['gacha'])
{
    print "Invalid use of file";
}
else
{
if ($_GET['ID'])
{
    $i =
            mysql_query(
                    "SELECT iv.*,i.*,it.* FROM inventory iv LEFT JOIN items i ON iv.inv_itemid=i.itmid LEFT JOIN itemtypes it ON i.itmtype=it.itmtypeid WHERE iv.inv_id={$_GET['ID']} AND iv.inv_userid=$userid",
                    $c);
                    }
                    else if ($_GET['gacha'])
                    {
                     $i =
            mysql_query(
                    "SELECT iv.*,i.*,it.* FROM inventory iv LEFT JOIN items i ON iv.inv_itemid=i.itmid LEFT JOIN itemtypes it ON i.itmtype=it.itmtypeid WHERE iv.inv_id={$_GET['gacha']} AND iv.inv_userid=$userid",
                    $c);
                    }
    if (mysql_num_rows($i) == 0)
    {
        print "Invalid item ID";
    }
    else
    {
        $r = mysql_fetch_array($i);
        if ($r['itmtypename'] == 'Food')
        {
            $f =
                    mysql_query(
                            "SELECT * FROM food WHERE item_id={$r['itmid']}",
                            $c);
            $fr = mysql_fetch_array($f);
            mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            mysql_query(
                    "UPDATE users SET energy=energy+{$fr['energy']} WHERE userid=$userid");
            mysql_query(
                    "UPDATE users SET energy=maxenergy WHERE energy > maxenergy");
            print
                    "You cram a {$r['itmname']} into your mouth. You feel a bit of energy coming back to you.";
        }
        else if ($r['itmtypename'] == 'Medical')
        {
            $f =
                    mysql_query(
                            "SELECT * FROM medical WHERE item_id={$r['itmid']}",
                            $c);
            $fr = mysql_fetch_array($f);
            mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            mysql_query(
                    "UPDATE users SET hp=hp+{$fr['health']} WHERE userid=$userid");
            mysql_query("UPDATE users SET hp=maxhp WHERE hp > maxhp");
            if ($r['itmname'] == 'Full Restore')
            {
                mysql_query(
                        "UPDATE users SET energy=maxenergy,will=maxwill,brave=maxbrave WHERE userid=$userid",
                        $c);
            }
            if ($r['itmname'] == 'Will Potion')
            {
                mysql_query(
                        "UPDATE users SET will=maxwill WHERE userid=$userid",
                        $c);
            }
            print
                    "You spray a {$r['itmname']} into your mouth. You feel a bit of health coming back to you.";
        }
        
        
        else if ($r['itmname']=='Rare Candy')
        {
            mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            mysql_query(
                    "UPDATE users SET level=level+1, maxbrave=maxbrave+2, brave=brave+2, maxenergy=maxenergy+2, energy=maxenergy, maxhp=maxhp+75, hp=hp+75 WHERE userid=$userid", $c);
           print "You used a rare candy and leveled up!";
        }
        else if ($r['itmname']=='Senzu Bean')
        {
            mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            mysql_query(
                    "UPDATE users SET hp=maxhp, energy=maxenergy, will=maxwill, brave=maxbrave WHERE userid=$userid");
           print "You ate a Senzu Bean and feel completely restored!";
        }
        else if ($r['itmname']=='Devil Fruit')
        {
        $checkers = mysql_query("SELECT * FROM unlockable WHERE userid=$userid", $c);
        
        if (mysql_fetch_array($checkers) != '26')
        {
        	mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            mysql_query(
                    "INSERT INTO unlockable VALUES($userid, 26)");
           print "You unlocked the pirate class! Next time you rebirth, you can choose to rebirth as a Pirate!";
        }
        else
        {
        print "You already unlocked pirate before.";
        }
        }
          else if ($r['itmname']=='Millennium Puzzle')
        {
        $checkers = mysql_query("SELECT * FROM unlockable WHERE userid=$userid AND unlocked=37", $c);
        $checkers = mysql_fetch_array($checkers);
        if ( $checkers['unlocked'] != 37)
        {
        $p1 = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=136", $c);
        
        $p2 = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=137", $c);
        
        $p3 = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=138", $c);
        
        $p4 = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=139", $c);
        
        $p5 = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=140", $c);
       
        $p6 = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=141", $c);
        
        $p7 = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=142", $c);
        if (mysql_num_rows($p1) != 0)
        {
        if (mysql_num_rows($p2) != 0)
        {
        if (mysql_num_rows($p3) != 0)
        {
        if (mysql_num_rows($p4) != 0)
        {
        if (mysql_num_rows($p5) != 0)
        {
        if (mysql_num_rows($p6) != 0)
        {
        if (mysql_num_rows($p7) != 0)
        {
        
        	mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
                    mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_userid=$userid AND inv_itemid=137", $c);
                    mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_userid=$userid AND inv_itemid=138", $c);
                    mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_userid=$userid AND inv_itemid=139", $c);
                    mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_userid=$userid AND inv_itemid=140", $c);
                    mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_userid=$userid AND inv_itemid=141", $c);
                    mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_userid=$userid AND inv_itemid=142", $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            mysql_query(
                    "INSERT INTO unlockable VALUES($userid, 37)");
           print "You unlocked the Duelist class! Next time you rebirth, you can choose to rebirth as a Duelist! Start at level 200!";
        }
        else
        {
        print "You are missing a piece.";
        }
        }
        else
        {
        print "You are missing a piece.";
        }
        }
        else
        {
        print "You are missing a piece.";
        }
        }
        else
        {
        print "You are missing a piece.";
        }
        }
        else
         {
        print "You are missing a piece.";
        }
        }
        else
        {
        print "You are missing a piece.";
        }
        }
        else
         {
        print "You are missing a piece.";
        }
        }
        else
        {
        print "You already unlocked Duelist before.";
        }
        }
       
        else if ($r['itmname']=='Rare Bag')
        {
        mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
        $itrec = rand(135,142);
        $qunt = rand(1,5);
        $itemmmd = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE itmid=$itrec", $c));
	mysql_query("INSERT INTO inventory VALUES (NULL, $itrec, $userid, $qunt)", $c);
	print "You recieved {$qunt} {$itemmmd['itmname']}(s)!";
        }
        
        elseif ($_GET['gacha'])
        {
        $checkoo = mysql_fetch_array(mysql_query("SELECT * FROM inventory WHERE inv_id={$_GET['gacha']}", $c));
        if ($checkoo['inv_itemid'] == 109)
        {
        mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['gacha']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            $poster = mysql_query("SELECT * FROM gachapon WHERE userid=$userid", $c);
           
            if (mysql_num_rows($poster) == 0)
            {
            print"A Gender Crystal has been added to the Gachapon";
            mysql_query("INSERT INTO gachapon VALUES ($userid, 1)", $c);
            }
            else
            {
            print"A Gender Crystal has been added to the Gachapon";
            mysql_query("UPDATE gachapon SET amount=amount+1 WHERE userid=$userid", $c);
            }
        }
        elseif ($checkoo['inv_itemid'] == 110)
        {
         mysql_query("UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['gacha']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            $poster = mysql_query("SELECT * FROM gachapon WHERE userid=$userid", $c);
           
            if (mysql_num_rows($poster) == 0)
            {
            print"A Red Crystal has been added to the Gachapon";
            mysql_query("INSERT INTO gachapon VALUES ($userid, 1)", $c);
            }
            else
            {
            print"A Red Crystal has been added to the Gachapon";
            mysql_query("UPDATE gachapon SET amount=amount+1 WHERE userid=$userid", $c);
            }
        }
        else
        print "You cant refresh for a individual item, go back to your items.";
        }
         elseif ($r['itmname']=='Gender Crystal')
        {
        mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            if ($ir['gender']=='Male')
            {mysql_query(
                    "UPDATE users SET gender='Female' WHERE userid=$userid", $c);
                    print"You are now a Female!";
           }
           elseif ($ir['gender']=='Female')
           {
           mysql_query(
                    "UPDATE users SET gender='Male' WHERE userid=$userid", $c);
           print "You are now a Male!";
        }
        }
        elseif ($r['itmname']=='Mega Rare Candy')
        {
         mysql_query(
                    "UPDATE inventory SET inv_qty=inv_qty-1 WHERE inv_id={$_GET['ID']}",
                    $c);
            mysql_query("DELETE FROM inventory WHERE inv_qty=0", $c);
            mysql_query(
                    "UPDATE users SET level=level+100, maxbrave=maxbrave+200, brave=brave+200, maxenergy=maxenergy+200, energy=maxenergy, maxhp=maxhp+7500, hp=hp+7500 WHERE userid=$userid", $c);
           print "You used a Mega Rare candy and leveled up 100 times!!";
        }
        
        else
        {
            print "You cannot use this item.";
        }
    }
}

$h->endpage();