<?php
/**
 * language reading
 * read the language wich is passed as a parameter in the url and if
 * it is not available read the default language
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PSI_Language
 * @author    Michael Cramer <BigMichi1@users.sourceforge.net>
 * @copyright 2009 phpSysInfo
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @version   SVN: $Id: language.php 305 2009-07-18 18:17:10Z BigMichi1 $
 * @link      http://phpsysinfo.sourceforge.net
 */

require_once 'classes/Session.inc';

$db = new ossim_db();
$dbconn = $db->connect();
 
// Set the correct content-type header.
header("Content-Type: text/xml\n\n");

/**
 * default language
 *
 * @var String
 */
$lang = 'en';

/**
 * default pluginname
 *
 * @var String
 */
$plugin = '';

/**
 * application root path
 *
 * @var string
 */
define('APP_ROOT', realpath(dirname(( __FILE__ )).'/../'));

if (file_exists(APP_ROOT.'/config.php')) {
    include_once APP_ROOT.'/config.php';
}

/*if (defined('PSI_DEFAULT_LANG')) {
    $lang = PSI_DEFAULT_LANG;
}*/

$admin_data = Session::get_list($dbconn, "WHERE login='admin'");
preg_match("/(.*)_.*/",$admin_data[0]->get_language(),$found);

$lang = $found[1];

if ( isset ($_GET['lang'])) {
    if (file_exists(APP_ROOT.'/language/'.trim(htmlspecialchars(basename($_GET['lang']))).'.xml')) {
        $lang = basename($_GET['lang']);
    }
}

$plugin = isset ($_GET['plugin']) ? trim(htmlspecialchars(basename($_GET['plugin']))) : null;

if ($plugin == null) {
    if (file_exists(APP_ROOT.'/language/'.$lang.'.xml')) {
        echo file_get_contents(APP_ROOT.'/language/'.$lang.'.xml');
    } else {
        echo file_get_contents(APP_ROOT.'/language/en.xml');
    }
} else {
    if (file_exists(APP_ROOT.'/plugins/'.$plugin.'/lang/'.$lang.'.xml')) {
        echo file_get_contents(APP_ROOT.'/plugins/'.$plugin.'/lang/'.$lang.'.xml');
    } else {
        echo file_get_contents(APP_ROOT.'/plugins/'.$plugin.'/lang/en.xml');
    }
}

$dbconn->disconnect();
?>
