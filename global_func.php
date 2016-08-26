<?php
/*
MCCodes FREE
global_func.php Rev 1.1.0c
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

if (strpos($_SERVER['PHP_SELF'], "global_func.php") !== false)
{
    exit;
}

function money_formatter($muny, $symb = '$')
{
    $moneys = "";
    $muny = (string) $muny;
    if (strlen($muny) <= 3)
    {
        return $symb . $muny;
    }
    $dun = 0;
    for ($i = strlen($muny); $i > 0; $i -= 1)
    {
        if ($dun % 3 == 0 && $dun > 0)
        {
            $moneys = "," . $moneys;
        }
        $dun += 1;
        $moneys = $muny[$i - 1] . $moneys;
    }
    return $symb . $moneys;
}

function itemtype_dropdown($connection, $ddname = "item_type", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM itemtypes ORDER BY itmtypename ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['itmtypeid']}'";
        if ($selected == $r['itmtypeid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['itmtypename']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function item_dropdown($connection, $ddname = "item", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM items ORDER BY itmname ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['itmid']}'";
        if ($selected == $r['itmid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['itmname']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function location_dropdown($connection, $ddname = "location", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM cities ORDER BY cityname ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['cityid']}'";
        if ($selected == $r['cityid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['cityname']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function shop_dropdown($connection, $ddname = "shop", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM shops ORDER BY shopNAME ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['shopID']}'";
        if ($selected == $r['shopID'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['shopNAME']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function user_dropdown($connection, $ddname = "user", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query("SELECT * FROM users ORDER BY username ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['userid']}'";
        if ($selected == $r['userid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['username']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function fed_user_dropdown($connection, $ddname = "user", $selected = -1)
{
    $ret = "<select name='$ddname' type='dropdown'>";
    $q =
            mysql_query(
                    "SELECT * FROM users WHERE fedjail=1 ORDER BY username ASC",
                    $connection);
    if ($selected == -1)
    {
        $first = 0;
    }
    else
    {
        $first = 1;
    }
    while ($r = mysql_fetch_array($q))
    {
        $ret .= "\n<option value='{$r['userid']}'";
        if ($selected == $r['userid'] || $first == 0)
        {
            $ret .= " selected='selected'";
            $first = 1;
        }
        $ret .= ">{$r['username']}</option>";
    }
    $ret .= "\n</select>";
    return $ret;
}

function event_add($userid, $text, $connection)
{
    $text = mysql_real_escape_string($text, $connection);
    mysql_query(
            "INSERT INTO events VALUES(NULL,$userid," . time() . ",0,'$text')",
            $connection) or die(mysql_error());
    return 1;
}

function mysql_escape($str)
{
    global $c;
    return mysql_real_escape_string($str, $c);
}

function check_level()
{
global $ir,$c,$userid;
$ir['exp_needed']=(int) ((($ir['level'])*2)^($ir['level']/7)+1);
while($ir['exp'] >= $ir['exp_needed'] AND $ir['level'] < 1000)
{
$expu=$ir['exp']-$ir['exp_needed'];
$ir['level']+=1;
$ir['exp']=$expu;
$ir['energy']+=2;
$ir['brave']+=2;
$ir['maxenergy']+=2;
$ir['maxbrave']+=2;
$ir['hp']+=75;
$ir['maxhp']+=75;
$ir['exp_needed']=(int) ((($ir['level'])*2)^($ir['level']/7)+1);
mysql_query("UPDATE users SET level=level+1,exp=$expu,energy=maxenergy+2,brave=brave+2,maxenergy=maxenergy+2,maxbrave=maxbrave+2,
hp=hp+50,maxhp=maxhp+50 where userid=$userid",$c);
}
if ($ir['level'] > 999)
{
mysql_query("UPDATE users SET level=999, maxbrave=2000, exp=0, energy=2000, maxenergy=2000, maxhp=80000  WHERE userid=$userid", $c);
}
}

function check_class()
{
global $ir, $c, $userid;
/* This is for lv 1-10 upgrade! */
if ($ir['rankID']== 1 || $ir['rankID']== 8 || $ir['rankID']== 14 || $ir['rankID']== 20) 
{
while($ir['level']>= 5)
{
if ($ir['rankID']== 1){mysql_query("UPDATE users SET rankID=2, rank='Genin' where userid=$userid",$c);}
if ($ir['rankID']== 8){mysql_query("UPDATE users SET rankID=9, rank='Shinigami' where userid=$userid",$c);}
if ($ir['rankID']== 14){mysql_query("UPDATE users SET rankID=15, rank='Gillian' where userid=$userid",$c);}
if ($ir['rankID']== 20){mysql_query("UPDATE users SET rankID=21, rank='Yellow Belt' where userid=$userid",$c);}
}
}
/* This is for lv 10+ upgrade! */
if ($ir['rankID']== 2 || $ir['rankID']== 15 || $ir['rankID']== 21) 
{
while($ir['level']>= 10)
{
if ($ir['rankID']== 2){mysql_query("UPDATE users SET rankID=3, rank='Chunnin' where userid=$userid",$c);}
if ($ir['rankID']== 15){mysql_query("UPDATE users SET rankID=16, rank='Adjuchas' where userid=$userid",$c);}
if ($ir['rankID']== 21){mysql_query("UPDATE users SET rankID=22, rank='Green Belt' where userid=$userid",$c);}
}
}
/* This is for lv 25+ upgrade! */
if ($ir['rankID']== 22) 
{
while($ir['level']>= 25)
{
if ($ir['rankID']== 22){mysql_query("UPDATE users SET rankID=23, rank='Blue Belt' where userid=$userid",$c);}
}
}
/* This is for lv 50+ upgrade! */
if ($ir['rankID']== 23) 
{
while($ir['level']>= 50)
{
if ($ir['rankID']== 23){mysql_query("UPDATE users SET rankID=24, rank='Red Belt' where userid=$userid",$c);}
}
}
/* This is for lv 100+ upgrade! */
if ($ir['rankID']== 3 || $ir['rankID']== 24) 
{
while($ir['level']>= 100)
{
if ($ir['rankID']== 3){mysql_query("UPDATE users SET rankID=4, rank='Jounin' where userid=$userid",$c);}
if ($ir['rankID']== 24){mysql_query("UPDATE users SET rankID=25, rank='Black Belt' where userid=$userid",$c);}
}
}
// This is for Max Level! 
if ($ir['rankID']== 4 || $ir['rankID']== 16) 
{
while($ir['level']==999)
{
//$top1 = mysql_query("SELECT TOP 5 * FROM users WHERE level >= 200",$c);
if ($ir['rankID']== 4){mysql_query("UPDATE users SET rankID=5, rank='Sannin' where userid=$userid",$c);}
if ($ir['rankID']== 16){mysql_query("UPDATE users SET rankID=17, rank='Vasto Lorde' where userid=$userid",$c);}
}
}
$dnacheck = mysql_fetch_array(mysql_query("SELECT * FROM userdna WHERE userid=$userid", $c));
$pic1 = $dnacheck['Saiyan'];
$pic2 = $dnacheck['Hyuga'];
$pic3 = $dnacheck['Senju'];
$pic4 = $dnacheck['Uchiha'];
$pic5 = $dnacheck['Diclonius'];
$pic6 = $dnacheck['Demon'];
$pic7 = $dnacheck['Vampire'];
if ($pic1+$pic2+$pic3+$pic4+$pic5+$pic6+$pic7 == 700)
{
mysql_query("UPDATE users SET race = 'Demi-God' WHERE userid=$userid", $c);
}
}


