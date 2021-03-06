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
* - match_os()
* Classes list:
*/

require_once ('classes/Session.inc');
require_once ('classes/Plugin.inc');

if ( !Session::menu_perms("MenuEvents", "EventsHidsConfig") )
	Session::unallowed_section(null, 'noback', "MenuEvents", "EventsHidsConfig");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title> <?php echo gettext("OSSIM Framework"); ?> </title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Pragma" content="no-cache"/>
	<link rel="stylesheet" type="text/css" href="../style/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/ossec.css"/>
	<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
	
	<script type="text/javascript">
	var messages = new Array();
		messages[0]  = '<div id="oss_load" style="height:20px; width: 100%; font-size:12px; text-align:center;"><img src="images/loading.gif" border="0" align="absmiddle"/><span style="padding-left: 5px;"><?php echo _("Processing action...")?> </span></div>';
	</script>
	
	<script type="text/javascript">
				
		
		function show_tab_content(tab)
		{
			$("ul.oss_tabs li").removeClass("active"); //Remove any "active" class
			$(tab).addClass("active"); //Add "active" class to selected tab
			$(".tab_content").hide(); //Hide all tab content
			var activeTab = $(tab).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			$(activeTab).show(); //Fade in the active content
			return false;
		}
		
		function load_tab1()
		{
			var extra = '';
			execute_action("status", "#ossc_result", extra);
		}
		
		function load_tab2()
		{
			var extra = $('#oss_num_line').val();
			execute_action("ossec_log", "#logs_result", extra);
		}
		
		function load_tab3()
		{
			var extra = $('#alert_num_line').val();
			execute_action("alerts_log", "#alerts_result", extra);
		}
		
		
		function execute_action(action, div_load, extra)
		{
			//Load img
									
			$(div_load).html(messages[0]);
			
			$("#oss_load").css('padding', "40px 0px");
			
			var data = "action="+action+"&extra="+extra;
			
			$.ajax({
				type: "POST",
				url: "ajax/ossec_control_actions.php",
				data: data,
				success: function(msg){
					
					if ( msg.match("Illegal action") != null )	
						$(div_load).html("<div class='oss_error' style='width:90%'><div style='padding: 5px 10px 10px 50px;'>"+msg+"</div></div>");
					else
					{
						var status = msg.split("###");
						var html   = msg;
						
						if (div_load == "#ossc_result")
						{
							if (status[0] == "0")
							{
								
								$('#ossc_buttons_actions').html(status[2]);
								bind_action ('#cont_system_action');
								bind_action ('#cont_db_action');
								bind_action ('#cont_cs_action');
								bind_action ('#cont_al_action');
								bind_action ('#cont_dbg_action');
							}
							
							html = status[1];
						}
					}
																	
					$(div_load).html('');
					$(div_load).html("<div style='padding: 5px 10px 10px 10px; width:900px;'>"+html+"</div>");
				
				}
			});
			
		}
		
		
		
		function bind_action (id)
		{
			switch (id){
								
				case "#cont_db_action":
									
					$(id+ ' input').bind('click', function() {
						var action = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable_db" : "disable_db";
						var extra  = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable database" : "disable database";
						execute_action(action, "#ossc_result", extra);
					});
				break;
				
				case "#cont_cs_action":
					$(id+ ' input').bind('click', function() {
						var action = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable_cs" : "disable_cs";
						var extra  = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable client-syslog" : "disable client-syslog";
						execute_action(action, "#ossc_result", extra);
					});
				break;
				
				case "#cont_al_action":
					$(id+ ' input').bind('click', function() {
						var action = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable_al" : "disable_al";
						var extra  = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable agentless" : "disable agentless";
						execute_action(action, "#ossc_result", extra);
					});
				break;
				
				case "#cont_dbg_action":
					$(id+ ' input').bind('click', function() {
						var action = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable_dbg" : "disable_dbg";
						var extra  = ( $(this).val() == "<?php echo _("Enable")?>" ) ? "enable debug" : "disable debug";
						execute_action(action, "#ossc_result", extra);
					});
				break;
				
				case "#cont_system_action":
					
					
					$(id+ ' input').bind('click', function() {
					
						
						var action = $(this).val();
						var extra  = '';
						
						if ( action == "<?php echo _("Start")?>" )
							var action = "Start";
						else if ( action == "<?php echo _("Restart")?>" )
							var action = "Restart";
						else
						{
							if ( action == "<?php echo _("Restart")?>" )
								var action = "Stop";
						}
										
						execute_action(action, "#ossc_result", extra);
					});
				break;
			}
		}
		
		
		
		$(document).ready(function() {
			
			//Tabs
			$("ul.oss_tabs li:first").addClass("active");
			
			$('#oss_num_line').bind('change', function() { load_tab2(); });
			
			$('#alert_num_line').bind('change', function() { load_tab3(); });
			
			$("ul.oss_tabs li").click(function(event) { 
				event.preventDefault(); 
				show_tab_content(this); 
			});
			
			$("#link_tab1, #refresh").click(function(event) { load_tab1();});
			
			$("#link_tab2").click(function(event) { load_tab2(); });
			
			$("#link_tab3").click(function(event) { load_tab3();});
			
			$("#show_actions").click(function(event) { 
				event.preventDefault();
				
				if ( $("#show_actions").hasClass('hide') )
				{
					$("#show_actions").removeClass();
					$("#show_actions").addClass('show');
					$("#show_actions span").html('<?php echo _("Hide Actions")?>');
									  
					$('#ossc_actions').css('display', 'block');
					$('#ossc_actions').css('height', '100px');
				}
				else
				{
					$("#show_actions").removeClass();
					$("#show_actions").addClass('hide');
					$("#show_actions span").html('<?php echo _("Show Actions")?>');
					
					$('#ossc_actions').css('display', 'none');
					$('#ossc_actions').css('height', '1px');
								
				}
			});
			
			bind_action ('#cont_db_action');
			bind_action ('#cont_cs_action');
			bind_action ('#cont_al_action');
			bind_action ('#cont_dbg_action');
			bind_action ('#cont_system_action');
					
		});
	
	</script>
	
	<style type='text/css'>
		.generic_tab {width: 80%; margin: 20px auto;}
		.generic_tab table{ width: 100%; background: transparent;}
				
		.generic_tab th { height: 20px;}
				
		#ossc_options { width: 60px;}
		div .button {float: none; margin:0px; width: 55px;}
				
		.div_pre {
			background-color: #FFFFFF;
			border: none;
			font-family: Courier New,Courier, monospace;
			font-size: 12px;
			text-align: left;
			overflow:auto;
		}
		
		#ossc_result,#alerts_result,#logs_result, #table_ossc_buttons_actions {
			margin: auto; 
			width: 100%; 
			border: 1px solid #D3D3D3;
			-moz-border-radius:4px;
			-webkit-border-radius: 4px;
			-khtml-border-radius: 4px;
			border: solid 1px #D2D2D2;
		}
						
		.cont_num_line {border: none !important; padding: 0px 0px 10px 0px; text-align:left; font-size:11px;}
		.cont_num_line select {width: 100px; margin-left: 5px;}
		
		.log { height: 380px;}
		
		.bold {font-weight: bold;}
					
		html ul.oss_tabs li.active a:hover  {cursor:pointer !important;}
		
		#ossc_actions {padding-bottom: 20px;}
				
		.text_ossc_actions {padding: 10px 0px; text-align:left; border: none !important;}
		
		#table_ossc_actions { width: 100%;}
		#table_ossc_actions th {width: 120px;}
		
		#table_ossc_buttons_actions td { padding-bottom: 15px;}
						
		.noborder {border: none !important;}
		
		.pad10 {padding-top: 10px;}
		
		.running {margin: 0px 5px 0px 5px; color:#15B103; font-weight: bold; font-size: 11px;}
		.not_running {margin: 0px 5px 0px 5px; color:#E54D4D; font-weight: bold; font-size: 11px;}
		
		.headerpr {margin-bottom: 5px;}
		
		#refresh img { margin-left: 5px;}
		
		.bottom_link {padding: 0px 0px 20px 0px; font-size: 11px; font-style: italic; font-weight: bold;}
		
		#cont_ref {clear: both; height: 22px;}
						
	</style>
</head>

<body>

<?php

include ("../hmenu.php"); 

//Link to SIEM
$db 	       = new ossim_db();
$conn      	   = $db->connect();
$oss_p_id_name = Plugin::get_id_and_name($conn, "WHERE name LIKE 'ossec%'");
$db->close($conn);

$oss_plugin_id = implode(",",array_flip ($oss_p_id_name));

$link_siem     = "../forensics/base_qry_main.php?&plugin=".$oss_plugin_id."&num_result_rows=-1&submit=Query+DB&current_view=-1&sort_order=time_d&hmenu=Forensics&smenu=Forensics";


//Ossec Status
exec ("sudo /var/ossec/bin/ossec-control status", $result);
$result_1 = implode("<br/>", $result);
$result_1 = str_replace("is running", "<span style='font-weight: bold; color:#15B103;'>is running</span>", $result_1);
$result_1 = str_replace("not running", "<span style='font-weight: bold; color:#E54D4D;'>not running</span>", $result_1);


//Ossec Control

$pattern      = "/ossec-analysisd is running|ossec-syscheckd is running|ossec-remoted is running|ossec-monitord is running/";
$pattern_db   = "/ossec-dbd is running/";
$pattern_cs   = "/ossec-csyslogd is running/";
$pattern_al   = "/ossec-agentlessd is running/";
$pattern_dbg  = "/ossec-analysisd -d|ossec-syscheckd -d|ossec-remoted -d|ossec-monitord -d | ossec-analysisd -d/";

$status = implode("", $result);

preg_match_all($pattern, $status, $match);

if ( count($match[0]) < 4)
{
	$system_action    = "<span class='not_running'>"._("Ossec service is down")."</span><br/><br/>
						<input type='button' id='system_start' class='lbutton' value='"._("Start")."'/>"; 
						

}
else
	$system_action   = "<span class='running'>"._("Ossec service is up")."</span><br/><br/>
						<input type='button' id='system_stop' class='lbuttond' value='"._("Stop")."'/>"; 
					

$system_action .= "<input type='button' id='system_restart' class='lbutton' value='"._("Restart")."'/>";
	

preg_match($pattern_cs, $status, $match_cs);

$syslog_action    = ( count($match_cs) < 1 ) ? "<span class='not_running'>Client-syslog "._("not running")."</span><br/><br/><input type='button' id='cs_enable' class='lbutton' value='"._("Enable")."'/>" : "<span class='running'>Client-syslog "._("is running")."</span><br/><br/><input type='button' id='cs_disable' class='lbuttond' value='"._("Disable")."'/>";

preg_match($pattern_db, $status, $match_db);
$database_action  = ( count($match_db) < 1 ) ? "<span class='not_running'>Database "._("not running")."</span><br/><br/><input type='button' id='db_enable' class='lbutton' value='"._("Enable")."'/>" : "<span class='running'>Database "._("is running")."</span><br/><br/><input type='button' id='db_disable' class='lbuttond' value='"._("Disable")."'/>";


preg_match($pattern_al, $status, $match_al);
$agentless_action = ( count($match_al) < 1 ) ? "<span class='not_running'>Agentless "._("not running")."</span><br/><br/><input type='button' id='al_enable' class='lbutton' value='"._("Enable")."''/>" : "<span class='running'>Agentless "._("is running")."</span><br/><br/><input type='button' id='al_disable' class='lbuttond' value='"._("Disable")."'/>";

exec ("ps -ef | grep ossec | grep -v grep", $res_dbg);
$status_dbg = implode('', $res_dbg);
preg_match_all($pattern_dbg, $status_dbg, $match_dbg);
$debug_action     = ( count($match_dbg[0]) < 1 ) ? "<span class='not_running'>Debug "._(" is disabled")."</span><br/><br/><input type='button' id='dbg_enable' class='lbutton' value='"._("Enable")."''/>" : "<span class='running'>Debug "._("is enabled")."</span><br/><br/><input type='button' id='dbg_disable' class='lbuttond' value='"._("Disable")."'/>";

?>

	<div id='container_center'>

		<table id='tab_menu'>
			<tr>
				<td id='oss_mcontainer'>
					<ul class='oss_tabs'>
						<li id='litem_tab1'><a href="#tab1" id='link_tab1'><?php echo _("Ossec Control")?></a></li>
						<li id='litem_tab2'><a href="#tab2" id='link_tab2'><?php echo _("Ossec Log")?></a></li>
						<li id='litem_tab3'><a href="#tab3" id='link_tab3'><?php echo _("Alerts Log")?></a></li>
					</ul>
				</td>
			</tr>
		</table>
		
		<table id='tab_container' class='oss_control'>
			<tr>
				<td>
					<div id='tab1' class='generic_tab tab_content'>
						
						<div class='text_ossc_actions'>
							<img border="0" align="absmiddle" src="../pixmaps/arrow_green.gif">
							<a id='show_actions' class='show'><span class='bold'><?php echo _("Hide actions")?></span></a>
						</div>
						
						<div id='ossc_actions'>
								
							<table id='table_ossc_actions'>
								<tr>
									<th class='headerpr' colspan='5'><?php echo _("Actions")?></th>
								</tr>
								<tr id='ossc_buttons_actions'>
									<td>
										<table id='table_ossc_buttons_actions'>
											<td class='noborder center pad10' id='cont_db_action'><?php echo $database_action ?></td>
											<td class='noborder center pad10' id='cont_cs_action'><?php echo $syslog_action ?></td>
											<td class='noborder center pad10' id='cont_al_action'><?php echo $agentless_action ?></td>
											<td class='noborder center pad10' id='cont_dbg_action'><?php echo $debug_action ?></td>
											<td class='noborder center pad10' id='cont_system_action'><?php echo $system_action ?></td>
										</table>
									</td>
								</tr>
							</table>
						</div>
												
						<div class='headerpr' id='ossec_header'>
							<a id='refresh'><span><?php echo _("Ossec Output");?></span><img align='absmiddle' border="0" src="../pixmaps/refresh.png" title='<?php echo _("Refresh Output")?>'></a>
						</div>
						<div id='ossc_result' class='div_pre'>
							<div style='padding: 5px 10px 10px 10px;'><?php echo $result_1;?></div>
						</div>
					
					</div>
					
					<div id='tab2' class='generic_tab tab_content' style='display:none;'>
						
						<div class='cont_num_line'>
							<span class='bold'><?php echo _("View")?>:</span>
							<select name='oss_num_line' id='oss_num_line'>
								<option value='50' selected='selected'>50</option>
								<option value='100'>100</option>
								<option value='250'>250</option>
								<option value='500'>500</option>
								<option value='5000'>5000</option>
							</select>
						</div>
						<div class='headerpr' id='logs_header'><?php echo _("Ossec Log");?></div>
						<div id='logs_result' class='log div_pre'></div>
					
					</div>
					
					<div id='tab3' class='generic_tab tab_content' style='display:none;'>
						
						<div class='cont_num_line'>
							<span class='bold'><?php echo _("View")?>:</span>
							<select name='alert_num_line' id='alert_num_line'>
								<option value='50' selected='selected'>50</option>
								<option value='100'>100</option>
								<option value='250'>250</option>
								<option value='500'>500</option>
								<option value='5000'>5000</option>
							</select>
						</div>
						<div class='headerpr' id='alerts_header'><?php echo _("Alerts log");?></div>
						<div id='alerts_result' class='log div_pre'></div>
						
					</div>
				</td>
			</tr>
			
			<tr>
				<td class='bottom_link'>
					<a href='<?php echo $link_siem?>' target='main'>
					<span><?php echo _("If you're looking for the HIDS events please go to Analysis -> SIEM")?></span>
					</a>
				</td>
			</tr>
		</table>	
		
	</div>
</body>
</html>

