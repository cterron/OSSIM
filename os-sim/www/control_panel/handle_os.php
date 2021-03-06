<?php
/*****************************************************************************
*
*    License:
*
*   Copyright (c) 2003-2006 ossim.net
*   Copyright (c) 2007-2009 AlienVault
*   All rights reserved.
*
*   This package is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; version 2 dated June, 1991.
*   You may not use, modify or distribute this program under any other version
*   of the GNU General Public License.
*
*   This package is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this package; if not, write to the Free Software
*   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,
*   MA  02110-1301  USA
*
*
* On Debian GNU/Linux systems, the complete text of the GNU General
* Public License can be found in `/usr/share/common-licenses/GPL-2'.
*
* Otherwise you can read it here: http://www.gnu.org/licenses/gpl-2.0.txt
****************************************************************************/
/**
* Class and Function List:
* Function list:
* Classes list:
*/
require_once ('classes/Session.inc');
Session::logcheck("MenuEvents", "EventsAnomalies");
?>

<html>
<head>
  <title> <?php
echo gettext("OSSIM Framework"); ?> </title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
  <link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>
                                                                                
  <h1> <?php
echo gettext("OSSIM Framework"); ?> </h1>

<?php
require_once 'ossim_db.inc';
require_once 'classes/Host_os.inc';
require_once 'classes/Security.inc';
?>

<?php
$back = urldecode(GET("back"));
ossim_valid($back, OSS_ALPHA, OSS_PUNC, 'illegal:' . _("back"));
$db = new ossim_db();
$conn = $db->connect();
while (list($key, $val) = each($_GET)) {
    if (substr($key, 0, 3) != "ip,") continue;
    list($place_holder, $ip, $sensor, $date) = split(",", $key, 4);
    $ip = $val;
    if (preg_match("/^ack/i", $ip)) {
        $ip = ereg_replace("ack", "", $ip);
        $ip = ereg_replace("_", ".", $ip);
        $sensor = ereg_replace("_", ".", $sensor);
        $date = ereg_replace("_", " ", $date);
        ossim_valid($ip, OSS_IP_ADDR, 'illegal:' . _("ip"));
        ossim_valid($sensor, OSS_IP_ADDR, 'illegal:' . _("Sensor"));
        ossim_valid($date, OSS_ALPHA, OSS_PUNC, OSS_SPACE, 'illegal:' . _("Date"));
        if (ossim_error()) {
            die(ossim_error());
        }
        Host_os::ack_ign($conn, $ip, $date, $sensor, "ack");
    } elseif (preg_match("/^ignore/i", $ip)) {
        $ip = ereg_replace("ignore", "", $ip);
        $ip = ereg_replace("_", ".", $ip);
        $sensor = ereg_replace("_", ".", $sensor);
        $date = ereg_replace("_", " ", $date);
        ossim_valid($ip, OSS_IP_ADDR, 'illegal:' . _("ip"));
        ossim_valid($sensor, OSS_IP_ADDR, 'illegal:' . _("Sensor"));
        ossim_valid($date, OSS_ALPHA, OSS_PUNC, OSS_SPACE, 'illegal:' . _("Date"));
        if (ossim_error()) {
            die(ossim_error());
        }
        print _("Ignore").": $ip $date $sensor<br>";
        Host_os::ack_ign($conn, $ip, $date, $sensor, "ignore");
    }
}
$db->close($conn);
?>
    <p> <?php
echo gettext("Successfully Acked/Deleted"); ?> </p>
<?php
$location = "$back";
sleep(2);
echo "<script>
///history.go(-1);
window.location='$location';
</script>
";
?>


</body>
</html>

