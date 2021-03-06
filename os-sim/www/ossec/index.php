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


require_once ('classes/Session.inc');
require_once ('classes/Xml_parser.inc');
require_once ('conf/_conf.php');
require_once ('utils.php');



if ( !Session::menu_perms("MenuEvents", "EventsHidsConfig") )
	Session::unallowed_section(null,'noback', "MenuEvents", "EventsHidsConfig");
	

$filename = $rules_file.$editable_files[0];
		
$error      = false;
$error_conf = null;

$_SESSION["_current_file"]   = $editable_files[0];

if ( file_exists( $filename) )
{
	$result = test_conf(); 
			
	if ( $result !== true )
	{
		$error = true;
		$link_txt    = _("Configuration error in file")." ".basename($ossec_conf)." "._("and/or")." ".$editable_files[0];
		$info_conf   = "<span style='font-weight: bold;'>$link_txt<a onclick=\"$('#msg_errors').toggle();\"> ["._("View errors")."]</a><br/></span>";
		$info_conf  .= "<div id='msg_errors'>$result</div>";
		$error_conf  = "<div id='parse_errors' class='oss_error'>$info_conf</div>";
	}
	else
	{
		$file_xml = @file_get_contents ($filename, false);
				
		if ($file_xml === false)
			$error = true;
		else
		{
						
			$_level_key_name             = set_key_name($_level_key_name, $file_xml);
			$_SESSION['_level_key_name'] = $_level_key_name;
			
			$xml_obj=new xml($_level_key_name);
			$xml_obj->load_file($filename);
											
			$array_xml=$xml_obj->xml2array();
							
			$tree_json = array2json($array_xml, $filename);
			
			$_SESSION['_tree_json'] = $tree_json;
			$_SESSION['_tree']      = $array_xml;
			
			$file_xml               = clean_string($file_xml);
		}	
	}
}
else
	$error  = true;

	
