<?php
/**
* Class and Function List:
* Function list:
* Classes list:
*/
/*******************************************************************************
** OSSIM Forensics Console
** Copyright (C) 2009 OSSIM/AlienVault
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
** Built upon work by the BASE Project Team <kjohnson@secureideas.net>
*/
echo '<FORM ACTION="base_graph_main.php" METHOD="post">';
echo '<TABLE WIDTH="100%" BORDER="2" class="query" cellpadding="20">
          <TR>
           <TD COLSPAN=2>';
echo '<TABLE WIDTH="100%" BORDER="1"><TR>';
//  echo '<B>'.gettext("Chart Type:").'</B>&nbsp;
echo '<TD><B>What do you want to know:</B></TD><TD>
        <SELECT NAME="chart_type">
         <OPTION VALUE=" "  ' . chk_select($chart_type, " ") . '>' . gettext("{ chart type }") . '
         <OPTION VALUE="1" ' . chk_select($chart_type, "1") . '>' . gettext("Time (hour) vs. Number of Events") . '
         <OPTION VALUE="2" ' . chk_select($chart_type, "2") . '>' . gettext("Time (day) vs. Number of Events") . '
         <!--<OPTION VALUE="3" ' . chk_select($chart_type, "3") . '>' . gettext("Time (week) vs. Number of Events") . '-->
         <OPTION VALUE="4" ' . chk_select($chart_type, "4") . '>' . gettext("Time (month) vs. Number of Events") . '
         <!--<OPTION VALUE="5" ' . chk_select($chart_type, "5") . '>' . gettext("Time (year) vs. Number of Events") . '-->
         <OPTION VALUE="6" ' . chk_select($chart_type, "6") . '>' . gettext("Src. IP address vs. Number of Events") . '
         <OPTION VALUE="7" ' . chk_select($chart_type, "7") . '>' . gettext("Dst. IP address vs. Number of Events") . '
         <OPTION VALUE="8" ' . chk_select($chart_type, "8") . '>' . gettext("Dst. UDP Port vs. Number of Events") . '
         <OPTION VALUE="10" ' . chk_select($chart_type, "10") . '>' . gettext("Src. UDP Port vs. Number of Events") . '
         <OPTION VALUE="9" ' . chk_select($chart_type, "9") . '>' . gettext("Dst. TCP Port vs. Number of Events") . '
         <OPTION VALUE="11" ' . chk_select($chart_type, "11") . '>' . gettext("Src. TCP Port vs. Number of Events") . '
         <OPTION VALUE="12" ' . chk_select($chart_type, "12") . '>' . gettext("Sig. Classification vs. Number of Events") . '
         <OPTION VALUE="13" ' . chk_select($chart_type, "13") . '>' . gettext("Sensor vs. Number of Events") . '
         <OPTION VALUE="14" ' . chk_select($chart_type, "14") . '>' . 'Src countries vs. number of alerts
         <OPTION VALUE="15" ' . chk_select($chart_type, "15") . '>' . 'Src countries vs. number of alerts on a worldmap
         <OPTION VALUE="16" ' . chk_select($chart_type, "16") . '>' . 'Dst countries vs. number of alerts
         <OPTION VALUE="17" ' . chk_select($chart_type, "17") . '>' . 'Dst countries vs. number of alerts on a worldmap';
echo '</SELECT>
	      </TD></TR>';
//echo '&nbsp;&nbsp;<B>'.gettext("Plot type:").'</B> &nbsp;&nbsp;
echo '<TR><TD><B>How should it be displayed?</B></TD><TD>As&nbsp;
            <INPUT TYPE="radio" NAME="chart_style"
                   VALUE="bar" ' . chk_check($chart_style, "bar") . '> ' . gettext("bar") . ' &nbsp;&nbsp
            <INPUT TYPE="radio" NAME="chart_style"
                   VALUE="line" ' . chk_check($chart_style, "line") . '> ' . gettext("line") . ' &nbsp;&nbsp
            <INPUT TYPE="radio" NAME="chart_style"
                   VALUE="pie" ' . chk_check($chart_style, "pie") . '> ' . gettext("pie") . ' ';
echo '</TD></TR>';
//  echo '&nbsp;&nbsp;<B>'.gettext("Size: (width x height)").'</B>
echo '<TD><B>... with a size of:</B></TD><TD>(width x height)
        &nbsp;<INPUT TYPE="text" NAME="width" SIZE=4 VALUE="' . $width . '">
        &nbsp;<B>x</B>
        &nbsp;<INPUT TYPE="text" NAME="height" SIZE=4 VALUE="' . $height . '">
        &nbsp;&nbsp;<BR></TD></TR>';
