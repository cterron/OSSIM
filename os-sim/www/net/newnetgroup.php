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
require_once ('classes/Security.inc');
require_once ('ossim_db.inc');
require_once ('classes/Net.inc');
require_once ('classes/Net_group.inc');
require_once ('classes/Net_group_scan.inc');
require_once ('classes/Util.inc');

Session::logcheck("MenuPolicy", "PolicyNetworks");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title> <?php echo gettext("OSSIM Framework"); ?> </title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Pragma" content="no-cache">
	<link type="text/css" rel="stylesheet" href="../style/style.css"/>
</head>
                                                                                
  <h1> <?php echo gettext("New network group"); ?> </h1>

<?php

$descr = POST('descr');
$net_group_name = POST('name');
$threshold_a = POST('threshold_a');
$threshold_c = POST('threshold_c');
$rrd_profile = POST('rrd_profile');
$nnets = POST('nnets');

ossim_valid($net_group_name, OSS_ALPHA, OSS_SPACE, OSS_PUNC, OSS_SPACE, 'illegal:' . _("Net name"));
ossim_valid($threshold_a, OSS_DIGIT, 'illegal:' . _("threshold_a"));
ossim_valid($threshold_c, OSS_DIGIT, 'illegal:' . _("threshold_c"));
ossim_valid($nnets, OSS_DIGIT, OSS_NULLABLE, 'illegal:' . _("nnets"));
ossim_valid($rrd_profile, OSS_ALPHA, OSS_NULLABLE, OSS_SPACE, OSS_PUNC, 'illegal:' . _("Net name"));
ossim_valid($descr, OSS_ALPHA, OSS_NULLABLE, OSS_SPACE, OSS_PUNC, OSS_AT, 'illegal:' . _("Description"));
if (ossim_error()) {
    die(ossim_error());
}
if (POST('insert'))
 {
    $nets = array();
    for ($i = 1; $i <= $nnets; $i++)
	{
        $name = "mboxs" . $i;
        ossim_valid(POST("$name") , OSS_ALPHA, OSS_NULLABLE, OSS_PUNC, OSS_SPACE, 'illegal:' . _("$name"));
        if (ossim_error()) {
            die(ossim_error());
        }
        $name_aux = POST("$name");
        if (!empty($name_aux)) $nets[] = POST("$name");
    }
   
    $db = new ossim_db();
    $conn = $db->connect();
    Net_group::insert($conn, $net_group_name, $threshold_c, $threshold_a, $rrd_profile, $nets, $descr);
    if (POST('nessus')) {
        Net_group_scan::insert($conn, $net_group_name, 3001, 0);
    }
    $db->close($conn);
    Util::clean_json_cache_files("(policy|vulnmeter|hostgroup)");
}
?>
    <p> <?php echo gettext("Network group succesfully inserted"); ?> </p>
    <? if ($_SESSION["menu_sopc"]=="Network groups") { ?><script>document.location.href="netgroup.php"</script><? } ?>

	</body>
</html>

