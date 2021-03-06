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
* - Agentless()
* - get_ip()
* - get_hostname()
* - get_user()
* - get_password()
* - get_password_type()
* - get_descr()
* - get_list()
* - get_list_m_entries()
* - add_monitoring_entry()
* - delete_monitoring_entry()
* - modify_monitoring_entry()
* - add_host_data()
* - modify_host_data()
* - delete_host_data()
* - change_status()
* - enable_host()
* - disable_host()
* - get_list_ossec()
* Classes list:
* - Agentless
*/

require_once 'classes/Session.inc';
require_once 'classes/Security.inc';
require_once 'classes/Host.inc';
require_once 'classes/Host_os.inc';
require_once 'classes/Util.inc';
require_once 'ossim_db.inc';

class Agentless {

	private $ip;
    private $hostname;
	private $user;
    private $pass;
	private $ppass;
	private $status;
    private $descr;
	
	function __construct($ip, $hostname, $user, $pass, $descr, $foundrows, $status=1, $ppass=null){
		$this->ip          = $ip;
		$this->hostname    = Util::htmlentities($hostname);
		$this->user        = $user;
		$this->pass        = $pass;
		$this->ppass       = $ppass;
		$this->status      = $status;
		$this->descr       = $descr;
		$this->foundrows   = $foundrows;
	}
	