// not implemented:
/*
//echo '&nbsp;&nbsp;<B>'.gettext("Plot Margins: (left x right x top x bottom)").'</B>
echo '<TR><TD><B>... and with margins of:</B></TD><TD>(left x right x top x bottom)<BR>
&nbsp;<INPUT TYPE="text" NAME="pmargin0" SIZE=4 VALUE="'.$pmargin0.'">
&nbsp;<B>x</B>
&nbsp;<INPUT TYPE="text" NAME="pmargin1" SIZE=4 VALUE="'.$pmargin1.'">
&nbsp;<B>x</B>
&nbsp;<INPUT TYPE="text" NAME="pmargin2" SIZE=4 VALUE="'.$pmargin2.'">
&nbsp;<B>x</B>
&nbsp;<INPUT TYPE="text" NAME="pmargin3" SIZE=4 VALUE="'.$pmargin3.'">
&nbsp;&nbsp;<BR></TD></TR>';
*/
echo '<TR><TD><B>Do you want to know<BR>the data just of a<BR>particular time frame?</B>&nbsp;(optional)</TD><TD>';
echo '<b>' . gettext("Chart Begin:") . '</B>&nbsp;
        <SELECT NAME="chart_begin_hour">
         <OPTION VALUE=" "  ' . chk_select($chart_begin_hour, " ") . '>' . gettext("{hour}") . "\n";
for ($i = 0; $i <= 23; $i++) echo "<OPTION VALUE=\"$i\" " . chk_select($chart_begin_hour, $i) . " >$i\n";
echo '</SELECT>
        <SELECT NAME="chart_begin_day">
         <OPTION VALUE=" "  ' . chk_select($chart_begin_day, " ") . '>' . gettext("{day}") . "\n";
for ($i = 1; $i <= 31; $i++) echo "<OPTION VALUE=\"$i\" " . chk_select($chart_begin_day, $i) . ">$i\n";
echo '</SELECT>
        <SELECT NAME="chart_begin_month">
         <OPTION VALUE=" "  ' . chk_select($chart_begin_month, " ") . '>' . gettext("{month}") . '
         <OPTION VALUE="01" ' . chk_select($chart_begin_month, "01") . '>' . gettext("January") . '
         <OPTION VALUE="02" ' . chk_select($chart_begin_month, "02") . '>' . gettext("February") . '
         <OPTION VALUE="03" ' . chk_select($chart_begin_month, "03") . '>' . gettext("March") . '
         <OPTION VALUE="04" ' . chk_select($chart_begin_month, "04") . '>' . gettext("April") . '
         <OPTION VALUE="05" ' . chk_select($chart_begin_month, "05") . '>' . gettext("May") . '
         <OPTION VALUE="06" ' . chk_select($chart_begin_month, "06") . '>' . gettext("June") . '
         <OPTION VALUE="07" ' . chk_select($chart_begin_month, "07") . '>' . gettext("July") . '
         <OPTION VALUE="08" ' . chk_select($chart_begin_month, "08") . '>' . gettext("August") . '
         <OPTION VALUE="09" ' . chk_select($chart_begin_month, "09") . '>' . gettext("September") . '
         <OPTION VALUE="10" ' . chk_select($chart_begin_month, "10") . '>' . gettext("October") . '
         <OPTION VALUE="11" ' . chk_select($chart_begin_month, "11") . '>' . gettext("November") . '
         <OPTION VALUE="12" ' . chk_select($chart_begin_month, "12") . '>' . gettext("December") . '
        </SELECT>
        <SELECT NAME="chart_begin_year">' . dispYearOptions($chart_begin_year) . '</SELECT>';
echo '<br><b>' . gettext("Chart End:") . '</B>&nbsp;&nbsp;&nbsp;&nbsp;
        <SELECT NAME="chart_end_hour">
         <OPTION VALUE=" "  ' . chk_select($chart_end_hour, " ") . '>' . gettext("{hour}") . "\n";
for ($i = 0; $i <= 23; $i++) echo "<OPTION VALUE=$i " . chk_select($chart_end_hour, $i) . ">$i\n";
echo '</SELECT>
        <SELECT NAME="chart_end_day">
         <OPTION VALUE=" "  ' . chk_select($chart_end_day, " ") . '>' . gettext("{day}") . "\n";