if ($error == true)
{
	$file_xml               = '';
	$tree_json              = "{title:'<span>".$filename."</span>', icon:'../../pixmaps/theme/any.png', addClass:'size12', isFolder:'true', key:'1', children:[{title: '<span>"._("No Valid XML File")."</span>', icon:'../../pixmaps/theme/ltError.gif', addClass:'bold_red', key:'load_error'}]}";
	$_SESSION['_tree_json'] = $tree_json;
	$_SESSION['_tree']      = array();
}
else
    $info = "<div id='msg_init'><div class='oss_info'><span>"._("Click on a brach to display a node")."</span></div></div>";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title> <?php echo gettext("OSSIM Framework"); ?> </title>
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
	<meta http-equiv="Pragma" content="no-cache"/>
	<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
	<script type='text/javascript' src='../js/codemirror/codemirror.js' ></script>
	
	<!-- Dynatree libraries: -->
	<script type="text/javascript" src="../js/jquery-ui-1.8.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery.cookie.js"></script>
	<script type="text/javascript" src="../js/jquery.dynatree.js"></script>
		
	<link type="text/css" rel="stylesheet" href="../style/tree.css" />
	
	<!-- Autocomplete libraries: -->
	<script type="text/javascript" src="../js/jquery.autocomplete.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="../style/jquery.autocomplete.css"/>
	
	<!-- Elastic textarea: -->
	<script type="text/javascript" src="../js/jquery.elastic.source.js" charset="utf-8"></script>
	
	<!-- Own libraries: -->
	<script type='text/javascript' src='../js/utils.js'></script>
	<script type="text/javascript" src="js/rules.js"></script>
	
	<script type='text/javascript'>
			
		var messages = new Array();
			messages[0]  = '<div class="reload"><img src="../pixmaps/theme/loading2.gif" border="0" align="absmiddle"/> <?php echo _("Re-loading data...") ?></div>';
			messages[1]  = '<img src="images/loading.gif" border="0" align="absmiddle"/><span><?php echo _("Loading data ... ")?></span>';
			messages[2]  = '<?php echo _("Failure: File does not exist")?>';
			messages[3]  = '<?php echo _("Failure to read XML file")?>';
			messages[4]  = '<?php echo _("Failure to create temporary copy from XML file")?>';
			messages[5]  = '<?php echo _("Failure to update XML file")?>';
			messages[6]  = '<?php echo _("XML file update successfully")?>';
			messages[7]  = '<?php echo _("Error to load tree")?>';
			messages[8]  = '<?php echo _("Unauthorized action. There must be one child at least")?>';
			messages[9]  = '<?php echo _("Rules File is empty")?>';
			messages[10] = '<?php echo _("Click on a brach to edit a node")?>';
			messages[11] = "<?php echo _("File")." <strong>$rules_file</strong> "._("doesn't exist or you don't have permission to access")?>";
			messages[12] = '<?php echo _("File name not allowed")?>';
			messages[13] = '<?php echo _("Configuration error in file")." ".basename($ossec_conf)." "._("and/or")." ".$editable_files[0]?>';
			messages[14] = '<?php echo _("View errors")?>';
			messages[15] = '<?php echo _("File not editable")?>';
			messages[16] = '<?php echo _("First save the changes then you can edit")?>';
			messages[17] = '<?php echo _("Re-loading in")?>';
			messages[18] = '<?php echo _("second(s)")?>';
							
		var label = new Array();
			label[0]  = '<?php echo _("Attribute")?>';
			label[1]  = '<?php echo _("Text Node")?>';
			label[2]  = '<?php echo _("Add")?>';	
			label[3]  = '<?php echo _("Delete")?>';
			label[4]  = '<?php echo _("Clone")?>';
			label[5]  = '<?php echo _("Show Attributes")?>';
			label[6]  = '<?php echo _("Arrow")?>';	
			label[7]  = '<?php echo _("Text Node Attributes")?>';
			label[8]  = '<?php echo _("Name")?>';	
			label[9]  = '<?php echo _("Value")?>';	
			label[10] = '<?php echo _("Actions")?>';	
			label[11] = '<?php echo _("Hide Attributes")?>';
			label[12] = '<?php echo _("Update")?>';
			label[13] = '<?php echo _("Updating ...")?>';			
					
		
		//AutoComplete
			
		content_ac = ["var", "accuracy", "frequency", "id","ignore", "level", "maxsize", "name", "timeframe",
					  "match","regex","decoded_as","category","srcip","dstip","user","program_name","hostname","time",		
					  "weekday", "url","if_sid","if_group","if_level","if_matched_sid","if_matched_group","if_matched_level",
					  "same_source_ip","same_source_port","same_location","description","list"	
					];
		
		content_ac.sort();
	
		var layer          = null;
		var nodetree       = null;
		var i              = 1;
		var editable_files = ['<?php echo implode("','", $editable_files)?>'];	
		var rules_files    = '<?php echo $rules_file;?>';
	    var editor         = null;
		
	
	
	$(document).ready(function() {
		
		var error   = '<?php echo $error;?>';
		/* Tabs */
		
		if ( error == true ) 
		{	
			$("ul.oss_tabs #litem_tab2").addClass("active");
			
			$('#link_tab1').addClass("dis_tab");
			
			$(".button").html("<input type='button' class='save' id='dis_send' disabled='disabled' value='"+label[12]+"'/>");
			
			$("#litem_tab2").click(function(event) { 
				event.preventDefault(); 
				var tab = $("#litem_tab2");
				show_tab_content(tab);
			});
			
			$("#clone_tree img").addClass("dis_icon");
			$("#link_tab2").bind('click', function()  { load_tab2('local_rules.xml'); });
			$('#send').bind('click', function()       { save(editor); });
			
			load_tab2('local_rules.xml');
			var tab = $("#litem_tab2");
			show_tab_content(tab);
			
			load_tree("<?php echo base64_encode($_SESSION['_tree_json'])?>", 'load_error', 'normal');
			
		} 
		else 
		{
			//On Click Event
		    $("ul.oss_tabs li:first").addClass("active");
		
			$("ul.oss_tabs li").click(function(event) { event.preventDefault(); show_tab_content(this); });
			
			$("#link_tab1").bind('click', function()  { load_tab1(); });
			$("#link_tab2").bind('click', function()  { load_tab2(''); });
						
			fill_rules('rules', "<?php echo $filename?>");
			$('#clone_tree').bind('click', function() { draw_clone(); });
			$('#rules').bind('change', function()     { show_actions(); });
			$('#send').bind('click', function()       { save(editor); });
			
			load_tree("<?php echo base64_encode($_SESSION['_tree_json'])?>", 1, 'normal');
		}
	});
	
	
	
	</script>
	
	<link rel="stylesheet" type="text/css" href="../style/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/ossec.css" />
	<style type='text/css'>
		ul.oss_tabs li.active a:hover {cursor: pointer !important;} 
		#countdown{font-weight: bold;}
	</style>
	
</head>

<body>

<?php include ("../hmenu.php"); ?>

	<div id='container_center'>
	
		<table id='tab_menu'>
			<tr>
				<td id='oss_mcontainer'>
					<ul class='oss_tabs'>
						<li id='litem_tab1'><a href="#tab1" id='link_tab1'><?php echo _("Rules Files")?></a></li>
						<li id='litem_tab2'><a href="#tab2" id='link_tab2'><?php echo ucfirst($editable_files[0])?></a></li>
					</ul>
				</td>
			</tr>
		</table>
		
		<table id='tab_container'>
			<tr>
				<td id='oss_clcontainer'>
					<div id='tree_container_top'>
						<select id='rules' name='rules'></select>
						<span id='tree_actions'>
							<span style='padding-left:10px;'><a id='clone_tree'><img src='images/clone.png' alt='Edit' title='Clone file' align='absmiddle'/></a></span>
						</span>
					</div>
					<div id='tree_container_bt'></div>
					
				</td>
				
				<td id='oss_crcontainer'>
					<div id='results_container'><div id='results'><div id='msg_edit'><?php echo ($error_conf != null) ? $error_conf : null?></div></div></div>
					<div class="tab_container">
						
						<div id="tab1" class="tab_content"><?php echo ($error == false) ? $info : null?></div>
						
						<div id="tab2" class="tab_content" style='display:none;'>
							<div id='container_code'><textarea id="code"></textarea></div>
							<div class='buttons_box'>
								<div><input type='button' class='save' id='send' value='<?php echo _("Update")?>'/></div>
							</div>
						</div>
						
					</div>
					
					<div class='notice'><div><span>(*)<?php echo _("You must restart Ossec for the changes to take effect")?></span></div></div>
				</td>
			</tr>
		</table>
		
	</div>


</body>

</html>

