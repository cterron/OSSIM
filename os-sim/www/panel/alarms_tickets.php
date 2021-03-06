<?php
require_once ('ossim_db.inc');
require_once ('classes/Alarm.inc');
require_once ('classes/Incident.inc');
require_once ('classes/Util.inc');
require_once ('classes/Security.inc');
require_once ('classes/Session.inc');
require_once 'sensor_filter.php';
Session::logcheck("MenuControlPanel", "ControlPanelExecutive");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  <title>Bar Charts</title>
	  <!--[if IE]><script language="javascript" type="text/javascript" src="../js/jqplot/excanvas.js"></script><![endif]-->
	  
	  <link rel="stylesheet" type="text/css" href="../js/jqplot/jquery.jqplot.css" />
		
	  <!-- BEGIN: load jquery -->
	  <script language="javascript" type="text/javascript" src="../js/jqplot/jquery-1.4.2.min.js"></script>
	  <!-- END: load jquery -->
	  
	  <!-- BEGIN: load jqplot -->
	  <script language="javascript" type="text/javascript" src="../js/jqplot/jquery.jqplot.min.js"></script>
	  <script language="javascript" type="text/javascript" src="../js/jqplot/plugins/jqplot.barRenderer.js"></script>
	  <script language="javascript" type="text/javascript" src="../js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
	  <script language="javascript" type="text/javascript" src="../js/jqplot/plugins/jqplot.pointLabels.min.js"></script>
	  <script language="javascript" type="text/javascript" src="../js/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
	  <script language="javascript" type="text/javascript" src="../js/jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
	  
	<style type="text/css">
		
		#chart .jqplot-point-label {
		  border: 1.5px solid #aaaaaa;
		  padding: 1px 3px;
		  background-color: #eeccdd;
		}

	</style>

<?php
$db = new ossim_db();
$conn = $db->connect();
$conn2 = $db->snort_connect();

$sensor_where = "";
$sensor_where_ossim = "";
if (Session::allowedSensors() != "") {
	$user_sensors = explode(",",Session::allowedSensors());
	$snortsensors = GetSnortSensorSids($conn2);
	$sids = array();
	foreach ($user_sensors as $user_sensor) {
		//echo "Sids de $user_sensor ".$snortsensors[$user_sensor][0]."<br>";
		if (count($snortsensors[$user_sensor]) > 0)
			foreach ($snortsensors[$user_sensor] as $sid) if ($sid != "")
				$sids[] = $sid;
	}
	if (count($sids) > 0) {
		$sensor_where = " AND sid in (".implode(",",$sids).")";
		$sensor_where_ossim = " AND alarm.snort_sid in (".implode(",",$sids).")";
	}
	else {
		$sensor_where = " AND sid in (0)"; // Vacio
		$sensor_where_ossim = " AND alarm.snort_sid in (0)"; // Vacio
	}
}
session_write_close();

