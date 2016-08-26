<?php
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
$chat = mysql_query("SELECT * FROM chat ORDER BY ID desc limit 10", $c);
print "<table width=75% border=3><tr style='background:gray'><th>Name</th><th>                    Text                 </th><th></tr>";
while ($r = mysql_fetch_array($chat))
{
 $sent = date('F j, Y, g:i:s a', $r['time']);
print "<tr><td>[{$r['userid']}]<a href='viewuser.php?u={$r['userid']}'> {$r['username']} <br> {$sent} </a></td><td>{$r['text']}</td></tr>";
}
print "
<b>Post:</b><form action='chatroom.php' method='get'>
Text: <input type='text' name='text'/><br />
<input type='submit' value='Post' /></form><hr />";
print" <br> <a href='chatroom.php'><font color='darkblue'>Refresh Chat</a> </font> <br>";
$username = $ir['username'];
$text = $_GET['text'];
if ($_GET['text'])
{
if ($ir['donatordays'] > 0) 
{
mysql_query("INSERT INTO chat VALUES(NULL,$userid, '$username', '$text', 1, " . time()
                    . ")", $c);
}
else
{
mysql_query("INSERT INTO chat VALUES(NULL, $userid, '$username', '$text', 0, " . time()
                    . ")", $c); 
}
}
?>