function stack_items()
{
global $ir, $userid, $c;
$item = 109;
$qty = 0;
$stacked=false;
while ($stacked == false)
{
$spec = mysql_num_rows(mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item", $c));

while ($spec>0)
{
$quat = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty<2 LIMIT 1", $c);
if (mysql_num_rows($quat)>0)
{
mysql_query("DELETE FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty<2 LIMIT 1", $c);
$qty++;
}
//$tests= mysql_num_rows(mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item", $c));
$qua = mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty>1 ORDER BY inv_qty LIMIT 1", $c);
if (mysql_num_rows($qua)>0)
{
$qua = mysql_fetch_array($qua);
$qty+=$qua['inv_qty'];
mysql_query("DELETE FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item AND inv_qty>1 ORDER BY inv_qty LIMIT 1", $c);
}
$spec = mysql_num_rows(mysql_query("SELECT * FROM inventory WHERE inv_userid=$userid AND inv_itemid=$item ", $c));
}
if ($qty>0){
mysql_query("INSERT INTO inventory VALUES (NULL, $item, $userid, $qty)", $c);}
$qty = 0;
$item++;
if ($item > 999)
{
$stacked = true;
}
}
}


function get_request()
{
global $ir, $userid, $c;
$req =
        mysql_query(
                "SELECT * FROM requests WHERE Sid=$userid", $c);   
$req = mysql_fetch_array($req);
if ($userid == $req['Sid'])
{
return true;
} 
}

function roll_dna()
{
global $ir, $userid, $c;
$dna = mysql_query("SELECT * FROM userdna WHERE userid=$userid", $c);
$dna = mysql_fetch_array($dna);
$dna = array_sum($dna)-($userid*2);
$pick1 = (rand(0, 200) / 100)* rand(0,1);
$pick2 = (rand(0, 200) / 100)* rand(0,1);
$pick3 = (rand(0, 200) / 100)* rand(0,1);
$pick4 = (rand(0, 200) / 100)* rand(0,1);
$pick5 = (rand(0, 200) / 100)* rand(0,1);
$pick6 = (rand(0, 200) / 100)* rand(0,1);
$pick7 = (rand(0, 200) / 100)* rand(0,1);
if ($ir['level'] > 4 && $dna == 0)
{
mysql_query("UPDATE userdna SET Saiyan = $pick1, Hyuga = $pick2, Senju = $pick3, Uchiha = $pick4, Diclonius = $pick5, Demon = $pick6, Vampire = $pick7 WHERE userid=$userid", $c);
}
}

function get_rank($stat, $mykey)
{
    global $ir, $userid, $c;
    $q =
            mysql_query(
                    "SELECT count(*) FROM userstats us LEFT JOIN users u ON us.userid=u.userid WHERE us.$mykey > $stat AND us.userid != $userid AND u.user_level != 0",
                    $c);
    return mysql_result($q, 0, 0) + 1;
}

/**
 * Given a password input given by the user and their actual details,
 * determine whether the password entered was correct.
 *
 * Note that password-salt systems don't require the extra md5() on the $input.
 * This is only here to ensure backwards compatibility - that is,
 * a v2 game can be upgraded to use the password salt system without having
 * previously used it, without resetting every user's password.
 *
 * @param string $input The input password given by the user.
 * 						Should be without slashes.
 * @param string $salt 	The user's unique pass salt
 * @param string $pass	The user's encrypted password
 *
 * @return boolean	true for equal, false for not (login failed etc)
 *
 */
function verify_user_password($input, $salt, $pass)
{
    return ($pass === encode_password($input, $salt));
}

/**
 * Given a password and a salt, encode them to the form which is stored in
 * the game's database.
 *
 * @param string $password 		The password to be encoded
 * @param string $salt			The user's unique pass salt
 * @param boolean $already_md5	Whether the specified password is already
 * 								a md5 hash. This would be true for legacy
 * 								v2 passwords.
 *
 * @return string	The resulting encoded password.
 */
function encode_password($password, $salt, $already_md5 = false)
{
    if (!$already_md5)
    {
        $password = md5($password);
    }
    return md5($salt . $password);
}

/**
 * Generate a salt to use to secure a user's password
 * from rainbow table attacks.
 *
 * @return string	The generated salt, 8 alphanumeric characters
 */
function generate_pass_salt()
{
    return substr(md5(microtime_float()), 0, 8);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

/**
 *
 * @return string The URL of the game.
 */
function determine_game_urlbase()
{
    $domain = $_SERVER['HTTP_HOST'];
    $turi = $_SERVER['REQUEST_URI'];
    $turiq = '';
    for ($t = strlen($turi) - 1; $t >= 0; $t--)
    {
        if ($turi[$t] != '/')
        {
            $turiq = $turi[$t] . $turiq;
        }
        else
        {
            break;
        }
    }
    $turiq = '/' . $turiq;
    if ($turiq == '/')
    {
        $domain .= substr($turi, 0, -1);
    }
    else
    {
        $domain .= str_replace($turiq, '', $turi);
    }
    return $domain;
}

/**
 * Get the file size in bytes of a remote file, if we can.
 *
 * @param string $url	The url to the file
 *
 * @return int			The file's size in bytes, or 0 if we could
 * 						not determine its size.
 */

function get_filesize_remote($url)
{
    // Retrieve headers
    if (strlen($url) < 8)
    {
        return 0; // no file
    }
    $is_ssl = false;
    if (substr($url, 0, 7) == 'http://')
    {
        $port = 80;
    }
    else if (substr($url, 0, 8) == 'https://' && extension_loaded('openssl'))
    {
        $port = 443;
        $is_ssl = true;
    }
    else
    {
        return 0; // bad protocol
    }
    // Break up url
    $url_parts = explode('/', $url);
    $host = $url_parts[2];
    unset($url_parts[2]);
    unset($url_parts[1]);
    unset($url_parts[0]);
    $path = '/' . implode('/', $url_parts);
    if (strpos($host, ':') !== false)
    {
        $host_parts = explode(':', $host);
        if (count($host_parts) == 2 && ctype_digit($host_parts[1]))
        {
            $port = (int) $host_parts[1];
            $host = $host_parts[0];
        }
        else
        {
            return 0; // malformed host
        }
    }
    $request =
            "HEAD {$path} HTTP/1.1\r\n" . "Host: {$host}\r\n"
                    . "Connection: Close\r\n\r\n";
    $fh = fsockopen(($is_ssl ? 'ssl://' : '') . $host, $port);
    if ($fh === false)
    {
        return 0;
    }
    fwrite($fh, $request);
    $headers = array();
    $total_loaded = 0;
    while (!feof($fh) && $line = fgets($fh, 1024))
    {
        if ($line == "\r\n")
        {
            break;
        }
        if (strpos($line, ':') !== false)
        {
            list($key, $val) = explode(':', $line, 2);
            $headers[strtolower($key)] = trim($val);
        }
        else
        {
            $headers[] = strtolower($line);
        }
        $total_loaded += strlen($line);
        if ($total_loaded > 50000)
        {
            // Stop loading garbage!
            break;
        }
    }
    fclose($fh);
    if (!isset($headers['content-length']))
    {
        return 0;
    }
    return (int) $headers['content-length'];
}
// GPC fix: added in 1.1.1
if (version_compare(PHP_VERSION, '5.4.0-dev') < 0
        && function_exists('get_magic_quotes_gpc'))
{
    $_core_gpc_on = get_magic_quotes_gpc();
}
else
{
    $_core_gpc_on = false;
}
if (!$_core_gpc_on)
{
    foreach ($_POST as $k => $v)
    {
        $_POST[$k] = addslashes($v);
    }
    foreach ($_GET as $k => $v)
    {
        $_GET[$k] = addslashes($v);
    }
}
// Error reporting we want
@error_reporting(E_ALL & ~E_NOTICE);
// Tidy?
if (class_exists('tidy'))
{

    function tidy_test()
    {
        $html = ob_get_clean();

        // Specify configuration
        $config =
                array('indent' => true, 'output-xhtml' => true, 'wrap' => 200);

        // Tidy
        $tidy = new tidy;
        $tidy->parseString($html, $config, 'latin1');
        $tidy->cleanRepair();

        // Output
        echo $tidy;
    }
    ob_start();
    register_shutdown_function('tidy_test');
}