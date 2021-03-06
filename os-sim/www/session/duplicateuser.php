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
require_once ('classes/User_config.inc');
Session::logcheck("MenuConfiguration", "ConfigurationUsers");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title> <?php echo gettext("OSSIM Framework"); ?> </title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Pragma" content="no-cache"/>
	<link rel="stylesheet" type="text/css" href="../style/style.css"/>
</head>
<body>

  <h1> <?php echo _("New User"); ?> </h1>

<?php

$user        = POST('user');
$pass1       = POST('pass1');
$pass2       = POST('pass2');
$name        = POST('name');
$email       = POST('email');
$nnets       = POST('nnets');
$nsensors    = POST('nsensors');
$tzone		 = POST('tzone');
$company     = POST('company');
$department  = POST('department');
$language    = POST('language');
$first_login = POST('first_login');

ossim_valid($first_login, OSS_DIGIT, 'illegal:' . _("First Login"));
ossim_valid($user, OSS_USER, 'illegal:' . _("User name"));
ossim_valid($name, OSS_ALPHA, OSS_PUNC, OSS_AT, OSS_SPACE, 'illegal:' . _("Name"));
ossim_valid($email, OSS_MAIL_ADDR, OSS_NULLABLE, 'illegal:' . _("E-mail"));
ossim_valid($nnets, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("Nets"));
ossim_valid($nsensors, OSS_ALPHA, OSS_NULLABLE, 'illegal:' . _("Sensors"));
ossim_valid($company, OSS_ALPHA, OSS_PUNC, OSS_AT, OSS_NULLABLE, 'illegal:' . _("Company"));
ossim_valid($tzone, OSS_ALPHA, OSS_SCORE, '\/', '\+', 'illegal:' . _("Timezone"));
ossim_valid($department, OSS_ALPHA, OSS_PUNC, OSS_AT, OSS_NULLABLE, 'illegal:' . _("Department"));
ossim_valid($language, OSS_ALPHA, OSS_PUNC, OSS_AT, OSS_NULLABLE, 'illegal:' . _("Language"));
ossim_valid($pass1, OSS_ALPHA, OSS_DIGIT, OSS_PUNC_EXT, 'illegal:' . _("Pass1"));
ossim_valid($pass2, OSS_ALPHA, OSS_DIGIT, OSS_PUNC_EXT, 'illegal:' . _("Pass2"));


if (ossim_error()) {
    die(ossim_error());
}

if (!Session::am_i_admin()) 
{
    require_once ("ossim_error.inc");
    $error = new OssimError();
    $error->display("ONLY_ADMIN");
}

/* check passwords */
elseif (0 != strcmp($pass1, $pass2)) 
{
    require_once ("ossim_error.inc");
    $error = new OssimError();
    $error->display("PASSWORDS_MISMATCH");
}
/* check OK, insert into DB */
elseif (POST("insert")) 
{
    require_once ('ossim_db.inc');
    require_once ('ossim_acl.inc');
    require_once ('classes/Net.inc');
    $perms = Array();
	
    foreach($ACL_MAIN_MENU as $menus) 
	{
        foreach($menus as $key => $menu) {
            if (POST($key) == "on") $perms[$key] = true;
            else $perms[$key] = false;
        }
    }
    $db = new ossim_db();
    $conn = $db->connect();
	
	/*
    if ($copy_panels == 1) {
        User_config::copy_panel($conn, "admin", $user);
    }
	*/
    
	
	$nets = "";
    $nets_selected = POST("nets");
    if(is_array($nets_selected)) 
	{
        foreach ($nets_selected as $index => $net_name) {
            ossim_valid($net_name, OSS_ALPHA, OSS_PUNC, OSS_NULLABLE, 'illegal:' . _("net$i"));
            if (ossim_error()) {
                die(ossim_error());
            }
            if ($net_list_aux = Net::get_list($conn, "name = '$net_name'")) {
                foreach($net_list_aux as $net) {
                    if ($nets == "") $nets = $net->get_ips();
                    else $nets.= "," . $net->get_ips();
                }
            }
        }
    }
	
	
	$sensors = "";
	
	for ($i = 0; $i < $nsensors; $i++) {
        ossim_valid(POST("sensor$i") , OSS_LETTER, OSS_DIGIT, OSS_DOT, OSS_NULLABLE, 'illegal:' . _("sensor$i"));
        if (ossim_error()) {
            die(ossim_error());
        }
        if ($sensors == "") $sensors = POST("sensor$i");
        else $sensors.= "," . POST("sensor$i");
    }
	
	Session::insert($conn, $user, $pass1, $name, $email, $perms, $nets, $sensors, $company, $department, $language, $first_login, $tzone);
    $db->close($conn);
	?>
    <p> <?php echo gettext("User succesfully inserted"); ?> </p>
	
	<?php
	$location = "users.php";
    sleep(2);
    echo "<script>window.location='$location';</script>";

}
?>

</body>
</html>
