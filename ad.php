<?php
/*
MCCodes FREE
ad.php Rev 1.1.0c
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

require "mysql.php";
$_GET['ad'] = abs(@intval($_GET['ad']));
mysql_query("UPDATE ads SET adCLICKS=adCLICKS+1 WHERE adID='{$_GET['ad']}'",
        $c);
$q = mysql_query("SELECT adURL FROM ads WHERE adID='{$_GET['ad']}'", $c);
if (mysql_num_rows($q) > 0)
{
    header("Location: " . mysql_result($q, 0, 0));
}
else
{
    die("Invalid ad.");
}
