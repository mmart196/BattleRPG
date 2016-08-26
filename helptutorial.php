<?php
/*
MCCodes FREE
helptutorial.php Rev 1.1.0c
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
print
        <<<EOF
<h1>BattleRPG Tutorial</h1>
<br />
<br />
<p>Welcome to the Battlerpg Tutorial, we hope that this guide will help you to better
understand the game.<p>
<br />
<p>In Battlerpg, you are free to choose your own path. You can protect the weak, or
exploit their weakness. Spend your money to help your friends, or horde it, they can take
care of themselves.</p>

<h1>SHORT GUIDE TO TRAINING:</h1>
<br>
<p>To train you use energy and will. The higher your will the more you gain from training. Will is dependent on your property.
<br>The bigger your property the more will you have. If you wanna buy a property go to explore then estate agent.
<br>You also need energy to train. You use up energy when you train. To regain it back, you must buy food from the shops.
<br>When you level up, you get 100% energy and stat boosts. 
<br></p>

<h1>IMPORTANT STUFF</h1>
<br>
<p>Do your daily reward, check item market, do quests! Once ur level 5, you can roll for dna. At level 10 you can rebirth. You must have 100% of a DNA
to gain its benefits! Make use of the gachapon if you have lots of crystals! You must have a skill to fight! Killing someone in battle arena awards you with
1 battle point! To rebirth you must send a rebirth request to someone of opposite gender! <br> To become a pirate, you must eat a devil fruit! Pirates start at level 100!<br>
To become a duelist, you must have all millenium items! Duelist start at level 200.</p>



<br />
<h3>LONG Guide</h3>
<font color='lightblue'><a href="#general">General</a>
<br />
<a href="#explore">Explore</a>
<br />
<a href="#stat">Training</a>
<br />
<a href="#rebirth">Rebirth</a>
<br />
<a href="#fight">Attacking</a>
<br />
<a href="#preferences">Preferences</a>
</font>
<a name="general"><h4>General</h4></a>
<br />
<p><u>Personal Info and Status Bars</u></p>
<p>In the top right corner of the screen is your personal information. This shows your current
name, amount of cash, level, and number of crystals. To the right of your personal info is your
status bars. These show your current energy, will, brave, experience, and health.
1)Energy is used for training and attacking.
2)Will determines the effectiveness of your training and quest performance.
3)Brave is used to do quests, different quests take more brave to do, these quests are harder to succeed at
so be careful not to try them to soon.
4)Experience shows how close you are to leveling up.
5)Health shows how much health you have remaining. You lose this if you're hit in a fight.
<br />
<p><u>Stats:</u></p>
<p>There are 5 types of stats used on Battlerpg: Strength, Agility, and Guard!
1)Strength determines how much damage you do in battle,
2)Agility is used to determine your hit rate in battle,
3)Guard reduces the amount of damage done to you when you are hit,
</p>
<br />

<p><u>Sidebar</u></p>
<p>The sidebar shows much of the things you are able to do in Battlerpg.</p>
<ol>
<li>The Home link will bring you to your homepage.</li>
<li>Items will bring you to your item page.</li>
<li>Explore brings up a list of places that you can go on Battlerpg.</li>
<li>Events displays the number of new events, and when clicked tells you what they are.</li>
<li>Mailbox will display any new messages you have received.</li>
<li>Gym is where you go to train your fighting stats.</li>
<li>Quests will let you select which Quest you want to do.</li>
<li>Local School will let you take education classes.</li>
<li>Search allows you to find other players by their name or their ID.</li>
<li>Preferences will bring you the the Preferences page.</li>
<li>Player Report is used to report players that have broken the rules of the game.</li>
<li>My Profile shows you your profile.</li>

</ol>
<br />
<a name="explore"><h4>Exploring</h4></a>
<ol>
<li>Shops: Here you can buy everything from med supplies, to weapons to make your enemy need meds.</li>
<li>Item Market: You can go and see what people are selling here.</li>
<li>Crystal Market: Come here to buy or sell crystals.</li>
<li>Travel Agency: This will bring you to new towns with different equipment, keep in mind you can only fight someone in your town.</li>
<li>Estate Agent:Go here to buy yourself a new house. It increases your max will! REALLY IMPORTANT! The bigger your will the faster you level and better performance in quests!</li>
<li>City Bank: Here you can deposit your money. You must first open an account for 50K, and pay a fee for depositing.</li>
<li>Federal Jail: Where all the suspected cheaters on the game go. If you're in here without cheating, mail fedjail@monocountry.com</li>
<li>Slot Machines: Go here to make your fortune, or lose your shirt.</li>
<li>User List: Shows a list of all the players on the game.</li>
<li>Battlerpg Staff: A list of all the staff on Battlerpg.</li>
<li>Hall of Fame: Shows the top players in various fields.</li>
<li>Country Stats: A list of various statistics about the game.</li>
<li>Users Online: Shows which players have acted last.</li>
<li>Battle Arena: You can fight here for Battle points. You use battle points to get rewards!.</li>
<li>Rewards: You can get a daily reward and redeem your battle points!</li>
<li>Crystal temple: Trade your crystals for various things.</li>
</ol>
<br />
<a name="stat"><h4>Training</h4></a>
<br />
<p><u>Gym</u></p>
<p>To use the gym, type in the number of times you want to train, select the stat to train and click ok. The next screen will tell
you how much of that stat you gained, and what your total in that stat is.</p>
<br />
<p><u>Quests</u></p>
<p>Go to the crime screen and select the quest you want to do.</p>
<br />

<p><u>School (NOT WORKING AT THE MOMENT)</u></p>
<p>School offers courses that will raise your stats over a certain period of time</p>
<br />
<p><u>Attacking</u></p>
<p>Attacking will gain you experience when you win, but you lose experience if you lose. The amount of experience depends on the comparative
strength of your enemy, if they are much weaker, you won't get much experience</p>
<br />

<br />
<a name="rebirth"><h4>Rebirth</h4>
<br/> 
<p>
To rebirth, you must be level 10 and the person you want to mate with must be level 10. Both of you must be of the opposite gender and to
send a request you must have a Rebirth crystal in your inventory. If you are not of the same gender you can change gender with Sex change but 
you need a gender crystal. When the other person accepts, you both combine genetic information and turn to level 1 but you maintain the same "trainable" stats.
<br />

<h4>Genetic Information</h4>
</br>
<p>At level 5, you can see your genetic info. 
If you have at least 10% Saiyan you can turn into SS1, 25% Saiyan: SS2, 50% Saiyan: SS3.
If you have 10% Vampire you have 5% lifesteal, 25% Vampire: 10% lifesteal, 50% Vampire: 15% lifesteal .
If you have 10% Diclonius and you are a girl, then your Crit Strike is 2.15x, 25% = 2.35x, 50% = 2.5%.
If you have 50% Senju and 50% Uchiha, you will gain rinnegan which will allow you to fuse all tailed beast to 10-tailed and more stuff(UNDER CONSTRUCTION)
If you have 10% Demon, you have access to 3rd Tier Demon world. 25% =2nd Tier, 50% 1st Tier.
If you have 10% Hyuga, you have 5%+ crit chance, 25% = 10%+, 50% = 15%+.
SENJU AND UCHIHA ARE UNDER CONSTRUCTION
<br />
<a name="fight"><h4>Attacking</h4>

<br />
<p>Attacking is a good way to exert your superiority over those weaker than you and you get Battle points! In order to attack you need 50% energy,
and should have a weapon. When you win a fight you will get a percentage of experience depending on how much stronger you are compared to the
person you are attacking. Make sure that you really want to fight the person, because once you start you can't stop until one of you loses.
When you start a fight, you will have the option of using any weapon that you currently have in your items page.<br />
<a name="preferences"><h4>Preferences</h4></a>
<br />
<p><u>Sex Change</u></p>
<p>This will allow you to change from male to female and back for free, try finding that deal in the real world!</p>
<br />
<p><u>Password Change</u></p>

<p>The place to change your password, you should do this often to avoid having someone use your account if they crack your password</p>
<p><u>Name Change</u></p>
<p>Go here to change your name, remember that you're ID stays the same, so you can't use this to avoid consequences of your actions</p>
<br />
<p><u>Change Display Pic</u></p>
<p>Here you can change the display picture in your profile, it will automatically refit the picture to 150x150. Don't post anything offensive
or you may be federal jailed.</p>
<br />
EOF;
$h->endpage();