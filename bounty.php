<?php
/*
MCCodes FREE
search.php Rev 1.1.0c
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
if (!$_GET['name'])
{
print "<h3>Underground Assassins!</h3>
The user who kills any of the users posted below will receive half of the bounty!<br>
<b>Post victim:</b><form action='bounty.php' method='get'>
ID: <input type='text' name='name' /><br />
Bounty amount: <input type='text' name='amount' /><br />
<input type='submit' value='Post' /></form><hr />";

print "<table width=75% border=2><tr style='background:gray'><th>ID</th><th>Name</th><th>Bounty</th></tr>";
$poster = mysql_query("SELECT * FROM bounty", $c);
$ts=mysql_query("SELECT SUM(money) AS total FROM users", $c);
print"{$ts['total']}";
while($r=mysql_fetch_array($poster))
{
print "<tr><td>{$r['userid']}</td><td><a href='viewuser.php?u={$r['userid']}'>{$r['gangPREF']} {$r['username']} $d</a></td><td>\${$r['amount']}</td>";
print "</td></tr>";
}
print "</table>";
}
else
{
$_GET['amount'] = abs((int) $_GET['amount']);
$amount = $_GET['amount'];
$victim = $_GET['name'];
$check = mysql_query("SELECT * FROM bounty WHERE userid=$victim", $c);
$check = mysql_fetch_array($check);
$confirm = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE userid=$victim", $c));
$username = $confirm['username'];
if ($check['userid'] == $_GET['name'])
{
if ($ir['money'] < $amount)
{
print" You do not have that kind of money.";
}
else
{
mysql_query("UPDATE users SET money=money-$amount WHERE userid=$userid", $c);
mysql_query("UPDATE bounty SET amount=amount+$amount WHERE userid=$victim", $c);
print"{$username}'s bounty has been increased by {$amount}!";
}
}
else 
{
if ($confirm['userid'] == $victim)
{
if ($amount > 10000)
{
if ($ir['money'] < $amount)
{
print "You do not have that kind of money.";
}
else
{
mysql_query("UPDATE users SET money=money-$amount WHERE userid=$userid", $c);
mysql_query("INSERT INTO bounty VALUES('$victim', '$username', '$amount')", $c);
print"{$username} has been given a bounty!";
}
}
else
print "You need at least $10,000.";
}
else
{
print "OOPS something is wrong. You shouldnt be seeing this.";
}

}  


}


$h->endpage();