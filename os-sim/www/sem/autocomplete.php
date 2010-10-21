<?php 
/*****************************************************************************
*
*    License:
*
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
require_once ('classes/Security.inc');
require_once ('classes/Host.inc');
require_once ('classes/Net.inc');
require_once ('ossim_db.inc');
$db = new ossim_db();
$conn = $db->connect();

$str = GET('str');
$qstr = quotemeta($str);

ossim_valid($str, OSS_DIGIT, OSS_SPACE, OSS_PUNC, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("str"));
if (ossim_error()) {
    die(ossim_error());
}

$data = array();
$top = 10;
if (trim($str) != "") {
	list($sensors, $hosts) = Host::get_ips_and_hostname($conn);
	$nets = Net::get_list($conn);
	
	foreach ($sensors as $ip=>$name) {
		if ((preg_match("/^$qstr/i",$name) || preg_match("/^$qstr/i",$ip)) && count($data) < $top) {
			$data[] = array("name"=>"<b>sensor</b>:$name");
		}
	}
	foreach ($hosts as $ip=>$name) {
		if ((preg_match("/^$qstr/i",$name) || preg_match("/^$qstr/i",$ip)) && count($data) < $top) {
			$data[] = array("name"=>"<b>source</b>:$name");
			$data[] = array("name"=>"<b>destination</b>:$name");
		}
	}
	foreach ($nets as $net) {
		$ip = $net->get_ips();
		$name = $net->get_name();
		if ((preg_match("/^$qstr/i",$name) || preg_match("/^$qstr/i",$ip)) && count($data) < $top) {
			$data[] = array("name"=>"<b>source</b>:$name");
			$data[] = array("name"=>"<b>destination</b>:$name");
		}
	}
}
//echo JSON to page  
$response = "(" . json_encode($data) . ")";  
echo $response;  
?>