	public function get_ip()        { return $this->ip; }
    public function get_hostname()  { return $this->hostname;}
	public function get_user()      { return $this->user; }
    public function get_pass()      { return $this->pass;}
	public function get_ppass()     { return $this->ppass; }
	public function get_status()    { return $this->status;}
    public function get_descr()     { return $this->descr;}
	public function get_foundrows() { return $this->foundrows;}
	
	
	function get_list($conn, $extra = "") {
        mysql_escape_string($extra);
        $iffoundrows = preg_match("/LIMIT/i", $order) ? true : false;
		$uuid        = Util::get_system_uuid();
        
		if ( $iffoundrows )
			$select = "SQL_CALC_FOUND_ROWS *, AES_DECRYPT(pass,'$uuid') AS pass_decrypt, AES_DECRYPT(ppass,'$uuid') AS ppass_decrypt";
		else
			$select = "*, AES_DECRYPT(pass,'$uuid') AS pass_decrypt, AES_DECRYPT(ppass,'$uuid') AS ppass_decrypt";
		
		$query = OssimQuery("SELECT $select FROM host_agentless $extra");
		
		$list  = array();
        
		if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } 
		else
		{
            $foundrows = 0;
            if ($iffoundrows) {
                if (!$rf = & $conn->Execute("SELECT FOUND_ROWS() as total")) 
					print $conn->ErrorMsg();
                else 
					$foundrows = $rf->fields["total"];
            }
            while (!$rs->EOF)
			{
                $list[$rs->fields["ip"]] = new Agentless( $rs->fields["ip"],     
														  $rs->fields["hostname"],  
														  $rs->fields["user"], 
														  ( empty($rs->fields['pass_decrypt']) ) ? $rs->fields['pass'] : $rs->fields['pass_decrypt'], 
														  $rs->fields["descr"],  
														  $foundrows, 
														  $rs->fields["status"],  
														  ( empty($rs->fields['ppass_decrypt']) ) ? $rs->fields['ppass'] : $rs->fields['ppass_decrypt']);
                $rs->MoveNext();
            }
        }
        return $list;
    }
	
	public function get_list_m_entries ($conn, $extra = "")
	{
		
		$array_types = array ("ssh_integrity_check_bsd"    => "Integrity Check BSD",
							  "ssh_integrity_check_linux"  => "Integrity Check Linux",
							  "ssh_generic_diff" 		   => "Generic Command Diff",
							  "ssh_pixconfig_diff"  	   => "Cisco Config Check",
							  "ssh_foundry_diff" 		   => "Foundry Config Check ",
							  "ssh_asa-fwsmconfig_diff "   => "ASA FWSMconfig Check");
						
		mysql_escape_string($extra);
       
		$query = OssimQuery("SELECT * FROM host_agentless_entries $extra");
		
		$list = array();
        
		if (!$rs = & $conn->Execute($query))
		    return $conn->ErrorMsg();
        else
		{
            while (!$rs->EOF)
			{
                $list[] = array( 
					"id"        => $rs->fields["id"],   
					"ip"        => $rs->fields["ip"],   				
					"type"      => $array_types[$rs->fields["type"]],
					"id_type"   => $rs->fields["type"],
					"frequency" => $rs->fields["frequency"],
					"state"     => $rs->fields["state"],
					"arguments" => $rs->fields["arguments"]							 
				);
                $rs->MoveNext();
            }
        }
		
        return $list;
	}
	
	public function add_monitoring_entry($conn, $ip, $type, $frequency, $state, $arguments)
	{
		$arguments = preg_replace("/[\r?\n]+/", " ", $arguments);
		
		$sql = "INSERT INTO host_agentless_entries (id, ip, type, frequency, state, arguments) VALUES (?, ?, ?, ?, ?, ?)";
        $params = array(
            null,
			$ip,
            $type,
			$frequency,
            $state,
            Util::utf8_decode2($arguments)
        );
		
		if ($conn->Execute($sql, $params) === false)
		{
            //print 'Error Inserting Monitorig Host Entry: ' . $conn->ErrorMsg() . '<br/>';
            return false;
        } 
		else
		{
            $sql = "SELECT last_insert_id( ) AS id FROM host_agentless_entries";
			$params = array();
			if (!$rs = & $conn->Execute($sql, $params)) 
				return false;
			else
			{
				exec('touch /var/ossec/agentless/.reload');	
				return $rs->fields['id'];
			}
			
        }
	}
	
	public function delete_monitoring_entry($conn, $id)
	{
		$sql = "DELETE FROM host_agentless_entries WHERE id=?;";
        $params = array(
            $id
		);
		
		if ($conn->Execute($sql, $params) === false)
		{
            //print 'Error Deleting Monitorig Host Entry: ' . $conn->ErrorMsg() . '<br/>';
            return false;
        } 
		else
		{
			exec('touch /var/ossec/agentless/.reload');	
			return true;
		}
	}
	
	public function modify_monitoring_entry($conn, $type, $frequency, $state, $arguments, $id)
	{
		
		$arguments = preg_replace("/[\r?\n]+/", " ", $arguments);
		
		$sql = "UPDATE host_agentless_entries SET type=?, frequency=?, state=?, arguments=? WHERE id=?;";
        $params = array(
            $type,
			$frequency,
            $type,
			$state,
            Util::utf8_decode2($arguments),
            $id
        );
		
		if ($conn->Execute($sql, $params) === false)
		{
            //print 'Error Updating Monitorig Host Entry: ' . $conn->ErrorMsg() . '<br/>';
            return false;
        } 
		else
		{
			exec('touch /var/ossec/agentless/.reload');	
			return true;
		}
		
	}
	
	public function add_host_data($conn, $ip, $hostname, $user, $pass, $ppass, $descr, $status=1)
	{
		$uuid       = Util::get_system_uuid();
		$sql_pass_1 = ( !empty($pass) )  ? "AES_ENCRYPT(?,'$uuid')" : "?";
		$sql_pass_2 = ( !empty($ppass) ) ? "AES_ENCRYPT(?,'$uuid')" : "?";
		
		$sql        = "INSERT INTO host_agentless (ip, hostname, user, pass, ppass, descr, status) VALUES (?, ?, ?, ".$sql_pass_1.", ".$sql_pass_2.", ?, ?)";
        
		$params = array(
            $ip,
            $hostname,
			$user,
			$pass,
            $ppass,
            Util::utf8_decode2($descr),
			$status
        );
		 
		
		if ($conn->Execute($sql, $params) === false)
		{
            //print 'Error Inserting Monitorig Host Data: ' . $conn->ErrorMsg() . '<br/>';
            return false;
        } 
		else
		{
		    exec('touch /var/ossec/agentless/.reload');	
			return $rs->fields['id'];	
		}
    }
	
	public function modify_host_data ($conn, $ip, $hostname, $user, $pass, $ppass, $descr, $status)
	{
		
		if ( Util::is_fake_pass($pass) )
			$sql_pass_1  = "";
		else
		{
			if ( !empty($pass) )
			{
				$uuid        = Util::get_system_uuid();
				$sql_pass_1  = "pass = AES_ENCRYPT(?,'$uuid'),";
			}
			else
				$sql_pass_1  = "pass = ?,";
		}
		
		if ( Util::is_fake_pass($ppass) )
			$sql_pass_2  = "";
		else
		{
			if ( !empty($ppass) )
			{
				$uuid        = Util::get_system_uuid();
				$sql_pass_2  = "ppass = AES_ENCRYPT(?,'$uuid'),";
			}
			else
				$sql_pass_2  = "ppass = ?,";
		}
		
				
		$sql   = "UPDATE host_agentless SET hostname=?, user=?, ".$sql_pass_1." ".$sql_pass_2." descr=?, status=? WHERE ip=?;";
			
		
		$params[] = $hostname;
		$params[] = $user;
		
		if ( !Util::is_fake_pass($pass) )
			$params[] = $pass;
		
		if ( !Util::is_fake_pass($ppass) )
			$params[] = $ppass;
		
		$params[] = Util::utf8_decode2($descr);
		$params[] = $status;
		$params[] = $ip;
		
		if ($conn->Execute($sql, $params) === false)
		{
            //print 'Error Updating Monitorig Host Data: ' . $conn->ErrorMsg() . '<br/>';
            return false;
        } 
		else
		{
			exec('touch /var/ossec/agentless/.reload');	
			return true;
		}
	}	
	
	public function delete_host_data($conn, $ip)
	{
		$sql = "DELETE FROM host_agentless_entries WHERE ip=?;";
        $params = array(
            $ip
		);
		
		if ($conn->Execute($sql, $params) === false)
		{
            //print 'Error Deleting Host: ' . $conn->ErrorMsg() . '<br/>';
            return false;
        } 
		else
		{
			$sql = "DELETE FROM host_agentless WHERE ip=?;";
			$params = array(
				$ip
			);
			
			if ($conn->Execute($sql, $params) === false)
				return false;
			else
			{
				exec('touch /var/ossec/agentless/.reload');	
				return true;
			}
		}
			
	}
	
	public function delete_host_ossec($conn, $ip)
	{
		$path      = '/var/ossec/agentless/.passlist';
		$path_tmp  =  "/tmp/".uniqid()."_tmp.passlist";
				
		$host      = Agentless::get_list($conn, "WHERE ip='$ip'");
		
		if ( !empty($host) )
		{
			@copy ($path , $path_tmp);
			$user       = $host[$ip]->get_user();
			$hostname   = $host[$ip]->get_hostname();
			$ossec_file = file($path);
					
			$pattern = "/$user@$ip|$user@$hostname/";
			
			foreach ($ossec_file as $k => $v)
			{
				if( preg_match($pattern, $v) )
					unset($ossec_file[$k]);
			}
			
			if ( !empty ($ossec_file) )	
			{
				$new_file = implode("", $ossec_file);
			
				if (@file_put_contents($path, $new_file, LOCK_EX) == false)
				{
					@copy ($path_tmp , $path);
					return false;
				}
			}
			else
				@unlink($path);	
			
			@unlink($path_tmp);
			return true;
			
		}
		else
			return false;
	}
	
	public function change_status($conn, $status)
	{
		$ip   	   = $this->get_ip();
		$hostname  = $this->get_hostname();
		$user      = $this->get_user();
		$pass      = $this->get_pass();
		$ppass     = $this->get_ppass();
		$descr     = html_entity_decode($this->get_descr());
		
		$res = $this->modify_host_data ($conn, $ip, $hostname, $user, $pass, $ppass, $descr, $status);
		exec('touch /var/ossec/agentless/.reload');	
						
		return $res;
	}
	
	public function enable_host($ip, $user, $pass, $ppass)
	{
		$command = "sudo /var/ossec/agentless/register_host.sh add $user@$ip $pass $ppass";
		
		exec ($command, $output, $retval);
								
		if ( $retval !== 0 )
			return false;
		else
			return true;
		
	
	}
	
	public function disable_host($ip, $user, $pass, $ppass)
	{
		$host = $user."@".$ip."|".$pass."|".$ppass;
		
		$available_hosts = file('/var/ossec/agentless/.passlist');
		
		if ( !is_array($available_hosts) )
			return false;
		
		foreach ($available_hosts as $k => $v)
		{
			if ( preg_match("/$host/", $v) )
			{
				unset($available_hosts[$k]);
				$res = file_put_contents('/var/ossec/agentless/.passlist', implode("", $available_hosts));
				return ( $res == false ) ? false : true;
			}
		}
		
		return false;
	}
	
	public function get_list_ossec($conn)
	{
		$list = array();
				
		if ( file_exists('/var/ossec/agentless/.passlist') )
			$available_hosts = file('/var/ossec/agentless/.passlist', FILE_IGNORE_NEW_LINES);
		else
			return array();
						
		
		$num_host = count($available_hosts);
		
		foreach ($available_hosts as $k => $v)
		{
			$add  = true;
			
			$data = explode("|", $v);
			
			$user_host = explode("@", $data[0]);
			$user      = $user_host[0];
			$pass      = $data[1];
			$ppass     = ( empty($data[2]) ) ? null : $data[2];
			$descr     = null;
			
			if ( valid_ip($user_host[1]) )
			{
				$ip   	   = $user_host[1];
				$hostname  = Host::ip2hostname($conn, $ip, true, false);
				$hostname  = ( empty($hostname) ) ? $ip : $hostname;
			}
			else
			{
				$hostname = $user_host[1];
				$ip       = Host::hostname2ip($conn, $hostname, true);   
				$add      = ( empty($ip) ) ? false : true;
			}
			
			if ($add == true)
				$list[$ip] = new Agentless( $ip, $hostname, $user, $pass, $descr, $num_host, 2, $ppass);
			
		}
		
		return $list;
	
	}
	

		
	
}
?>