<?php
/**
* Class and Function List:
* Function list:
* - QueryResultsOutput()
* - AddTitle()
* - GetSortSQL()
* - PrintHeader()
* - PrintFooter()
* - DumpQROHeader()
* - qroReturnSelectALLCheck()
* - qroPrintEntryHeader()
* - qroPrintEntry()
* - qroPrintEntryFooter()
* Classes list:
* - QueryResultsOutput
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
**/
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');
include_once ("$BASE_path/includes/base_constants.inc.php");
class QueryResultsOutput {
    var $qroHeader;
    var $qroHeader2;
    var $url;
    function QueryResultsOutput($uri) {
        $this->url = $uri;
    }
    function AddTitle($title, $asc_sort = " ", $asc_sort_sql1 = "", $asc_sort_sql2 = "", $desc_sort = " ", $desc_sort_sql1 = "", $desc_sort_sql2 = "", $asc_sort2 = "", $asc_sort_sql12 = "", $asc_sort_sql22 = "", $desc_sort2 = "", $desc_sort_sql12 = "", $desc_sort_sql22 = "") {
        $this->qroHeader[$title] = array(
            $asc_sort => array(
                $asc_sort_sql1,
                $asc_sort_sql2
            ) ,
            $desc_sort => array(
                $desc_sort_sql1,
                $desc_sort_sql2
            )
        );
        if ($asc_sort2!="" && $desc_sort2!="") {
          $this->qroHeader2[$title] = array(
              $asc_sort2 => array(
                  $asc_sort_sql12,
                  $asc_sort_sql22
              ) ,
              $desc_sort2 => array(
                  $desc_sort_sql12,
                  $desc_sort_sql22
              )
          );
        }
    }
    function GetSortSQL($sort, $sort_order) {
        reset($this->qroHeader);
        while ($title = each($this->qroHeader)) {
            if (in_array($sort, array_keys($title["value"]))) {
                $tmp_sort = $title["value"][$sort];
                return $tmp_sort;
            }
        }
        /* $sort is not a valid sort type of any header */
        return NULL;
    }
    function PrintHeader($text = '',$custom=0) {
        GLOBAL $htmlPdfReport;
        GLOBAL $current_cols_titles, $current_cols_widths;
        /* Client-side Javascript to select all the check-boxes on the screen
        *   - Bill Marque (wlmarque@hewitt.com) */
        echo '
          <SCRIPT type="text/javascript">
            function SelectAll()
            {
               for(var i=0;i<document.PacketForm.elements.length;i++)
               {
                  if(document.PacketForm.elements[i].type == "checkbox")
                  {
                    document.PacketForm.elements[i].checked = true;
                  }
               }
            }
      
            function UnselectAll()
            {
                for(var i=0;i<document.PacketForm.elements.length;i++)
                {
                    if(document.PacketForm.elements[i].type == "checkbox")
                    {
                      document.PacketForm.elements[i].checked = false;
                    }
                }
            }
           </SCRIPT>';
        if ('' != $text) {
            echo $text;
        }
        echo '<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 style="border:1px solid #AAAAAA" WIDTH="100%" BGCOLOR="#000000">' . "\n" . "<TR><TD>\n" . '<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH="100%" BGCOLOR="#FFFFFF">' . "\n" . "\n\n<!-- Query Results Title Bar -->\n   <TR>\n";
        reset($this->qroHeader);
        $flag = 0; 
        $checkbox = 0;
		if ($custom) $allowed_cols = $_SESSION['views'][$_SESSION['current_cview']]['cols'];
		else $allowed_cols = array("");
        //print_r($allowed_cols); print_r($current_cols_titles);
        // print custom headers and pdf headers
        if (isset($htmlPdfReport)) $htmlPdfReport->set("<tr>\n");
        //$wp = floor(100/count($allowed_cols));
        foreach ($allowed_cols as $colname) {
            $coltitle = ($current_cols_titles[$colname]!="") ? $current_cols_titles[$colname] : $colname;
            $w = ($current_cols_widths[$colname]!="") ? "style='width:".$current_cols_widths[$colname]."'" : ""; // style='width:$wp%'
            if (isset($htmlPdfReport)) $htmlPdfReport->set("<th align='center' $w>$coltitle</th>\n");
        }   
        if (isset($htmlPdfReport)) $htmlPdfReport->set("</tr>\n");
        //
        $field=0;
		foreach ($allowed_cols as $colname) {
			$coltitle = $current_cols_titles[$colname];
			while ($title = each($this->qroHeader)) {
			//print_r($title);
				if ($custom) { // Custom view only
					if (preg_match("/INPUT/",$title['key']) && $checkbox){ continue; }
					elseif ($colname != preg_replace("/\&nbsp.*/","",$title['key']) && $checkbox) continue;
					$checkbox=1;
				}
				$print_title = "";
				if ($title['key'] == "L4-proto") $width = " width=60";
				elseif (preg_match("/INPUT/",$title['key'])) $width = " width=30";
				elseif ($title['key'] == "Risk" || $title['key'] == "Rel" || $title['key'] == "Prio" || $title['key'] == "Asst") $width = "";
				//elseif ($title['key'] == gettext("Date")) $width = " width=130";
				//elseif ($title['key'] == gettext("Source Address") || $title['key'] == gettext("Dest. Address")) $width = " width=140";
				else $width = "";
				//$border = ($title['key'] == "L4-proto" || $title['key'] == "Last" || $title['key'] == "Total Events" || (preg_match("/(Dest\.|Src\.).+Addr\./",$title['key']) && $_GET['addr_type']>0) || !$flag) ? "border-bottom:1px solid #AAAAAA;" : "border-right:1px solid #AAAAAA;border-bottom:1px solid #AAAAAA;";
                $border = ($field>0 ? "border-left:1px solid #AAAAAA;" : "")."border-bottom:1px solid #AAAAAA;";
				$flag = 1;
				$title['key'] = ($current_cols_titles[$title['key']]!="") ? $current_cols_titles[$title['key']] : $title['key'] ;
				if (!preg_match("/INPUT/",$title['key']) && isset($this->qroHeader2[$title['key']])) {
					// Add asc and desc link icons
					$sort_keys = array_keys($title["value"]);
					$asc_icon = $desc_icon = $bold_s = $bold_e = "";
					$href = $this->url . "&amp;sort_order=" . $sort_keys[1];
					if ($_GET['sort_order'] == $sort_keys[0] || $_POST['sort_order'] == $sort_keys[0]) {
						$href = $this->url . "&amp;sort_order=" . $sort_keys[0];
						$asc_icon = "<a href=\"$href\"><img src='images/order_sign_d.gif' border=0 align=absmiddle></a>&nbsp;";
						$href = $this->url . "&amp;sort_order=" . $sort_keys[1];
						$desc_icon = "&nbsp;<a href=\"$href\"><img src='images/order_sign_a.gif' border=0 align=absmiddle></a>";
						$bold_s = "<font style='text-decoration:underline;font-size:12px'>";
						$bold_e = "</font>";
						if ($width == " width=40") $width = " width=75";
						elseif ($width == " width=60") $width = " width=95";
					}
					if ($_GET['sort_order'] == $sort_keys[1] || $_POST['sort_order'] == $sort_keys[1]) {
						$href = $this->url . "&amp;sort_order=" . $sort_keys[0];
						$asc_icon = "<a href=\"$href\"><img src='images/order_sign_d.gif' border=0 align=absmiddle></a>&nbsp;";
						$href = $this->url . "&amp;sort_order=" . $sort_keys[1];
						$desc_icon = "&nbsp;<a href=\"$href\"><img src='images/order_sign_a.gif' border=0 align=absmiddle></a>";
						$href = $this->url . "&amp;sort_order=" . $sort_keys[0];
						$bold_s = "<font style='text-decoration:underline;font-size:12px'>";
						$bold_e = "</font>";
						if ($width == " width=40") $width = " width=75";
						elseif ($width == " width=60") $width = " width=95";
					}
					$print_title = $title["key"]."<br>$asc_icon<a href=\"$href\" style='color:#222222;font-size:12px;font-weight:bold'>$bold_s" . "S" . "$bold_e</a>$desc_icon";

					// Add asc and desc link icons 2
					$sort_keys2 = array_keys($this->qroHeader2[$title['key']]);
					$asc_icon = $desc_icon = $bold_s = $bold_e = "";
					$href = $this->url . "&amp;sort_order=" . $sort_keys2[1];
					if ($_GET['sort_order'] == $sort_keys2[0] || $_POST['sort_order'] == $sort_keys2[0]) {
						$href = $this->url . "&amp;sort_order=" . $sort_keys2[0];
						$asc_icon = "<a href=\"$href\"><img src='images/order_sign_d.gif' border=0 align=absmiddle></a>&nbsp;";
						$href = $this->url . "&amp;sort_order=" . $sort_keys2[1];
						$desc_icon = "&nbsp;<a href=\"$href\"><img src='images/order_sign_a.gif' border=0 align=absmiddle></a>";
						$bold_s = "<font style='text-decoration:underline;font-size:12px'>";
						$bold_e = "</font>";
						if ($width == " width=40") $width = " width=75";
						elseif ($width == " width=60") $width = " width=95";
					}
					if ($_GET['sort_order'] == $sort_keys2[1] || $_POST['sort_order'] == $sort_keys2[1]) {
						$href = $this->url . "&amp;sort_order=" . $sort_keys2[0];
						$asc_icon = "<a href=\"$href\"><img src='images/order_sign_d.gif' border=0 align=absmiddle></a>&nbsp;";
						$href = $this->url . "&amp;sort_order=" . $sort_keys2[1];
						$desc_icon = "&nbsp;<a href=\"$href\"><img src='images/order_sign_a.gif' border=0 align=absmiddle></a>";
						$href = $this->url . "&amp;sort_order=" . $sort_keys2[0];
						$bold_s = "<font style='text-decoration:underline;font-size:12px'>";
						$bold_e = "</font>";
						if ($width == " width=40") $width = " width=75";
						elseif ($width == " width=60") $width = " width=95";
					}
					$print_title .= "<img src='images/arrow-000-small.gif' border=0 align=absmiddle>$asc_icon<a href=\"$href\" style='color:#222222;font-size:12px;font-weight:bold'>$bold_s" . "D" . "$bold_e</a>$desc_icon";
					echo '<TD height="34" CLASS="plfieldhdr"' . $width . ' style="'.$border.'line-height:14px;padding-top:1px;background:url(\'../pixmaps/fondo_col.gif\') 50% 50% repeat-x;color:#222222;font-size:12px;font-weight:bold" NOWRAP>&nbsp;' . $print_title . '&nbsp;</TD>' . "\n";
				} else {
					$sort_keys = array_keys($title["value"]);
					if (count($sort_keys) == 2) {
						// Add asc and desc link icons
						$asc_icon = $desc_icon = $bold_s = $bold_e = "";
						$href = $this->url . "&amp;sort_order=" . $sort_keys[1];
						if ($_GET['sort_order'] == $sort_keys[0] || $_POST['sort_order'] == $sort_keys[0]) {
							$href = $this->url . "&amp;sort_order=" . $sort_keys[0];
							$asc_icon = "<a href=\"$href\"><img src='images/order_sign_d.gif' border=0 align=absmiddle></a>";
							$href = $this->url . "&amp;sort_order=" . $sort_keys[1];
							$desc_icon = "<a href=\"$href\"><img src='images/order_sign_a.gif' border=0 align=absmiddle></a>";
							$bold_s = "<font style='text-decoration:underline;font-size:12px'>";
							$bold_e = "</font>";
							if ($width == " width=40") $width = " width=75";
							elseif ($width == " width=60") $width = " width=95";
						}
						if ($_GET['sort_order'] == $sort_keys[1] || $_POST['sort_order'] == $sort_keys[1]) {
							$href = $this->url . "&amp;sort_order=" . $sort_keys[0];
							$asc_icon = "<a href=\"$href\"><img src='images/order_sign_d.gif' border=0 align=absmiddle></a>";
							$href = $this->url . "&amp;sort_order=" . $sort_keys[1];
							$desc_icon = "<a href=\"$href\"><img src='images/order_sign_a.gif' border=0 align=absmiddle></a>";
							$href = $this->url . "&amp;sort_order=" . $sort_keys[0];
							$bold_s = "<font style='text-decoration:underline;font-size:12px'>";
							$bold_e = "</font>";
							if ($width == " width=40") $width = " width=75";
							elseif ($width == " width=60") $width = " width=95";
						}
						$print_title = "$asc_icon<a href=\"$href\" style='color:#222222;font-size:12px;font-weight:bold'>$bold_s" . $title["key"] . "$bold_e</a>$desc_icon";
					} else {
						$print_title = $title["key"];
					}
					echo '<TD height="34" CLASS="plfieldhdr"' . $width . ' style="'.$border.'line-height:14px;background:url(\'../pixmaps/fondo_col.gif\') 50% 50% repeat-x;color:#222222;font-size:12px;font-weight:bold" NOWRAP>&nbsp;' . $print_title . '&nbsp;</TD>' . "\n";
				}
			}
			reset($this->qroHeader);
            $field++;
		}
        echo "</TR>\n";
    }
    function PrintFooter() {
        echo "  </TABLE>\n
           </TD></TR>\n
          </TABLE>\n";
    }
    function DumpQROHeader() {
        echo "<B>" . gettext("Query Results Output Header") . "</B>
          <PRE>";
        print_r($this->qroHeader);
        echo "</PRE>";
    }
}
function qroReturnSelectALLCheck() {
    return '<INPUT type=checkbox value="Select All" onClick="if (this.checked) SelectAll(); if (!this.checked) UnselectAll();">';
}
function qroPrintEntryHeader($prio = 1, $color = 0, $more = "", $forced_color="", $class="trcell") {
    global $priority_colors;
    if ($color == 1) {
        echo '<TR class="'.$class.'" BGCOLOR="#' . $priority_colors[$prio] . '" ' . $more . '>';
    } else {
        $bgcolor = ($forced_color!="") ? $forced_color : (($prio % 2 == 0) ? "EEEEEE" : "FFFFFF");
        echo '<TR class="'.$class.'" BGCOLOR="#' . $bgcolor . '" ' . $more . '>';
    }
}
function qroPrintEntry($value, $halign = "center", $valign = "top", $passthru = "", $bgcolor = "") {
	echo "<TD $bgcolor style='border-right:1px solid white;border-top:1px solid white;padding-left:2px' align=\"" . $halign . "\" valign=\"" . $valign . "\" " . $passthru . ">\n" . "  $value\n" . "</TD>\n\n";
}
function qroPrintEntryTooltip($value, $halign = "center", $valign = "top", $passthru = "", $tooltip = "") {
	echo "<TD txt='".str_replace("'","\'",$tooltip)."' class='tztooltip' style='border-right:1px solid white;border-top:1px solid white;padding-left:2px' align=\"" . $halign . "\" valign=\"" . $valign . "\" " . $passthru . ">\n" . "  $value\n" . "</TD>\n\n";
}
function qroPrintEntryFooter() {
    echo '</TR>';
}
function colHidden($title,$current_cols_titles) {
	return ($current_cols_titles[$title]) ? 1 : 0;
}
?>
