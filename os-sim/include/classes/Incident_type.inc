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
* - Incident_type()
* - get_id()
* - get_descr()
* - get_list()
* - insert()
* - update()
* - delete()
* - get_list()
* - get_custom_types()
* - insert_custom()
* - update_custom()
* - delete_custom()
* - update_ord()
* - get_next_ord()
* Classes list:
* - Incident_type
* - Incident_custom
*/
require_once ('classes/Incident.inc');
require_once 'classes/Log_action.inc';
require_once ("ossim_db.inc");

class Incident_type {
    var $id;
    var $descr;
    function Incident_type($id, $descr, $keywords="") {
        $this->id = $id;
        $this->descr = $descr;
        $this->keywords = $keywords;
    }
    function get_id() {
        return $this->id;
    }
    function get_descr() {
        return $this->descr;
    }
    function get_keywords() {
        return $this->keywords;
    }
    function get_list($conn, $args = "") {
        $list = array();
        $query = OssimQuery("SELECT * FROM incident_type $args");
        if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } else {
            while (!$rs->EOF) {
                $list[] = new Incident_type($rs->fields["id"], $rs->fields["descr"], $rs->fields["keywords"]);
                $rs->MoveNext();
            }
        }
        return $list;
    }
    
	function insert($conn, $id, $descr, $keywords="") {
        $sql = "INSERT into incident_type ( id, descr, keywords) VALUES   (?, ?, ?)";
        $params = array(
            $id,
            $descr,
            $keywords
        );
        if ($conn->Execute($sql, $params) == false) {
            return 'Error inserting: ' . $conn->ErrorMsg();
        }
        $infolog = array($id);
        Log_action::log(53, $infolog);
		
		return true;
    }
	
    function update($conn, $id, $descr, $keywords="") {
    	if ($keywords=="custom") {
	        $sql = "UPDATE incident_type SET descr= ?,keywords=TRIM(CONCAT(keywords,' custom')) WHERE id = ?";
	        $params = array(
	            $descr,
	            $id
	        );
	    } else {
	        $sql = "UPDATE incident_type SET descr= ?,keywords=TRIM(REPLACE(keywords,'custom','')) WHERE id = ?";
	        $params = array(
	            $descr,
	            $id
	        );
	    }
        if ($conn->Execute($sql, $params) == false) {
            print 'Error updating: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        $infolog = array($id);
        Log_action::log(52, $infolog);
    }
    function delete($conn, $id) {
        $sql = "DELETE FROM incident_type WHERE id = ?";
        $params = array(
            $id
        );
        if ($conn->Execute($sql, $params) === false) {
            print 'error deleting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        $sql = "UPDATE incident SET type_id = 'Generic' WHERE type_id = ?";
        $params = array(
            $id
        );
        if ($conn->Execute($sql, $params) === false) {
            print 'error deleting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
        $infolog = array($id);
        Log_action::log(54, $infolog);
    }
}

/* Incident custom*/
	
class Incident_custom {
	    
	public $id;
	public $incident_id;
	public $name;
	public $type;
	public $content;
	
	function get_id()          { return $this->id; }
    function get_incident_id() { return $this->incident_id; }
    function get_name()        { return $this->name; }
	function get_type()        { return $this->type; }
    function get_content()     { return $this->content; }	
	
	public function __construct($id, $incident_id, $name, $type, $content){
		$this->id          = $id;
		$this->incident_id = $incident_id;
		$this->name        = $name;
		$this->type        = $type;
		$this->content     = $content;
	;}	
	
	function get_list($conn, $args) { 	
	
		$list = array();
        $query = OssimQuery("SELECT * FROM incident_custom $args");
						
        if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } 
		else 
		{
            while (!$rs->EOF) 
			{
                $list[$rs->fields["name"]] = new Incident_custom($rs->fields["id"], $rs->fields["incident_id"], $rs->fields["name"], $rs->fields["type"], $rs->fields["content"]);
                $rs->MoveNext();
            }
        }
        return $list;
	
	}	
	
    function get_custom_types($conn, $id) {
        $list = array();
        $query = OssimQuery("SELECT * FROM incident_custom_types WHERE id=? ORDER BY ord ASC");
						
        if (!$rs = & $conn->Execute($query,array($id))) {
            print $conn->ErrorMsg();
        } else {
            while (!$rs->EOF) {
                $list[] = array("id"       => $rs->fields['id'], 
								"name"     => $rs->fields['name'], 
								"type"     => $rs->fields['type'], 
								"options"  => $rs->fields['options'],
								"required" => $rs->fields['required'],
								"ord"      => $rs->fields['ord']);
                $rs->MoveNext();
            }
        }
        return $list;
    }  
	
	function insert_custom($conn, $params) {
        $sql = "INSERT IGNORE into incident_custom_types ( id, name, type, options, required, ord) VALUES (?, ?, ?, ?, ?, ?)";
        
		if ($conn->Execute($sql, $params) == false) {
            print '<div id="info_error" class="ct_error" style="display: block">Error inserting: ' . $conn->ErrorMsg() . '<br/></div>';
            exit;
        }
    }
	
    function delete_custom($conn, $id, $name) {
        $sql = "DELETE FROM incident_custom_types WHERE id=? AND name=?";
        $params = array(
            $id,
            $name
        );
        if ($conn->Execute($sql, $params) == false) {
            print '<div id="info_error" class="ct_error" style="display: block">Error inserting: ' . $conn->ErrorMsg() . '<br/></div>';
            exit;
        }
    }
	
	function update_custom($conn, $custom_name, $custom_type, $custom_options, $custom_required,  $inctype_id, $custom_old_name) {
    	
        $sql = "UPDATE incident_custom_types SET name= ?, type= ?, options= ?, required=? WHERE id = ? and name = ?";
		
		$params = array(
           	$custom_name,
			$custom_type, 
			$custom_options, 
			$custom_required,
			$inctype_id,
			$custom_old_name
        );
			
						
		if ($conn->Execute($sql, $params) == false) {
            print '<div id="info_error" class="ct_error" style="display: block">Error updating: ' . $conn->ErrorMsg() . '<br/></div>';
            exit;
        }
    }
	
	function update_ord($conn, $oldpos, $newpos, $inctype_id) {
    	
    	if ($oldpos<0) $oldpos=0; // safe check
    	if ($newpos<0) $newpos=0;
        $sql = "UPDATE incident_custom_types SET ord=-1 WHERE id = ? AND ord = ?";
		if ($conn->Execute($sql, array($inctype_id,$newpos)) == false) {
            print '<div id="info_error" class="ct_error" style="display: block">Error updating ord: ' . $conn->ErrorMsg() . '<br/></div>';
            exit;
        }        
        $sql = "UPDATE incident_custom_types SET ord=? WHERE id = ? AND ord = ?";
		if ($conn->Execute($sql, array($newpos,$inctype_id,$oldpos)) == false) {
            print '<div id="info_error" class="ct_error" style="display: block">Error updating ord: ' . $conn->ErrorMsg() . '<br/></div>';
            exit;
        }        
        $sql = "UPDATE incident_custom_types SET ord=? WHERE id = ? AND ord = -1";
		if ($conn->Execute($sql, array($oldpos,$inctype_id)) == false) {
            print '<div id="info_error" class="ct_error" style="display: block">Error updating ord: ' . $conn->ErrorMsg() . '<br/></div>';
            exit;
        }        
    }
	
	function get_next_ord($conn, $inctype_id) {
    	
		$query = OssimQuery("SELECT max(ord) as max FROM incident_custom_types WHERE id = ?");
        if (!$rs = & $conn->Execute($query,array($inctype_id))) {
            print '<div id="info_error" class="ct_error" style="display: block">Error get maximum order: ' . $conn->ErrorMsg() . '<br/></div>';
            exit;
        } else {
            if (!$rs->EOF)
            	$max = $rs->fields['max'] + 1;
            else
            	$max = 1;
        }
        return $max;
    }
}
?>