for ($i = 1; $i <= 31; $i++) echo "<OPTION VALUE=$i " . chk_select($chart_end_day, $i) . ">$i\n";
echo '</SELECT>
        <SELECT NAME="chart_end_month">
         <OPTION VALUE=" "  ' . chk_select($chart_end_month, " ") . '>' . gettext("{month}") . '
         <OPTION VALUE="01" ' . chk_select($chart_end_month, "01") . '>' . gettext("January") . '
         <OPTION VALUE="02" ' . chk_select($chart_end_month, "02") . '>' . gettext("February") . '
         <OPTION VALUE="03" ' . chk_select($chart_end_month, "03") . '>' . gettext("March") . '
         <OPTION VALUE="04" ' . chk_select($chart_end_month, "04") . '>' . gettext("April") . '
         <OPTION VALUE="05" ' . chk_select($chart_end_month, "05") . '>' . gettext("May") . '
         <OPTION VALUE="06" ' . chk_select($chart_end_month, "06") . '>' . gettext("June") . '
         <OPTION VALUE="07" ' . chk_select($chart_end_month, "07") . '>' . gettext("July") . '
         <OPTION VALUE="08" ' . chk_select($chart_end_month, "08") . '>' . gettext("August") . '
         <OPTION VALUE="09" ' . chk_select($chart_end_month, "09") . '>' . gettext("September") . '
         <OPTION VALUE="10" ' . chk_select($chart_end_month, "10") . '>' . gettext("October") . '
         <OPTION VALUE="11" ' . chk_select($chart_end_month, "11") . '>' . gettext("November") . '
         <OPTION VALUE="12" ' . chk_select($chart_end_month, "12") . '>' . gettext("December") . '
        </SELECT>
        <SELECT NAME="chart_end_year">' . dispYearOptions($chart_end_year) . '</SELECT></TD></TR>';
echo '<TR><TD><B>' . gettext("Chart Title:") . '</B></TD><TD>
		<INPUT TYPE="text" NAME="user_chart_title" SIZE="35" VALUE="' . $user_chart_title . '"></TD></TR>';
//  echo '&nbsp;&nbsp;<b>'.gettext("Chart Period:").'</B>&nbsp;
echo '<TR><TD><B>How many columns or elements do you want to see?</B>&nbsp</TD><TD>';
echo '<SELECT NAME="chart_interval">' . '<OPTION VALUE="0"  ' . chk_select($chart_interval, "0") . '>{all of them}' . /* gettext("no period"). */
'<OPTION VALUE="5"  ' . chk_select($chart_interval, "5") . '> 5 elements' . '<OPTION VALUE="10" ' . chk_select($chart_interval, "10") . '>10 elements' . /* gettext("7 (a week)"). */
'<OPTION VALUE="15" ' . chk_select($chart_interval, "15") . '>15 elements' . /* gettext("24 (whole day)"). */
'<OPTION VALUE="20" ' . chk_select($chart_interval, "20") . '>20 elements' . /* gettext("168 (24x7)"). */
'<OPTION VALUE="25" ' . chk_select($chart_interval, "25") . '>25 elements' . '<OPTION VALUE="30" ' . chk_select($chart_interval, "30") . '>30 elements' . '</SELECT><BR></TD></TR>';
echo '<TR><TD><B>... and starting from which element on?</B>&nbsp;</TD>' . '<TD>From element no.&nbsp<INPUT TYPE="text" NAME="element_start" SIZE="10" VALUE="' . $element_start . '"></TD></TR>';
// submit button
echo '<TR align=middle><TD colspan="2">';
echo '<BR><BR><div class="center"><INPUT TYPE="submit" NAME="submit" VALUE="' . gettext("Graph Events") . '" align=center></div><BR><BR>';
echo '</TR></TABLE>';
echo '<TR><TD>
  <ul id="zMenu">
    <li>' . gettext("X / Y AXIS CONTROLS") . ':<BR>
		<ul>
		    <BR>
        <TABLE WIDTH="100%" BORDER="1">
        <TR>
         <TD ALIGN="CENTER" WIDTH="50%"><B>' . gettext("X Axis") . '</B></TD>
         <TD ALIGN="CENTER" WIDTH="50%"><B>' . gettext("Y Axis") . '</B></TD>
        </TR>
        <TR>
         <TD>
           <B>' . gettext("Data Source ID:") . '</B> &nbsp;
           <SELECT NAME="data_source">
           <OPTION VALUE=" " ' . chk_select($data_source, " ") . '>{ data source (AG) }';