//
if($conf->get_conf("backup_day")<=5){
    $_2Ago='INTERVAL -2 DAY';
    $_2Ago_div='';
    $_2Ago_interv='DATE_ADD(CURDATE(), INTERVAL -2 DAY)';
    //
    $_Week='INTERVAL -3 DAY';
    $_Week_div='';
    $_Week_interv='DATE_ADD(CURDATE(), INTERVAL -3 DAY)';
    //
    $_2Week='INTERVAL -4 DAY';
    $_2Week_div='';
    $_2Week_interv='DATE_ADD(CURDATE(), INTERVAL -4 DAY)';
    //
}elseif($conf->get_conf("backup_day")>=6&&$conf->get_conf("backup_day")<=10){
    $_2Ago='INTERVAL -2 DAY';
    $_2Ago_div='';
    $_2Ago_interv='DATE_ADD(CURDATE(), INTERVAL -2 DAY)';
    //
    $_Week='INTERVAL -3 DAY';
    $_Week_div='';
    $_Week_interv='DATE_ADD(CURDATE(), INTERVAL -3 DAY)';
    //
    $_2Week_value=($conf->get_conf("backup_day")-3)+2;
    $_2Week='INTERVAL -'.$_2Week_value.' DAY';
    $_2Week_div='';
    $_2Week_interv='DATE_ADD(CURDATE(), INTERVAL -'.$_2Week_value.' DAY)';
    //
}elseif($conf->get_conf("backup_day")>=11){
    $_2Ago='INTERVAL -2 DAY';
    $_2Ago_div='';
    $_2Ago_interv='DATE_ADD(CURDATE(), INTERVAL -2 DAY)';
    //
    $_Week='INTERVAL -6 DAY';
    $_Week_div='/7';
    $_Week_interv='NOW()';
    //
    $_2Week='INTERVAL -13 DAY';
    $_2Week_div='/14';
    $_2Week_interv='NOW()';
    //
}
// Alarms
$query = "SELECT * FROM 
(select count(*) as Today from alarm where alarm.timestamp > CURDATE()$sensor_where_ossim) as Today, 
(select count(*) as Yesterd from alarm where alarm.timestamp > DATE_ADD(CURDATE(), INTERVAL -1 DAY) and alarm.timestamp < CURDATE()$sensor_where_ossim) as Yesterd,
(select count(*)".$_2Ago_div." as 2DAgo from alarm where alarm.timestamp > DATE_ADD(CURDATE(), ".$_2Ago.") and alarm.timestamp < DATE_ADD(".$_2Ago_interv."$sensor_where_ossim, INTERVAL +1 DAY) ) as 2DAgo,
(select count(*)".$_Week_div." as Week from alarm where alarm.timestamp > DATE_ADD(CURDATE(), ".$_Week.") and alarm.timestamp < ".$_Week_interv."$sensor_where_ossim) as Seamana,
(select count(*)".$_2Week_div." as 2Weeks from alarm where alarm.timestamp > DATE_ADD(CURDATE(), ".$_2Week.") and alarm.timestamp < ".$_2Week_interv."$sensor_where_ossim) as 2Weeks ;";

if (!$rs = & $conn->Execute($query)) {
    print $conn->ErrorMsg();
    exit();
}
while (!$rs->EOF) {
    $values =  $rs->fields["Today"].",".
        $rs->fields["Yesterd"].",".
        $rs->fields["2DAgo"].",".
        $rs->fields["Week"].",".
        $rs->fields["2Weeks"];
    $rs->MoveNext();
}

// Tickets
$incident_list = Incident::search($conn, array("status"=>"Open","last_update"=>"CURDATE()"), "", "", 1, 99999999);
$today = Incident::search_count($conn);
$incident_list = Incident::search($conn, array("status"=>"Open","last_update"=>array("DATE_ADD(CURDATE(), INTERVAL -1 DAY)","CURDATE()")), "", "", 1, 999999999);
$yday = Incident::search_count($conn);
$incident_list = Incident::search($conn, array("status"=>"Open","last_update"=>array("DATE_ADD(CURDATE(), ".$_2Ago.")",$_2Ago_interv)), "", "", 1, 999999999);
$ago2 = Incident::search_count($conn);
$incident_list = Incident::search($conn, array("status"=>"Open","last_update"=>array("DATE_ADD(CURDATE(), ".$_Week.")",$_Week_interv)), "", "", 1, 999999999);
$week = Incident::search_count($conn);
$incident_list = Incident::search($conn, array("status"=>"Open","last_update"=>array("DATE_ADD(CURDATE(), ".$_2Week.")",$_2Week_interv)), "", "", 1, 999999999);
$week2 = Incident::search_count($conn);
$values2 = $today.",".$yday.",".$ago2.",".$week.",".$week2;
//

$db->close($conn);
$db->close($conn2);

