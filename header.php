<?php
/*
MCCodes FREE
header.php Rev 1.1.0c
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



if (strpos($_SERVER['PHP_SELF'], "header.php") !== false)
{
    exit;
}

class headers
{


    function startheaders()
    {
       
         global $ir;
        echo <<<EOF
        
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/game.css" type="text/css" rel="stylesheet" />
<title>Battlerpg</title>
</head>
<body style='background-color: #C3C3C3;'>


EOF;
    }


    function userdata($ir, $lv, $fm, $cm, $dosessh = 1)
    {
        global $c, $userid;
        $ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
        mysql_query(
                "UPDATE users SET laston=" . time()
                        . ",lastip='$ip' WHERE userid=$userid", $c);
        if (!$ir['email'])
        {
            die(
                    "<body>Your account may be broken. Please mail help@yourgamename.com stating your username and player ID.");
        }
        if ($dosessh && isset($_SESSION['attacking']))
        {
            if ($_SESSION['attacking'] > 0)
            {
                print "You lost all your EXP for running from the fight.";
                mysql_query("UPDATE users SET exp=0 WHERE userid=$userid", $c);
                $_SESSION['attacking'] = 0;
            }
        }
        $enperc = (int) ($ir['energy'] / $ir['maxenergy'] * 100);
        $wiperc = (int) ($ir['will'] / $ir['maxwill'] * 100);
        $experc = (int) ($ir['exp'] / $ir['exp_needed'] * 100);
        $brperc = (int) ($ir['brave'] / $ir['maxbrave'] * 100);
        $hpperc = (int) ($ir['hp'] / $ir['maxhp'] * 100);
        $enopp = 100 - $enperc;
        $wiopp = 100 - $wiperc;
        $exopp = 100 - $experc;
        $bropp = 100 - $brperc;
        $hpopp = 100 - $hpperc;
        $d = "";
        $u = $ir['username'];
        if ($ir['donatordays'])
        {
         $color=$ir['color'];
            $u = "<font color=$color>{$ir['username']}</font>";
            $d =
                    "<img src='donator.gif' alt='Donator: {$ir['donatordays']} Days Left' title='Donator: {$ir['donatordays']} Days Left' />";
        }
        
        
        
print "<body bgcolor='#C3C3C3'>";
if ($ir['rankID'] < 8)
{
print"
<table width=100%><tr><td><img src='logo.png'></td>";
}
else
{
print"<table width=100%><tr><td><img src='logo.png'></td>";
}
print"
<td><img src='anime-chatbox.png'> <b> Name:</b> {$u} [{$ir['userid']}]<br />
<img src='anime-list.png'> <b> Race:</b> {$ir['race']}<br />
<img src='anime-crown.png'> <b> Class:</b> {$ir['rank']}<br />
<img src='anime-green letter s.png'> <b> Money:</b> {$fm}<br />";
if ($ir['level']<999)
{
print"
<img src='anime-helmet.png'> <b> Level: </b> {$ir['level']}<br />";
}
else
{
print"
<img src='anime-helmet.png'> <b> Level: <font color='darkred'>MAX</font></b><br>";
}
print"
<img src='anime-medal.png'> <b> Battle Points:</b> {$ir['batpts']}<br />
<img src='anime-letter S.png'> <b> AP:</b> {$ir['crystals']}<br />
[<a href='logout.php'>Emergency Logout</a>]</td><td>
<img src='anime-lightning.png'><b>Energy:</b> {$enperc}%<br />
<img src=bargreen.gif width=$enperc height=10><img src=barred.gif width=$enopp height=10><br />
<img src='anime-blue gem.png'><b>Will:</b> {$wiperc}%<br />
<img src=bargreen.gif width=$wiperc height=10><img src=barred.gif width=$wiopp height=10><br />
<img src='anime-red gem.png'><b>Brave:</b> {$ir['brave']}/{$ir['maxbrave']}<br />
<img src=bargreen.gif width=$brperc height=10><img src=barred.gif width=$bropp height=10><br />";
if ($ir['level']<999)
{
print"
<img src='anime-ap.png'><b>EXP:</b> {$experc}%<br />
<img src=bargreen.gif width=$experc height=10><img src=barred.gif width=$exopp height=10><br />";
}
print"
<img src='anime-hp heart.png'><b>Health:</b> {$hpperc}%<br />
<img src=bargreen.gif width=$hpperc height=10><img src=barred.gif width=$hpopp height=10></td></tr></table></div>
<center><b><u><a href='voting.php'>Vote</a></b></u> | 
";

if (get_request())
{
print "<b><u><a href='request.php'><font color='darkblue'>Request!</font></a></b></u> | ";
}

print"<b><u><a href='donator.php'><font color='green'>Donate</font></a></u></b></center><br />";
                
        $q = mysql_query("SELECT * FROM ads ORDER BY rand() LIMIT 1", $c);
        if (mysql_num_rows($q))
        {
            $r = mysql_fetch_array($q);
            print
                    "<center><a href='ad.php?ad={$r['adID']}'><img src='{$r['adIMG']}' alt='Paid Advertisement' /></a></center><br />";
            mysql_query(
                    "UPDATE ads SET adVIEWS=adVIEWS+1 WHERE adID={$r['adID']}",
                    $c);
        }
        print "<table width=100%><tr><td width=20% valign='top'>
";
        if ($ir['fedjail'])
        {
            $q =
                    mysql_query(
                            "SELECT * FROM fedjail WHERE fed_userid=$userid",
                            $c);
            $r = mysql_fetch_array($q);
            die(
                    "<b><font color=red size=+1>You have been put in the Battlerpg Federal Jail for {$r['fed_days']} day(s).<br />
Reason: {$r['fed_reason']}</font></b></body></html>");
        }
        if (file_exists('ipbans/' . $ip))
        {
            die(
                    "<b><font color=red size=+1>Your IP has been banned, there is no way around this.</font></b></body></html>");
        }
        
         echo <<<EOF
        <script type="text/javascript" src="https://cdn.goinstant.net/v1/platform.min.js"></script>
<script type="text/javascript" src="https://cdn.goinstant.net/widgets/chat/latest/chat.min.js"></script>
<!-- CSS is optional -->
<link rel="stylesheet" href="https://cdn.goinstant.net/widgets/chat/latest/chat.css" />
<script>
// Connect URL
var url = 'https://goinstant.net/238300606a39/Battlerp';
 
// Connect to GoInstant
goinstant.connect(url, {rooms: ['lobby']}, function(err, connection, lobbyRoom) {
   
  if (err) {
    console.log("Error connecting:", err);
    // Failed to connect to GoInstant
    return;
  }
   
   
  //Try and join the lobbyRoom
  var roomObj = connection.room('lobbyRoom');
    roomObj.join({ displayName: '[{$ir['userid']}] {$ir['username']}' , 
                   avatarUrl: ' {$ir['display_pic']}' }, function(err, yourRoom, userData) {
      if (err) {
        console.log("Error joining room:", err);
        // Failed to join room; clean up or retry.
        return;
      }
     
      // Joined the room.
       
      // Create a new instance of the Chat widget
      var chat = new goinstant.widgets.Chat({
        room: roomObj
      });
       
      // Initialize the Chat widget
      chat.initialize(function(err) {
        if (err) {
          throw err;
        }
        // Now it should render on the page
      });         
    });
});
</script>

EOF;
        
    }

    function menuarea()
    {
        include "mainmenu.php";
        global $ir, $c;
        print "</td><td valign='top'>
";  
    }

    function endpage()
    {
        $year = date('Y');
	         echo <<<EOF
<head>
        </td></tr></table>
        <div style='font-style: italic; text-align: center'>
      		Powered by codes made by Dabomstew & Exelight. Copyright &copy; {$year} Michael Martinez.
      		
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- battlerpg -->
<ins class="adsbygoogle"
     style="display:inline-block;width:320px;height:50px"
     data-ad-client="ca-pub-3447922182622936"
     data-ad-slot="4021943801"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

    	</div>
        </body>
       
		</html>
	
EOF;

    }

}
?>