<?php
/*
MCCodes FREE
docrime.php Rev 1.1.0c
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
$_GET['c'] = abs((int) $_GET['c']);
if (!$_GET['c'])
{
    print "Invalid crime";
}
else
{
    $q = mysql_query("SELECT * FROM crimes WHERE crimeID={$_GET['c']}", $c);
    if (mysql_num_rows($q) == 0)
    {
        echo 'Invalid crime.';
        $h->endpage();
        exit;
    }
    $r = mysql_fetch_array($q);
    if ($ir['brave'] < $r['crimeBRAVE'])
    {
        print "You do not have enough Brave to perform this quest.";
    }
    else
    {
        $ec =
                "\$sucrate="
                        . str_replace(array("LEVEL", "EXP", "WILL", "IQ"),
                                array($ir['level'], $ir['exp'], $ir['will'],
                                        $ir['IQ']), $r['crimePERCFORM']) . ";";
        eval($ec);
        print $r['crimeITEXT'];
        $ir['brave'] -= $r['crimeBRAVE'];
        mysql_query(
                "UPDATE users SET brave={$ir['brave']} WHERE userid=$userid",
                $c);
        if (rand(1, 100) <= $sucrate)
        {
            print
                    str_replace("{money}", $r['crimeSUCCESSMUNY'],
                            $r['crimeSTEXT']);
            $ir['money'] += $r['crimeSUCCESSMUNY'];
            mysql_query(
                    "UPDATE users SET money={$ir['money']} WHERE userid=$userid",
                    $c);
        }
        else
        {
            print $r['crimeFTEXT'];
        }
        print
                "<br /><a href='doquest.php?c={$_GET['c']}'>Try Again</a><br />
<a href='quests.php'>Quests</a>";
    }
}

$h->endpage();
