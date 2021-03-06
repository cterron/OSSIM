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
* - Net()
* - get_name()
* - get_name_by_ip()
* - get_ips()
* - get_asset()
* - get_threshold_c()
* - get_threshold_a()
* - get_rrd_profile()
* - get_alert()
* - get_persistence()
* - get_descr()
* - get_foundrows()
* - get_sensors()
* - get_list()
* - get_all()
* - insert()
* - update()
* - delete()
* - netthresh_c()
* - net_asset()
* - netthresh_a()
* - isIpInNet()
* - expand_CIDR()
* - enable_plugin()
* - disable_plugin()
* - isIpInAnyNet()
* - GetClosestNet()
* - check_orphan()
* - get_related_sensors()
* - get_cidrs_summary();
* Classes list:
* - Net
*/
require_once 'classes/Net_qualification.inc';
require_once 'classes/Net_vulnerability.inc';
require_once 'classes/Net_sensor_reference.inc';
require_once 'classes/Net_group.inc';
require_once 'classes/Control_panel_net.inc';
require_once 'classes/Session.inc';
require_once 'ossim_conf.inc';
require_once 'classes/Log_action.inc';
require_once 'ossim_db.inc';
require_once 'classes/WebIndicator.inc';
require_once 'classes/Util.inc';
require_once 'classes/CIDR.inc';

class Net {
    var $name;
    var $ips;
    var $asset;
    var $threshold_c;
    var $threshold_a;
    var $rrd_profile;
    var $alert;
    var $persistence;
    var $descr;
    var $icon;
    