//
$alarm_urls = "'../control_panel/alarm_console.php?num_alarms_page=50&hmenu=Alarms&smenu=Alarms&hour=00&minutes=00&hide_closed=1&date_from=".gmdate("Y-m-d",$timetz)."&date_to=".gmdate("Y-m-d",$timetz)."'";
$alarm_urls .= ",'../control_panel/alarm_console.php?num_alarms_page=50&hmenu=Alarms&smenu=Alarms&hour=00&minutes=00&hide_closed=1&date_from=".gmdate("Y-m-d",$timetz-86400)."&date_to=".gmdate("Y-m-d",$timetz)."'";
$alarm_urls .= ",'../control_panel/alarm_console.php?num_alarms_page=50&hmenu=Alarms&smenu=Alarms&hour=00&minutes=00&hide_closed=1&date_from=".gmdate("Y-m-d",$timetz-172800)."&date_to=".gmdate("Y-m-d",$timetz)."'";
$alarm_urls .= ",'../control_panel/alarm_console.php?num_alarms_page=50&hmenu=Alarms&smenu=Alarms&hour=00&minutes=00&hide_closed=1&date_from=".gmdate("Y-m-d",$timetz-604800)."&date_to=".gmdate("Y-m-d",$timetz)."'";
$alarm_urls .= ",'../control_panel/alarm_console.php?num_alarms_page=50&hmenu=Alarms&smenu=Alarms&hour=00&minutes=00&hide_closed=1&date_from=".gmdate("Y-m-d",$timetz-1209600)."&date_to=".gmdate("Y-m-d",$timetz)."'";
?>  
	<script class="code" type="text/javascript">
	
		var links = [<?=$alarm_urls?>];

		function myClickHandler(ev, gridpos, datapos, neighbor, plot) {
            //mouseX = ev.pageX; mouseY = ev.pageY;
            url = links[neighbor.pointIndex];
            if (neighbor.seriesIndex==1) url = '../incidents/index.php?status=&hmenu=Tickets&smenu=Tickets';
            if (typeof(url)!='undefined' && url!='') top.frames['main'].location.href = url;
        }
		var isShowing = -1;
		function myMoveHandler(ev, gridpos, datapos, neighbor, plot) {
			if (neighbor == null) {
	            $('#myToolTip').hide().empty();
	            isShowing = -1;
	        }
	        if (neighbor != null) {
	        	if (neighbor.pointIndex!=isShowing) {
	            	$('#myToolTip').html(neighbor.data[1]).css({left:gridpos.x, top:gridpos.y-5}).show();
	            	isShowing = neighbor.pointIndex
	            }
	        }
        }
        
		$(document).ready(function(){
			
			$.jqplot.config.enablePlugins = true;		
			$.jqplot.eventListenerHooks.push(['jqplotClick', myClickHandler]); 
			$.jqplot.eventListenerHooks.push(['jqplotMouseMove', myMoveHandler]);
						
			line1 = [<?=$values?>];
			line2 = [<?=$values2?>];
			
			plot1 = $.jqplot('chart', [line1, line2], {
			    legend:{show:true, location:'nw', rowSpacing:'2px'},
			    series:[
			        { pointLabels:{ show: false }, label:'<?=_("Alarms")?>', renderer:$.jqplot.BarRenderer }, 
			        { pointLabels:{ show: false }, label:'<?=_("Tickets")?>', renderer:$.jqplot.BarRenderer },
			    ],                                    
			    grid: { background: '#F5F5F5', shadow: false },
			    seriesColors: [ "#EFBE68", "#B5CF81" ],
			    axes:{
			        xaxis:{
			        	renderer:$.jqplot.CategoryAxisRenderer,
			        	ticks:['<?=_("Today")?>', '<?=_("-1 Day")?>', '<?=_("-2 Days")?>', '<?=_("Week")?>', '<?=_("2 Weeks")?>']
			        }, 
			        yaxis:{min:0, tickOptions:{formatString:'%d'}}
			    }
			});
			
			$('#chart').append('<div id="myToolTip"></div>');

		});
	</script>

    
    
  </head>
	<body style="overflow:hidden" scroll="no">
		<div id="chart" style="width:100%; height: 290px;"></div>
	</body>
</html>