$temp_sql = "SELECT ag_id, ag_name FROM acid_ag";
$tmp_result = $db->baseExecute($temp_sql);
if (($tmp_result)) {
    while ($myrow = $tmp_result->baseFetchRow()) echo '<OPTION VALUE="' . $myrow[0] . '" ' . chk_select($data_source, $myrow[0]) . '>' . '[' . $myrow[0] . '] ' . $myrow[1];
    $tmp_result->baseFreeRows();
}
echo '</SELECT><BR>' . '<B>' . gettext("Minimum Threshold Value") . ':</B>
                 <INPUT TYPE="text" NAME="min_size" SIZE="5" VALUE=' . $min_size . '>
                 &nbsp;&nbsp;
                 <BR>
                 <INPUT TYPE="checkbox" NAME="rotate_xaxis_lbl" VALUE="1" ' . chk_check($rotate_xaxis_lbl, "1") . '>
                 &nbsp;
                 <B>' . gettext("Rotate Axis Labels (90 degrees)") . '</B><BR>
                 <INPUT TYPE="checkbox" NAME="xaxis_grid" VALUE="1"  ' . chk_check($xaxis_grid, "1") . '>
                  &nbsp;
                 <B>' . gettext("Show X-axis grid-lines") . '</B><BR>
                 <!--
                 Disabled because it unexpectedly prevents displaying
                 the whole bar instead of just suppressing the label... 
                 -->
                 <!--
                 <B>' . gettext("Display X-axis label every") . '
                 <INPUT TYPE="text" NAME="xaxis_label_inc" SIZE=4 VALUE=' . $xaxis_label_inc . '>
                 &nbsp; ' . gettext("data points") . '
                 -->
                 <INPUT TYPE="hidden" NAME="xaxis_label_inc" VALUE="1"><BR> 
         </TD>


         <TD VALIGN="top">
         <!--
         Logarithmic y-axis temporarily disabled, as the bars seem
         to be displayed in a wrong way, although the y-axis grid lines
         look correct
         -->
         <!--
           <INPUT TYPE="checkbox" NAME="yaxis_scale" VALUE="1" ' . chk_check($yaxis_scale, "1") . '>&nbsp;
           <B>' . gettext("Y-axis logarithmic") . '</B>
           <BR>
         --> 
           <INPUT TYPE="hidden" NAME="yaxis_scale" VALUE="0">
         
           

           <INPUT TYPE="checkbox" NAME="yaxis_grid" VALUE="1"  ' . chk_check($yaxis_grid, "1") . '>&nbsp;
           <B>' . gettext("Show Y-axis grid-lines") . '</B>
         </TD>
        </TR>
        </TABLE>
	</ul></li>

        </TD></TR>
     </TABLE>';
echo '</FORM><P><HR>';
echo '
 <!-- ************ JavaScript for Hiding Details ******************** -->
 <script type="text/javascript">
// <![CDATA[
function loopElements(el,level){
        for(var i=0;i<el.childNodes.length;i++){
                //just want LI nodes:
                if(el.childNodes[i] && el.childNodes[i]["tagName"] && el.childNodes[i].tagName.toLowerCase() == "li"){
                        //give LI node a className
                        el.childNodes[i].className = "zMenu"+level
                        //Look for the A and if it has child elements (another UL tag)
                        childs = el.childNodes[i].childNodes
                        for(var j=0;j<childs.length;j++){
                                temp = childs[j]
                                if(temp && temp["tagName"]){
                                        if(temp.tagName.toLowerCase() == "a"){
                                                //found the A tag - set class
                                                temp.className = "zMenu"+level
                                                //adding click event
                                                temp.onclick=showHide;
                                        }else if(temp.tagName.toLowerCase() == "ul"){
                                                //Hide sublevels
                                                temp.style.display = "none"
                                                //Set class
                                                temp.className= "zMenu"+level
                                                //Recursive - calling self with new found element - go all the way through
                                                loopElements(temp,level +1)
                                        }
                                }
                        }
                }
        }
}

var menu = document.getElementById("zMenu") //get menu div
menu.className="zMenu"+0 //Set class to top level
loopElements(menu,0) //function call

function showHide(){
        //from the LI tag check for UL tags:
        el = this.parentNode
        //Loop for UL tags:
        for(var i=0;i<el.childNodes.length;i++){
                temp = el.childNodes[i]
                if(temp && temp["tagName"] && temp.tagName.toLowerCase() == "ul"){
                        //Check status:
                        if(temp.style.display=="none"){
                                temp.style.display = ""
                        }else{
                                temp.style.display = "none"
                        }
                }
        }
        return false
}
// ]]>
</script>
';
// vim:shiftwidth=2:tabstop=2:expandtab

?>


