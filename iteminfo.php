<?php
/*
MCCodes FREE
iteminfo.php Rev 1.1.0c
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
//look up item
$_GET['ID'] = abs((int) $_GET['ID']);
$itmid = $_GET['ID'];
if (!$itmid)
{
    print "Invalid item ID";
}
else
{
    $q =
            mysql_query(
                    "SELECT i.*,it.* FROM items i LEFT JOIN itemtypes it ON i.itmtype=itmtypeid WHERE i.itmid=$itmid LIMIT 1",
                    $c);
    if (!mysql_num_rows($q))
    {
        print "Invalid item ID";
    }
    else
    {
        $id = mysql_fetch_array($q);
        print
                "<table width=75%><tr style='background: gray;'><th colspan=2><b>Looking up info on {$id['itmname']}</b></th></table>
<table width=75%><tr bgcolor=#dfdfdf><th colspan=2>The <b>{$id['itmname']}</b> is a/an {$id['itmtypename']} Item - <b>{$id['itmdesc']}</b></th></table><br />
<table width=75%><tr style='background: gray;'><th colspan=2>Item Info</th></tr><tr style='background:gray'><th>Item Buy Price</th><th>Item Sell Price</th></tr><tr><td>";
        if ($id['itmbuyprice'])
        {
            print money_formatter($id['itmbuyprice']);
        }
        else
        {
            print "N/A";
        }
        print "</td><td>";
        if ($id['itmsellprice'])
        {
            print money_formatter($id['itmsellprice']);
        }
        else
        {
            print "N/A</td></tr></table>";
        }
    }
}
$h->endpage();
