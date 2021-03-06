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
* - Response_port()
* - get_response_id()
* - get_port()
* - get_type()
* - get_list()
* - insert()
* Classes list:
* - Response_port
*/
require_once ("ossim_db.inc");
class Response_port {
    var $response_id;
    var $port;
    var $type;
    function Response_port($response_id, $port, $type) {
        $this->response_id = $response_id;
        $this->port = $port;
        $this->type = $type;
    }
    function get_response_id() {
        return $this->response_id;
    }
    function get_port() {
        return $this->port;
    }
    function get_type() {
        return $this->type;
    }
    function get_list($conn, $args = "") {
        $query = OssimQuery("SELECT * FROM response_port $args");
        if (!$rs = & $conn->Execute($query)) {
            print $conn->ErrorMsg();
        } else {
            $list = array();
            while (!$rs->EOF) {
                $list[] = new Response_port($rs->fields["response_id"], $rs->fields["port"], $rs->fields["_type"]);
                $rs->MoveNext();
            }
        }
        return $list;
    }
    function insert($conn, $port, $type) {
        $sql = "INSERT IGNORE INTO response_port (response_id, port, _type)
            VALUES  (LAST_INSERT_ID(), ?, ?)";
        $params = array(
            $port,
            $type
        );
        if ($conn->Execute($sql, $params) === false) {
            print 'error inserting: ' . $conn->ErrorMsg() . '<BR>';
            exit;
        }
    }
}
?>