    function Net($name, $ips, $asset, $threshold_c, $threshold_a, $rrd_profile, $alert, $persistence, $descr, $foundrows = 0,$icon = "") {
        $this->name = $name;
        $this->ips = preg_replace("/[\r\n\t]+/","",$ips);
        $this->asset = $asset;
        $this->threshold_c = $threshold_c;
        $this->threshold_a = $threshold_a;
        $this->rrd_profile = $rrd_profile;
        $this->alert = $alert;
        $this->persistence = $persistence;
        $this->descr = $descr;
        $this->foundrows = $foundrows;
		$this->icon = $icon;        
    }
    function get_name() {
        return $this->name;
    }
    function get_ips() {
        return $this->ips;
    }
    function get_asset() {
        return $this->asset;
    }
    function get_threshold_c() {
        return $this->threshold_c;
    }
    function get_threshold_a() {
        return $this->threshold_a;
    }
    function get_rrd_profile() {
        return $this->rrd_profile;
    }
    function get_alert() {
        return $this->alert;
    }
    function get_persistence() {
        return $this->persistence;
    }
    function get_descr() {
        return $this->descr;
    }
    function get_icon() {
        return $this->icon;
    }
    function get_imgtag() {
    	if ($this->icon=="") return "";
        return "<img src='data:image/png;base64,".base64_encode($this->icon)."' border='0' align='absbottom'>";
    }    
    function get_foundrows() {
        return $this->foundrows;
    }
    function get_sensors($conn) {
        return Net_sensor_reference::get_list($conn, $this->name);
    }
	function get_name_by_ip($conn,$ip,$withicon=false) {
		$name = $ip; 
		$icon = "";
		$query = OssimQuery("SELECT name,icon FROM net WHERE ips like '%$ip%'");
		
		if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } 
		elseif (!$rs->EOF) 
		{
			$name = $rs->fields["name"];
			$icon = $rs->fields["icon"];
		}
		return ($withicon) ? array($name,$icon) : $name;
	}
	function get_ips_by_name($conn,$name,$withicon=false) {
		$ips = $icon = "";
		$query = OssimQuery("SELECT ips,icon FROM net WHERE name=?");
        if (!$rs = & $conn->Execute($query,array($name))) {
            print $conn->ErrorMsg();
        } elseif (!$rs->EOF) {
			$ips = preg_replace("/[\r\n\t]+/","",$rs->fields["ips"]);
			$icon = $rs->fields["icon"];
		}
		return ($withicon) ? array($ips,$icon) : $ips;
	}
	// Get asset icon
	function get_icon_by_name($conn,$name) {
		$icon = "";
		$query = OssimQuery("SELECT icon FROM net WHERE name=?");
        if (!$rs = & $conn->Execute($query,array($name))) {
            print $conn->ErrorMsg();
        } elseif (!$rs->EOF) {
			$icon = $rs->fields["icon"];
		}
		return $icon;
	}
    // Clean asset icon
    function clean_icon($conn,$name) {
    	$conn->Execute("UPDATE net SET icon = NULL WHERE name = ?",array($name));
    }	
    /*
    function get_list($conn, $args = "") {
        $list = array();
        $iffoundrows = preg_match("/LIMIT/i", $args) ? true : false;
        $select = ($iffoundrows) ? "SQL_CALC_FOUND_ROWS *" : "*";
        $query = OssimQuery("SELECT $select FROM net $args");
        if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } else {
            $foundrows = 0;
            if ($iffoundrows) {
                if (!$rf = & $conn->Execute("SELECT FOUND_ROWS() as total")) print $conn->ErrorMsg();
                else $foundrows = $rf->fields["total"];
            }
            while (!$rs->EOF) {
                if (Session::netAllowed($conn, $rs->fields["name"])) {
                    $list[] = new Net($rs->fields["name"], $rs->fields["ips"], $rs->fields["asset"], $rs->fields["threshold_c"], $rs->fields["threshold_a"], $rs->fields["rrd_profile"], $rs->fields["alert"], $rs->fields["persistence"], $rs->fields["descr"], $foundrows);
                }
                $rs->MoveNext();
            }
        }
        return $list;
    }
    
    function get_list_pag($conn, $args = "") {
        $list = array(); $i=0;
        $from = 0; $max = 999999999;
        if (preg_match("/LIMIT (\d+),\s*(\d+)/", $args, $found)) {
            $args = preg_replace("/LIMIT \d+,\s*\d+/","",$args);
            $from = $found[1]; $max = $found[2];
        }
        $query = OssimQuery("SELECT * FROM net $args");
        
        if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } else {
            $foundrows = $rs->RecordCount();
            while (!$rs->EOF) {
				if (Session::netAllowed($conn, $rs->fields["name"])) {
					if ($i>=$from && $i<$max+$from) {
                        $list[] = new Net($rs->fields["name"], $rs->fields["ips"], $rs->fields["asset"], $rs->fields["threshold_c"], $rs->fields["threshold_a"], $rs->fields["rrd_profile"], $rs->fields["alert"], $rs->fields["persistence"], $rs->fields["descr"], $foundrows);
                    }
                    $i++;
                }
                $rs->MoveNext();
            }
        }
        return array($list,$i);
    }
	*/
    function get_list($conn, $filter = "", $limit = "ORDER BY name ASC", $sensors=array()) {
    	$filter = preg_replace("/^\s*where /i","",$filter); // Clean filter
    	$list = array();
    	$allowed_nets = Session::allowedNets();
    	$nets_where = "";
    	if ($allowed_nets != "") {
    		$aux = explode(",",$allowed_nets);
    		$networks = "";
    		foreach ($aux as $net) {
    			$networks .= ($networks != "") ? " OR ips LIKE '%".$net."%'" : "ips LIKE '%".$net."%'";
    		}
    		$nets_where = " AND (".$networks.")";
    	}
    	$allowed_sensors = Session::allowedSensors();
    	$sensor_where = "";
    	if ($allowed_sensors != "" || count($sensors)>0) {
    		$aux = ($allowed_sensors=="" || !count($sensors)) ? array_merge(explode(",",$allowed_sensors),$sensors) : array_intersect(explode(",",$allowed_sensors), $sensors);
    		$sensor_str = "";
    		foreach ($aux as $sensor) if (trim($sensor)!="")
    			$sensor_str .= ($sensor_str != "") ? ",'".$sensor."'" : "'".$sensor."'";
    		$sensor_where = " AND name in (SELECT net_name FROM net_sensor_reference WHERE sensor_name in (SELECT name FROM sensor WHERE ip in ($sensor_str)))";
    	}
    	$main_where = ($filter != "") ? "WHERE $filter" : "WHERE 1";
    	
    	$iffoundrows = preg_match("/LIMIT/i", $limit) ? true : false;
    	
        $query = OssimQuery("SELECT DISTINCT SQL_CALC_FOUND_ROWS net.* FROM net $main_where$sensor_where$nets_where $limit");
        //echo $query."<br>";exit;
        //error_log("$query\n",3,"/tmp/net.log");
        //error_log(implode(",",$sensors).":$query\n",3,"/tmp/log");
        if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } else {
        	$foundrows = 0;
            if ($iffoundrows) {
                if (!$rf = & $conn->Execute("SELECT FOUND_ROWS() as total")) print $conn->ErrorMsg();
                else $foundrows = $rf->fields["total"];
            }
            while (!$rs->EOF) {
                $list[] = new Net($rs->fields["name"], $rs->fields["ips"], $rs->fields["asset"], $rs->fields["threshold_c"], $rs->fields["threshold_a"], $rs->fields["rrd_profile"], $rs->fields["alert"], $rs->fields["persistence"], $rs->fields["descr"], $foundrows, $rs->fields["icon"]);
                $rs->MoveNext();
            }
        }
        return $list;
	}

    function get_all($conn,$checkperm=false) {
        $list = array();
        if ($checkperm) return Net::get_list($conn);
        // without perms list all networks
        $sql = "SELECT * FROM net ORDER BY name ASC";
        if (!$rs = $conn->Execute($sql)) {
            die($conn->ErrorMsg());
        }
        while (!$rs->EOF) {
            $list[] = new Net($rs->fields["name"], $rs->fields["ips"], $rs->fields["asset"], $rs->fields["threshold_c"], $rs->fields["threshold_a"], $rs->fields["rrd_profile"], $rs->fields["alert"], $rs->fields["persistence"], $rs->fields["descr"], 0, $rs->fields["icon"]);
            $rs->MoveNext();
        }
        return $list;
    }
    function insert($conn, $name, $ips, $asset, $threshold_c, $threshold_a, $rrd_profile, $alert, $persistence, $sensors, $descr, $icon="") {
        settype($asset, "int");
        settype($threshold_c, "int");
        settype($threshold_a, "int");
        settype($alert, "int");
        settype($persistence, "int");
        $rrd_profile = Util::htmlentities($rrd_profile);
        $persistence = Util::htmlentities($persistence);
        $name = str_replace(" ", "_", $name);
        $ips = preg_replace("/[\r\n\t\s]+/","",$ips);
        $sql = "INSERT INTO net (name, ips, asset, threshold_c, threshold_a, rrd_profile, alert, persistence, descr".($icon!="" ? ", icon" : "").") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?".($icon!="" ? ", ?" : "").")";
        $params = array(
            $name,
            $ips,
            $asset,
            $threshold_c,
            $threshold_a,
            $rrd_profile,
            $alert,
            $persistence,
            $descr
        );
        if ($icon!="") $params[]=$icon;
                
        if ($conn->Execute($sql, $params) === false) {
            print 'error inserting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        Net_qualification::insert($conn, $name, 1, 1);
        Net_vulnerability::insert($conn, $name, 0);
        Net_sensor_reference::insert($conn, $name, $sensors);
        Net::cache_insert_cidr($conn, $ips);
        $infolog = array(
            $name
        );
        Log_action::log(27, $infolog);
        WebIndicator::set_on("Reload_nets");
        WebIndicator::set_on("ReloadPolicy");
    }
    function update($conn, $name, $ips, $asset, $threshold_c, $threshold_a, $rrd_profile, $alert, $persistence, $sensors, $descr, $icon) {
    	if ($icon=="") $icon = Net::get_icon_by_name($conn, $name); // recover icon from previous registry
        $skip_group_update = 1;
        Net::delete($conn, $name, $skip_group_update, 1);
        Net::insert($conn, $name, $ips, $asset, $threshold_c, $threshold_a, $rrd_profile, $alert, $persistence, $sensors, $descr, $icon);
        $infolog = array(
            $name
        );
        Log_action::log(28, $infolog);
        WebIndicator::set_on("Reload_nets");
        WebIndicator::set_on("ReloadPolicy");
    }
    function delete($conn, $name, $skip_group_update = 0, $skip_template = 1) {
    	$ips = Net::get_ips_by_name($conn, $name);
        $sql = "DELETE FROM net WHERE name = ?";
        $params = array(
            $name
        );
        if ($conn->Execute($sql, $params) === false) {
            print 'error deleting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        Control_panel_net::delete($conn, $name);
        Net_qualification::delete($conn, $name);
        Net_vulnerability::delete($conn, $name);
        if ($skip_group_update != 1) {
            Net_group_reference::check_orphan($conn, $name);
        }
        //if (!$skip_template) {
        //    Acl::remove_cidrs_from_templates($conn, explode(",",$ips));
        //}
        Net_sensor_reference::delete($conn, $name);
        Net::cache_delete_cidr($conn, $ips);
        $infolog = array(
            $name
        );
        Log_action::log(29, $infolog);
        WebIndicator::set_on("Reload_nets");
        WebIndicator::set_on("ReloadPolicy");
    }
	function can_delete($conn, $name) {
		$sql = "SELECT count(*) as num FROM policy_net_reference WHERE net_name = ?";
		$params = array(
            $name
        );
        if (!$rs = & $conn->Execute($sql, $params)) {
            print 'error deleting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
		if ($rs->fields['num'] == '0') return 1;
		else return 0;
	}
    function netthresh_c($conn, $name) {
        $sql = "SELECT * FROM net WHERE name = ?";
        $params = array(
            $name
        );
        if (!$rs = $conn->Execute($sql, $params)) {
            print 'error: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        if (!$rs->EOF) return $rs->fields["threshold_c"];
        else {
            /* net is not in db, return default threshold */
            $conf = $GLOBALS["CONF"];
            return $conf->get_conf("threshold");
        }
    }
	function get_descr_by_name($conn, $name) {
        $sql = "SELECT * FROM net WHERE name = ?";
        $params = array(
            $name
        );
        if (!$rs = $conn->Execute($sql, $params)) {
            print 'error: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        if (!$rs->EOF) return ($rs->fields["description"] != "") ? $rs->fields["description"] : "No description found";
        else {
            return "No description found";
        }
    }
    function net_asset($conn, $name) {
        $sql = "SELECT * FROM net WHERE name = ?";
        $params = array(
            $name
        );
        if (!$rs = $conn->Execute($sql, $params)) {
            print 'error: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        if ($name == "") {
            /* Return default asset: 2 */
            return 2;
        }
        if (!$rs->EOF) {
            return $rs->fields["asset"];
        } else {
            /* Return default asset: 2 */
            return 2;
        }
    }
    function netthresh_a($conn, $name) {
        $sql = "SELECT * FROM net WHERE name = ?";
        $params = array(
            $name
        );
        if (!$rs = $conn->Execute($sql, $params)) {
            print 'error: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        if (!$rs->EOF) return $rs->fields["threshold_a"];
        else {
            /* net is not in db, return default threshold */
            $conf = $GLOBALS["CONF"];
            return $conf->get_conf("threshold");
        }
    }
    /*
    * PRE:
    *   ip in ipv4 format (X.X.X.X)
    *   nets in net/mask format (X.X.X.X/X,X.X.X.X/X,...)
    */
    function isIpInNet($ip, $nets) {
        $net_list = explode(",", $nets);
        foreach($net_list as $n) {
        	$cached = $_SESSION["_ipinnet"][$n][$ip];
        	if ($cached>0) return true;
        	elseif ($cached<0) continue;
			list($net, $mask) = split("/", $n);
            $val1 = ip2long($ip);
            $val2 = ip2long($net);
            if (($val1 >> (32 - $mask)) == ($val2 >> (32 - $mask))) {
            	$_SESSION["_ipinnet"][$n][$ip] = 1;
            	return true;
            } else 
            	$_SESSION["_ipinnet"][$n][$ip] = -1;
        }
        return false;
    }   
    function expand_CIDR($nets,$return_type="FULL",$return_format="IP") {
        require_once ('classes/CIDR.inc');
        $ip_range = array();
        if (!is_array($net_list = explode(",", $nets))) return NULL;
        foreach($net_list as $n) {
            if (is_array($cidr_range = CIDR::expand_CIDR($n, $return_type, $return_format)))
				if ($return_type == "SHORT" && $return_format == "LONG") {
					$ip_range[0] = ($cidr_range[0] < $ip_range[0] || $ip_range[0] < 1) ? $cidr_range[0] : $ip_range[0];
					$ip_range[1] = ($cidr_range[1] > $ip_range[1]) ? $cidr_range[1] : $ip_range[1];
				} else {
					$ip_range = array_merge($ip_range, $cidr_range);
				}
        }
        return $ip_range;
    }
    function enable_plugin($conn, $net_name, $plugin) {
        $plugin = intval($plugin);
        $sql = "INSERT INTO net_scan (net_name, plugin_id, plugin_sid) VALUES (?, ?, 0)";
        $params = array(
            $net_name,
            $plugin
        );
        if ($conn->Execute($sql, $params) === false) {
            print 'error inserting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
    }
    function disable_plugin($conn, $net_name, $plugin) {
        $plugin = intval($plugin);
        $sql = "DELETE FROM net_scan WHERE net_name = ? AND plugin_id = ?";
        $params = array(
            $net_name,
            $plugin
        );
        if ($conn->Execute($sql, $params) === false) {
            print 'error deleting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
    }
    /*function isIpInAnyNet($conn, $ip) {
        $net_list = Net::get_list($conn);
        if (!is_array($net_list)) return false;
        foreach($net_list as $n) {
            list($net, $mask) = split("/", $n->get_ips());
            $val1 = ip2long($ip);
            $val2 = ip2long($net);
            if (($val1 >> (32 - $mask)) == ($val2 >> (32 - $mask))) return true;
        }
        return false;
    }*/
	function isIpInAnyNet($conn, $ip) {
        return Net::is_ip_in_cache_cidr($conn, $ip);
    }
    /*function GetClosestNet($conn, $ip, $return_type=0) {
        $net_list = Net::get_list($conn);
        $narrowest_mask = 0;
        $narrowest_net = "";
        if (!is_array($net_list)) return false;
        foreach($net_list as $n) {
            list($net, $mask) = split("/", $n->get_ips());
            $val1 = ip2long($ip);
            $val2 = ip2long($net);
            if (($val1 >> (32 - $mask)) == ($val2 >> (32 - $mask))) {
                if ($mask > $narrowest_mask) {
                    $narrowest_mask = $mask;
                    $narrowest_net = ($return_type) ? $n->get_ips() : $n->get_name();
                }
            }
        }
        if ($narrowest_mask) {
            return $narrowest_net;
        }
        return false;
    }*/
    function GetClosestNet($conn, $ip, $return_type=0) {
        $net_list = Net::get_cache_cidr($conn, $ip);
        $narrowest_mask = 0;
        $narrowest_net = "";
        if (!is_array($net_list)) return false;
        foreach($net_list as $n) {
            list($net, $mask) = split("/", $n);
            if ($mask > $narrowest_mask) {
                $narrowest_mask = $mask;
                $narrowest_net = ($return_type) ? $n : Net::get_name_by_ip($conn,$n);
            }
        }
        if ($narrowest_mask) {
            return $narrowest_net;
        }
        return false;
    }    
    function check_orphan($conn) {
        $sql = "SELECT * FROM net_group_reference LEFT JOIN net
                ON net.name = net_group_reference.net_name WHERE descr IS NULL";
        if (!$rs = & $conn->Execute($sql)) {
            print $conn->ErrorMsg();
        } else {
            while (!$rs->EOF) {
                Net_sensor_reference::delete($conn, $rs->fields["net_name"], $rs->fields["sensor_name"]);
                $rs->MoveNext();
            }
        }
    }
    function get_related_sensors($conn, $net) {
        $sensors = array();
        /* read from net table */
        $sql = "SELECT sensor.ip AS sensor FROM sensor, net_sensor_reference
                WHERE net_sensor_reference.net_name = ? AND
                sensor.name = net_sensor_reference.sensor_name ORDER BY sensor.priority DESC,sensor.name ASC";
        $params = array(
            $net
        );
        if (!$rs = & $conn->Execute($sql, $params)) {
            print $conn->ErrorMsg();
        } else {
             while (!$rs->EOF) {
                 $sensors[] = $rs->fields["sensor"];
                 $rs->MoveNext();
             }
        }
        return $sensors;
    }
    #
    # net cidr�s table cache
    #
    function cache_insert_cidr($conn, $nets) {
    	$cidrs = explode(",",$nets);
    	foreach ($cidrs as $cidr) {
			$exp = CIDR::expand_CIDR($cidr,"SHORT");
		    $sql = "INSERT IGNORE INTO net_cidrs (`cidr`,`begin`,`end`) VALUES (?, ?, ?)";
		    $params = array(
		        trim($cidr),
		        $exp[0],
		        $exp[1]
		    );
		    if ($conn->Execute($sql, $params) === false) {
		        print 'error inserting: ' . $conn->ErrorMsg() . '<BR>';
		        exit;
		    }
		}
    }
    function cache_delete_cidr($conn, $nets) {
    	$cidrs = explode(",",$nets);
    	foreach ($cidrs as $cidr) {
		    $sql = "DELETE FROM net_cidrs WHERE cidr=?";
		    $params = array(
		        trim($cidr)
		    );
		    if ($conn->Execute($sql, $params) === false) {
		        print 'error inserting: ' . $conn->ErrorMsg() . '<BR>';
		        exit;
		    }
		}
    }   
    function get_cache_cidr($conn, $ip) {
    	$local_nets = array();
        $sql = "SELECT cidr FROM net_cidrs WHERE inet_aton(?)>=begin AND inet_aton(?)<=end";        
        $params = array(
            $ip, $ip
        );
        if (!$rs = & $conn->Execute($sql, $params)) {
            print $conn->ErrorMsg();
        } else {
             while (!$rs->EOF) {
             	$local_nets[] = $rs->fields["cidr"];
             	$rs->MoveNext();
             }
        }
        return $local_nets;
    }
    function is_ip_in_cache_cidr($conn, $ip, $nets="") {
        if ($nets!="") 
        	$sql = "SELECT cidr FROM net_cidrs WHERE cidr in ('".str_replace(",","','",$nets)."') AND inet_aton(?)>=begin AND inet_aton(?)<=end";
        else
        	$sql = "SELECT cidr FROM net_cidrs WHERE inet_aton(?)>=begin AND inet_aton(?)<=end";        
        $params = array(
            $ip, $ip
        );
        if (!$rs = & $conn->Execute($sql, $params)) {
            print $conn->ErrorMsg();
        } else {
             if (!$rs->EOF)
             	return $rs->fields["cidr"];
        }
        return "";
    }
    function get_cidrs_summary($conn, $net_name) {
        $sql = "SELECT ips FROM net
                WHERE name = ? ";
        $params = array(
            $net_name
        );
        if (!$rs = & $conn->Execute($sql, $params)) {
            print $conn->ErrorMsg();
        } else {
            $tmp = explode(",", preg_replace("/[\r\n\t]+/","",$rs->fields["ips"]));
            if(count($tmp)>2) {
                return $tmp[0]."...".$tmp[count($tmp)-1];
            }
            else {
                return $rs->fields["ips"];
            }
        }
    }
    /* 
        Parameters:
        
        urltype = 0 Asset Structure
        urltype = 1 Targets in Vulnerabilities
        urltype = 2 Asset in wizard
    */
    function draw_nets_by_class($conn, $key, $filter, $length_name=50, $urltype=0, $low_limit = 10, $high_limit = 100) {
    
        require_once ('classes/Session.inc');
        require_once ('classes/Host.inc');
        require_once ('classes/Util.inc');
    
        $buffer    = "";
        $condition = "";
        
        if( $filter!="" ) {
            if(preg_match("/\d+\./",$filter))   
                $condition = "ips LIKE '".$filter."%' OR ips LIKE '%,".$filter."%'";
            else                                 
                $condition = "name LIKE '%".$filter."%'";
        }
        
        if(preg_match("/.*class.*/",$key) && $condition !="") {
            $condition = " AND (".$condition.")"; 
        }
        
        if($key=="net") {   // current user nets
            $net_list = Net::get_list($conn, $condition, "");
        }
        else if ( preg_match("/^.class_(.*)/",$key,$found) ) {  // current user subnets
            $net_list = Net::get_list($conn,"(ips LIKE '".$found[1].".%' OR ips LIKE '%,".$found[1].".%')" . $condition, "");
        }
        else if( preg_match("/^e_(.*)_net$/",$key,$found) ) { // entity nets
            $entityPerms    = Acl::entityPerms($conn,$found[1]);
            $all            = count($entityPerms["assets"]);
            $nets_to_check  = Net::get_list($conn, $condition, "ORDER BY name ASC", array_keys($entityPerms['sensors'])); //Net::get_all($conn);

            foreach ($nets_to_check as $net) {
                $cidrs = explode(",",$net->get_ips());
                if(!$all || Acl::cidrs_allowed($cidrs,$entityPerms["assets"]))  $net_list[] = $net; // save nets
            }
            

        }
        else if ( preg_match("/^e_(.*)_.class_(.*)/",$key,$found) ) { // entity subnets
            $entityPerms    = Acl::entityPerms($conn,$found[1]);
            $all            = count($entityPerms["assets"]);
            $nets_to_check  = Net::get_list($conn, "(ips LIKE '".$found[2].".%' OR ips LIKE '%,".$found[2].".%')" . $condition, "ORDER BY name ASC", array_keys($entityPerms['sensors'])); //Net::get_all($conn);
            
            foreach ($nets_to_check as $net) {
                $cidrs = explode(",",$net->get_ips());
                if(!$all || Acl::cidrs_allowed($cidrs,$entityPerms["assets"]))  $net_list[] = $net; // save nets
            }
        }
        else if( preg_match("/^u_(.*)_net$/",$key,$found) ) { // user nets
            $nets_to_check  = Net::get_list($conn, $condition);
            $allowedNets    = Session::allowedNets($found[1]);
            $nets_allowed   = array_fill_keys(explode(",",$allowedNets),1);
        
            foreach ($nets_to_check as $net) {
                $cidrs = explode(",",$net->get_ips());
                if ($allowedNets == "" || Acl::cidrs_allowed($cidrs,$nets_allowed))  $net_list[] = $net;
            }
        }
        else if(preg_match("/^u_(.*)_.class_(.*)/",$key,$found) ) { // user subnets
            $nets_to_check  = Net::get_list($conn, "(ips LIKE '".$found[2].".%' OR ips LIKE '%,".$found[2].".%')" . $condition);
            $allowedNets    = Session::allowedNets($found[1]);
            $nets_allowed   = array_fill_keys(explode(",",$allowedNets),1);
        
            foreach ($nets_to_check as $net) { 
                $cidrs = explode(",",$net->get_ips());
                if ($allowedNets == "" || Acl::cidrs_allowed($cidrs,$nets_allowed))  $net_list[] = $net;
            }
        }
        
        if(count($net_list)>0) {
            // Find IP classes (Class A, Class B and Class C)
            
            foreach ($net_list as $net) {
                $cidrs = trim($net->get_ips());
                $acidrs = explode(",", $cidrs);
                foreach($acidrs as $cidr) {
                    $data = explode(".", $cidr);
                    
                    if($cclasses[$data[0].".".$data[1].".".$data[2]]=="")  $cclasses[$data[0].".".$data[1].".".$data[2]] = Host::ip2ulong($data[0].".".$data[1].".".$data[2].".0");
                    if($bclasses[$data[0].".".$data[1]]=="")               $bclasses[$data[0].".".$data[1]]              = Host::ip2ulong($data[0].".".$data[1].".0.0");
                    if($aclasses[$data[0]]=="")                            $aclasses[$data[0]]                           = Host::ip2ulong($data[0].".0.0.0");
                }
                
                $name = trim($net->get_name());
                $nets[$name] = $cidrs;
            
            }
            
            asort($aclasses);
            asort($bclasses);
            asort($cclasses);

            ksort($nets);
            $data = array();
            $found = array();
            
            if($key == "net" || preg_match("/^u_(.*)_net$/",$key,$found) || preg_match("/^e_(.*)_net$/",$key,$found) ) {
                
                $uoe = "";
                
                if( $found[1]!="" ) {
                    if(preg_match("/^\d+$/",$found[1]))     $uoe = "e_".$found[1]."_";
                    else                                    $uoe = "u_".$found[1]."_";
                }
                $buffer .= "[";
                
                if(count($bclasses) <= $low_limit ) {
                    foreach($cclasses as $cclass => $v) {
                        $buffer .= "{ key:'".$uoe."cclass_$cclass', icon:'../../pixmaps/theme/net.png', title:'$cclass.---/--', expand:true, children:[\n";
                        foreach($nets as $net_name => $net_cidrs) if(preg_match("/$cclass\..*/",$net_cidrs)) {
                        
                            $net_title = Util::htmlentities($net_name);
                            
                            $net_key   = "net_".base64_encode($net_name);
                            $ips       = "<font style=\"font-size:80%\">(".$net_cidrs.")</font>";
                                            
                            $title     = ( strlen($net_name) > $length_name ) ? substr($net_name, 0, $length_name)."..." : $net_name;	
                            $title     = Util::htmlentities($title)." ".$ips;
                            
                            $tooltip   = $net_title." (".$net_cidrs.")";
                            
                            if( $urltype == 0 )
                                $url = "../net/newnetform.php?name=".urlencode($net_name);
                            else if ( $urltype == 1 )
                                $url = $net_cidrs;
                            else if ( $urltype == 2 )
                                $url = "NET:".Util::htmlentities($net_name);
                            
                            $buffer .= "{ key:'$net_key', isLazy:true, h:'$h', url:'$url', icon:'../../pixmaps/theme/net.png', title:'$title', tooltip:'$tooltip' },";

                        }
                        $buffer = preg_replace("/,$/", "", $buffer);
                        $buffer .= "]},";
                    }
                }
                else if(count($bclasses) > $low_limit && count($bclasses) <= $high_limit) {
                    foreach($bclasses as $bclass => $v)
                        $buffer .= "{ key:'".$uoe."bclass_$bclass', isFolder:true, isLazy:true, icon:'../../pixmaps/theme/net.png', title:'$bclass.---.---/--'},";
                }
                else {
                    foreach($aclasses as $aclass => $v)
                        $buffer .= "{ key:'".$uoe."aclass_$aclass', isFolder:true, isLazy:true, icon:'../../pixmaps/theme/net.png', title:'$aclass.---.---.---/--'},";
                }
                $buffer = preg_replace("/,$/", "", $buffer);
                
                $buffer .= "]";
                
            }
            else if( preg_match("/^aclass_(\d+)/", $key) || preg_match("/^._(.*)_aclass_\d+/", $key, $data) ) {
                $uoe = "";
                
                if( $data[1]!="" ) {
                    if(preg_match("/^\d+$/",$data[1]))      $uoe = "e_".$data[1]."_";
                    else                                    $uoe = "u_".$data[1]."_";
                }

                $buffer .= "[";
                foreach($bclasses as $bclass => $v)
                    $buffer .= "{ key:'".$uoe."bclass_$bclass', isFolder:true, isLazy:true, icon:'../../pixmaps/theme/net.png', title:'$bclass.---.---/--'},";
                $buffer = preg_replace("/,$/", "", $buffer);
                $buffer .= "]";
            }
            else if( preg_match("/^bclass_(\d+\.\d+)/", $key) ||  preg_match("/^._(.*)_bclass_(\d+\.\d+)/",$key,$data) ){
                $uoe = "";
                
                if( $data[1]!="" ) {
                    if(preg_match("/^\d+$/",$data[1]))      $uoe = "e_".$data[1]."_";
                    else                                    $uoe = "u_".$data[1]."_";
                }
                
                $buffer .= "[";
                foreach($cclasses as $cclass => $v)
                    $buffer .= "{ key:'".$uoe."cclass_$cclass', isFolder:true, isLazy:true, icon:'../../pixmaps/theme/net.png', title:'$cclass.---/--'},";
                $buffer = preg_replace("/,$/", "", $buffer);
                $buffer .= "]";
            }
            else if( preg_match("/^cclass_(\d+\.\d+\.\d+)/",$key) ||  preg_match("/^._(.*)_cclass_(\d+\.\d+\.\d+)/",$key) ){
                $buffer .= "[";

                foreach($nets as $net_name => $net_cidrs) {
                    $net_title = Util::htmlentities($net_name);
                            
                    $net_key   = "net_".base64_encode($net_name);
                    $ips       = "<font style=\"font-size:80%\">(".$net_cidrs.")</font>";
                                            
                    $title     = ( strlen($net_name) > $length_name ) ? substr($net_name, 0, $length_name)."..." : $net_name;	
                    $title     = Util::htmlentities($title)." ".$ips;
                            
                    $tooltip   = $net_title." (".$net_cidrs.")";
                            
                    if( $urltype == 0 )
                        $url = "../net/newnetform.php?name=".urlencode($net_name);
                    else if ( $urltype == 1 )
                        $url = $net_cidrs;
                    else if ( $urltype == 2 )
                        $url = "NET:".Util::htmlentities($net_name);
                            
                    $buffer .= "{ key:'$net_key', isLazy:true, h:'$h', url:'$url', icon:'../../pixmaps/theme/net.png', title:'$title', tooltip:'$tooltip' },";
                }

                $buffer = preg_replace("/,$/", "", $buffer);
                $buffer .= "]";
            }
        
        }
        
        if ( $buffer == "" || $buffer == "[]" )
            $buffer = "[{title:'"._("No Networks Found")."', noLink:true }]";

        return $buffer;
    }
}
?>
