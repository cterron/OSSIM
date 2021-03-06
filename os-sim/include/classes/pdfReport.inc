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
* Class:
* Classes list:
* - PdfReport
* - Html
*/
class PdfReport{
    private $path='../tmp/';
    private $pathHtml;
	private $pathHtmlAux;
    private $pathDirResourcesHtml;
    private $namePdf;
    private $nameTemp;
    private $orientation;
    private $paperformat;
    private $name;
	private $errors;
	private $extra_data;

    public function __construct($name,$orientation="P",$paperformat="A4", $user = null, $data=array()) {
        
		require_once ('classes/Session.inc');
		$db          = new ossim_db();
		$dbconn      = $db->connect();
		
		$this->errors = array();
		
		if ( !empty($name) )
		{
			$this->setName($name);
			$this->setNameTemp($name);  
			$this->setNamePdf($this->getNameTemp());
			
			$user   = ( empty($user) ) ? Session::get_session_user() : $user;
        
			$this->path = "../tmp/".$this->get_report_uuid($user)."/".$this->getName()."/";
			
			if ( !is_dir($this->path) ) 
				mkdir($this->path, 0755, true);
			
			if ( !empty($data) && is_array($data) )
			{
				
				if ( preg_match("/ENTITY\:(\d+)/", $data['assets'], $fnd)) 
				{
					$entity              = Acl::get_entity($dbconn, $fnd[1]);
					$data['assets_text'] = "ENTITY: ".$entity['name'];
				}
				else
					$data['assets_text'] = $data['assets'];
				
				$data_s           = serialize($data);
				$this->extra_data = $data;
				$res              = file_put_contents($this->path."/data_".$name.".ini", $data_s);
				
				if ( $res === false )
					$this->errors["file_error"] = _("File Error: Could not create file ").$this->path;
				else
				{
					$str_to_replace = array(" ", ":", ".", "#", "&");
					$this->setNamePdf(str_replace($str_to_replace, "_", $this->getNameTemp())."_".str_replace($str_to_replace, "_", $this->extra_data['assets_text']));
				}
			}
			else
			{
				if ($data == true)
				{
					$path = $this->path."/data_".$name.".ini";
					if ( @file_exists($path) )
					{
						$data_s = @file_get_contents($path);
						$data   = @unserialize($data_s);
						$this->extra_data = ( is_array($data) ) ? $data : null;
					}
				}
				
				
				if ( $data == false || empty($data) )
					$this->extra_data = null;
				else
				{
					$str_to_replace = array(" ", ":", ".", "#", "&");
					$this->setNamePdf(str_replace($str_to_replace, "_", $this->getNameTemp())."_".str_replace($str_to_replace, "_", $this->extra_data['assets_text']));
				}
			}
					
			
			$this->setpathHtml($this->path."/".$this->getName().".html");
			$this->setpathDirResourcesHtml($this->path);
			
			$this->orientation = $orientation;
			$this->paperformat = $paperformat;
		}
		else
			$this->errors["empty_name"] = _("File Error: Name is empty ");
	}
	
	
	private function get_report_uuid($user)
	{
		require_once ('classes/Session.inc');
		
		$uuid     = Session::get_secure_id();
		$url      = null;
		
		if ( empty($uuid) )
		{
			$db     = new ossim_db();
			$dbconn = $db->connect();
			
			$user   = ( empty($user) ) ? Session::get_session_user() : $user;
			
			$query  = 'SELECT * FROM `users` WHERE login="'.$user.'"';
			$result = $dbconn->Execute($query);
			
			if ( is_array($result->fields) && !empty($result->fields) )
			{
				$pass = $result->fields["pass"];
				$uuid = sha1($user."#".$pass);
			}
			else
				$uuid = false;
			
		}
		
		return $uuid;
	}

    public function setName($name){
        $this->name=$name;
    }

    public function setNameTemp($name){
        $this->nameTemp=$name;
    }

    public function getName(){
        return $this->name;
    }

    public function getNameTemp(){
        return $this->nameTemp;
    }
	
	public function setNamePdf($name){
        $this->namePdf=$this->getActualDate().'-'.$name.'.pdf';
    }
	
	public function getNamePdf(){
        return $this->namePdf;
    }

    private function setpathHtml($url){
        $this->pathHtml=$url;
		$this->pathHtmlAux=$url."_";
    }

    private function setpathDirResourcesHtml($url){
        $this->pathDirResourcesHtml=$url;
    }

    public function getpathHtml(){
        return $this->pathHtml;
    }

    private function existHtml(){
		if (file_exists($this->pathHtml))
			return true;
		else
			return false;
	}

    private function getActualDate(){
        $date=getdate();

        return $date['year'].$date['yday'].$date['hours'].$date['minutes'].$date['seconds'];
    }
	
	public function getExtraData($key)
	{
		return ( $key == '' ) ? $this->extra_data : $this->extra_data[$key];
	}
	
	public function setExtraData($data)
	{
		return $this->extra_data = $data;
	}

    private function convertEntities(){
    	$file = @file($this->pathHtml);
    	for ($i=0; $i<count($file); $i++) {
    		if (preg_match('/&#(\d{4,5});/',$file[$i]) && !preg_match('/\/image/',$file[$i]))
    			$file[$i] = mb_convert_encoding($file[$i],'UTF-8','HTML-ENTITIES');
    		else
    			$file[$i] = mb_convert_encoding($file[$i],'UTF-8','ISO-8859-1');
    	}
		file_put_contents($this->pathHtmlAux,$file);
		unset($file);
    }
    
    public function getPdf($output='navigator'){
       
		if( $this->existHtml() )
		{
        	session_write_close();
        	$this->convertEntities();
            require_once("classes/html2pdf/html2pdf.class.php");
            $html2pdf = new HTML2PDF($this->orientation,$this->paperformat,'en', true, 'UTF-8',array(0,0,0,0));
            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->setTestIsImage(false);
            //$html2pdf->setModeDebug();
            $html2pdf->WriteHTML(file_get_contents($this->pathHtmlAux));
            
			if( $output=='navigator' ) {
			    $html2pdf->Output($this->namePdf,'D');
            }
            elseif($output=='server')
			{
                $html2pdf->Output($this->pathDirResourcesHtml.$this->namePdf, 'F');
                return $this->pathDirResourcesHtml.$this->namePdf;
            }
        }
		else
            echo "<div class='ossim_error' style='width:80%; text-align:center;'>"._('No data available')."</div>";
        
    }

    public function sendPdfEmail($title,$email,$pdfName=null,$dirUser=null){
        /**/
        $conf     = $GLOBALS["CONF"];
        $from     = $conf->get_conf("from");
        $fromName = $conf->get_conf("from");
        $to       = $email;
        $toName   = $email;
        $subject  = _('Report (')._($title)._(') - OSSIM');
        $body     = $body='<html>
                    <head>
                    <title>'.$subject.'</title>
                    </head>
                    <body>
                    </body>
                    </html>
                    ';
        $fileName = $this->getNamePdf();
        
		/**/
        if($pdfName==null)
		{
            try
			{
                // obtenemos el pdf
                $attachment=$this->getPdf('server');
            } 
			catch (Exception $e) 
			{
				echo $e->getMessage(); //Boring error messages from anything else!
            }
        }
		else
		{
            // el pdf ya estaba generado
            $attachment='/usr/share/ossim/www/tmp/scheduler/'.$dirUser.'/'.$pdfName.'.pdf';
		}
        
        require_once('classes/PHPMailer.inc');
        include("classes/PHPMailerSMTP.inc");  // optional, gets called from within class.phpmailer.php if not already loaded

        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

        $mail->IsSMTP(); // telling the class to use SMTP
        
		try {
			if($conf->get_conf("use_ssl")=='yes'){ $mailHost='ssl://'; }else{ $mailHost=''; }
			
			$mailHost.=$conf->get_conf("smtp_server_address");
			$mail->Host       = $mailHost;// SMTP server
			$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
			$mailSMTPUser=$conf->get_conf("smtp_user");
			if(!empty($mailSMTPUser))
			{
				$mailSMTPAuth=true;
				$mailUsername=$conf->get_conf("smtp_user");
				$mailPassword=$conf->get_conf("smtp_pass");
			}
			else
			{
				$mailSMTPAuth=false;
				$mailUsername='';
				$mailPassword='';
			}
			
			$mail->SMTPAuth   = $mailSMTPAuth;                  // enable SMTP authentication
			$mail->Port       = $conf->get_conf("smtp_port");                    // set the SMTP port for the GMAIL server (26)
			$mail->Username   = $mailUsername; // SMTP account username
			$mail->Password   = $mailPassword;        // SMTP account password
			$mail->AddAddress($to,$toName);
			$mail->SetFrom($from,$fromName);
			$mail->Subject = $subject;
			
			$mail->AddAttachment($attachment, $fileName);
			$mail->AltBody = _("To view the message, please use an HTML compatible email viewer!"); // optional - MsgHTML will create an alternate automatically
			$mail->MsgHTML($body);
			$mail->Send();
			return true;
        }
		catch (phpmailerException $e) {
			//Pretty error messages from PHPMailer
			echo $e->errorMessage(); 
		} 
		catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    
    public function getLinkPdf($extra_data=false){
        
		$current_url = $_SERVER[SCRIPT_NAME].'?pdf=true&extra_data=true&'.$_SERVER[QUERY_STRING];
		
		$html='
            <script language="javascript">
                function exportReport(){
                    var idForm="#email_form";
                    $(idForm).attr("action","wizard_email.php?format=email&extra_data='.$extra_data.'&name='.base64_encode($this->getName()).'");
                 	$(idForm).attr("target","GB_frame");
                    $(idForm).submit();
                }
                function emailValidate(id)
                    {
                        if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($("#email").val()))
                        {
                            openGreyBox("Send email");
                            setTimeout("exportReport(\'\')",500);

                            return true;
                        }else{
                            alert("'._("Please, add a valid email").'");
                            $(id).focus();
                            return false;
                        }
                    }
               function openGreyBox(tittle) {
                    GB_show(tittle,"wizard_email.php","350","600");
                    return false;
                }
                function toggleDivEmail(){
                    $("#iemail").toggle();
                }

             </script>
               <table width="100%" class="transparent" align="center" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" class="nobborder" style="text-align:right;" nowrap>
                        <a class="sbutton" href="'.$current_url.'" target="_blank" title="'._("Generate PDF").'">
                            <img src="../pixmaps/pdf_button.png" align="absmiddle"> '._("Download PDF").'
                        </a>
                        </td>
                        <td  width="50%" class="nobborder" style="text-align:left;padding-left:10px;" nowrap>
                        <a class="sbutton" href="javascript:;" onclick="toggleDivEmail();" title="'._("Send PDF by e-mail").'"><img src="../pixmaps/emailButton.png" align="absmiddle"> '._("Send by e-mail").'</a>
                        </td>
                   </tr>
               </table>
               <div id="iemail" style="display:none;">
               <form method="POST" action="#" id="email_form">
                    <div style="margin-top:5px">
                        <input id="email" name="email" type="text" style="width:120px">
                        <input type="button" class="vsbutton" onclick="emailValidate(\'#email\');" value="'._("Send").'"\>
					</div>
                </form>
                </div>
            ';

        return $html;
    }

    public function setHtml($html){
       	
		file_put_contents($this->pathHtml, $html);
    }
}

class Html{
    private $html;
    private $path='../tmp/';
    private $backTop='30mm';
    private $backBottom='15mm';
    private $backLeft='7mm';
    private $backRight='7mm';
    private $backImg;
    private $backImgX='center';
    private $backImgY='top';
    private $backImgW='100%';
    private $page;
    private $linkStylesheet;
    private $name;
    private $user;
    private $resourceId=1;
    private $imagesArr=array();
    private $dirTemp;
    private $mainUrl;
    private $title;
    private $imgHeader='default.png';
    private $styleCss=array(
        'Title'=>array(
            'Background'=>'#8CC221',
            'Foreground'=>'#000',
        ),
        'Subtitle'=>array(
            'Background'=>'#7B7B7B',
            'Foreground'=>'#fff',
        )
    );
    private $footerContent;
    private $protocol;

    private function setMainUrl(){
        //$port = explode ("\n",`grep 'Listen' /etc/apache2/ports.conf | awk '{print $2}'`);
        //$_SERVER["APACHE_PORT"] = (is_array($port) && intval($port[0])>0) ? intval($port[0]) : 80;
        //echo $this->getProtocol().'//'.$_SERVER['SERVER_ADDR'].':'.$_SERVER["APACHE_PORT"].'/ossim/';
        $this->mainUrl = $this->getProtocol().'//'.$_SERVER['SERVER_ADDR'].':'.$_SERVER["SERVER_PORT"].'/ossim/';
    }

    private function getRootUrl(){
        //$port = explode ("\n",`grep 'Listen' /etc/apache2/ports.conf | awk '{print $2}'`);
        //$_SERVER["APACHE_PORT"] = (is_array($port) && intval($port[0])>0) ? intval($port[0]) : 80;
        //$this->getProtocol().'//'.$_SERVER['SERVER_ADDR'].':'.$_SERVER["APACHE_PORT"].'/';
        return $this->getProtocol().'//'.$_SERVER['SERVER_ADDR'].':'.$_SERVER["SERVER_PORT"].'/';
    }

    private function setLinkStylesheet(){
        $this->linkStylesheet='<link rel="stylesheet" type="text/css" href="'.$this->mainUrl.'/style/stylePdf.css"/>';
    }

    private function getMainUrl(){
        return $this->mainUrl;
    }

    private function setProtocol(){
        if(empty($_SERVER["HTTPS"])){
            $this->protocol='http:';
        }else{
            $this->protocol='https:';
        }
    }

    private function getProtocol(){
        return $this->protocol;
    }

    public function __construct($name,$title='',$imagenHeader='',$style='',$styleCss=array(),$footerContent=array()) {
        $this->setProtocol();
        $this->setMainUrl();
        $this->setFooterContent($footerContent);
        $this->setName($name);
        $this->setUser();
        $uuid = $this->get_report_uuid(Session::get_session_user());
        $this->path = "../tmp/$uuid/";
        $this->setDirTemp();
        $this->newDirTemp();
        $this->setImagenHeader($imagenHeader);
        $this->setBackImg();
        $this->setPage($style);
        $this->setLinkStylesheet();
        $this->setStyleCss($styleCss);
        $this->set($this->newStyle());
        $this->set($this->linkStylesheet);
        $this->set($this->page);
        $this->setTitle($title);
    }
	
	
	private function get_report_uuid($user)
	{
		require_once ('classes/Session.inc');
		
		$uuid     = Session::get_secure_id();
		$url      = null;
		
		if ( empty($uuid) )
		{
			$db     = new ossim_db();
			$dbconn = $db->connect();
			
			$user   = ( empty($user) ) ? Session::get_session_user() : $user;
			
			$query  = 'SELECT * FROM `users` WHERE login="'.$user.'"';
			$result = $dbconn->Execute($query);
			
			if ( is_array($result->fields) && !empty($result->fields) )
			{
				$pass = $result->fields["pass"];
				$uuid = sha1($user."#".$pass);
			}
			else
				$uuid = false;
			
		}
		
		return $uuid;
	}
	

    private function setFooterContent($footerContent){
        $this->footerContent=$footerContent;
    }

    private function setStyleCss($styleCss=array()){
        if(count($styleCss)>1){
            $this->styleCss['Title']['Background']=$styleCss['Title']['Background'];
            $this->styleCss['Title']['Foreground']=$styleCss['Title']['Foreground'];
            $this->styleCss['Subtitle']['Background']=$styleCss['Subtitle']['Background'];
            $this->styleCss['Subtitle']['Foreground']=$styleCss['Subtitle']['Foreground'];
        }
    }

    public function setDirTemp(){
        $this->dirTemp=$this->path.$this->name; // $this->name.'-'.$this->user;
    }

    public function newDirTemp(){
        if (!is_dir($this->dirTemp))
            mkdir($this->dirTemp,0755,true);
        else
            $this->cleanTemp();
    }

    public function cleanTemp(){
        if(!strstr($this->dirTemp,'/tmp/')){
            // boramos los elementos del directorio
            foreach(scandir($this->dirTemp) as $value){
                if($value!='.'&&$value!='..'){
                    if (!is_dir($this->dirTemp.'/'.$value)) unlink($this->dirTemp.'/'.$value);
                }
            }
            // borramos el temporal php
            if (file_exists($this->dirTemp.'.html')) unlink($this->dirTemp.'.html');
        }
    }

    public function set($html){
        $this->html.=$html;
    }

    private function setName($name){
        $this->name=$name;
    }

    public function setTitle($title){
        $this->title=$title;
        // Ponemos el pie de p�gina
        $this->set($this->getHeaderFooter());
    }
    
    private function setUser(){
        $this->user=Session::get_session_user();
    }

    private function getUser(){
        return $this->user;
    }

    public function setHeader($name){
        $this->header=$name;
    }

    private function setImagenHeader($name){
        if($name!=''){
            $this->imgHeader=$name;
        }
    }

    private function setBackImg(){
        $this->backImg=$this->path.'../headers/'.$this->imgHeader;
    }

    public function setPage($style){
        $html='<page backtop="'.$this->backTop.'" backbottom="'.$this->backBottom.'" backleft="'.$this->backLeft.'" backright="'.$this->backRight.'" backimg="'.$this->backImg.'" backimgx="'.$this->backImgX.'" backimgy="'.$this->backImgY.'" backimgw="'.$this->backImgW.'"'.($style!="" ? ' style="'.$style.'"' : '').'>';
        $this->page=$html;
    }
    private function getPage(){
        return $this->page;
    }

    public function setBookmark($name,$level=0){
		if($name!='&nbsp;'){
			$this->set('<bookmark title="'.$name.'" level="'.$level.'" ></bookmark>');
		}
    }

    public function pageBreak(){
        $this->set('</page>'.$this->getPage());
        // Footer
        $this->set($this->getHeaderFooter());
    }

    public function getHeaderFooter(){
    	require_once("classes/Util.inc");
        if(count($this->footerContent)==0){
            $this->footerContent=array(
                'left'=>'User: '.$this->getUser().' / [[date_y]]-[[date_m]]-[[date_d]] [[date_h]]:[[date_i]]:[[date_s]]',
                'right'=>'Page [[page_cu]] / [[page_nb]]'
            );
        }

        $html='<page_header>
        <table cellpadding=0 cellspacing=0 class="w100">
            <tr>
                <td class="right w100 title">'.$this->title.'</td>
            </tr>
        </table><br>
    </page_header>
    <page_footer>
        <table cellpadding=0 cellspacing=0 class="w100">
            <tr>
                <td class="left w50" style="padding-left:25px">'.$this->footerContent['left'].'</td>
                <td class="right w50">'.$this->footerContent['right'].'</td>
            </tr>
        </table><br>
    </page_footer>';

        return $html;
    }

    public function get(){
        $this->getImages(); // close session and get all images
        $this->set('</page>');
        return $this->html;
    }

    public function getResourceId(){
        return $this->resourceId++;
    }

    public function getImages(){
        session_write_close();
        //$file_1x1=@file_get_contents($this->getMainUrl()."pixmaps/1x1.png");
        $file_1x1=file_get_contents("/usr/share/ossim/www/pixmaps/1x1.png");
        foreach ($this->imagesArr as $path => $url) {
        	$file_temp=@file_get_contents($url);
        	if ($file_temp!="" && @imagecreatefromstring($file_temp)!==false)
        		file_put_contents($path,$file_temp);
        	else
        		file_put_contents($path,$file_1x1);
		}
    }
    
    public function newImage($url,$format,$path='',$session=true){
        if($path=='root'){
            $url=$this->getRootUrl().$url;
        }elseif($path=='external'){
            $url=$url;
        }else{
            $url=$this->getMainUrl().$url;
        }
        if($session){
            $url .= "&".session_name()."=".session_id();
        }
        $path=$this->dirTemp.'/image'.$this->getResourceId().'.'.$format;
        $this->imagesArr[$path] = $url;

        return $path;
    }

    public function newTitle($title,$date_from="",$date_to="",$notes="",$host=""){
        
		if ($host != "") {
			$html = '<table class="tableTitleF"><tr><td style="width:15%">&nbsp;</td><td style="width:70%"><b>'.$title.'</b></td>
            <td style="width:15%">'.$host.'</td>
            </tr></table>';
		} 
		elseif ($date_from!="" && $date_to!="") 
		{
            $html = '<table class="tableTitleF"><tr><td style="width:74%"><b>'.$title.'</b></td>
            <td style="width:13%;font-size:12px">'._("from").': '.date("Y-m-d",strtotime($date_from)).'</td>
            <td style="width:13%;font-size:12px">'._("to").': '.date("Y-m-d",strtotime($date_to)).'</td>
            </tr></table>';
        } 
		else 
		{
            $html='<table class="tableTitle"><tr><td><b>'.$title.'</b></td></tr></table>';
        }
        
		if ($notes!="") {
        	$html .= '<table class="w100" cellpadding=0 cellspacing=0><tr><td style="padding:3px 0px 1px 0px;font-size:12px" valign="top">'.$notes.'</td></tr></table>';
        }
        
		return $html;
    }
    
    public function newStyle(){
        return '<style type="text/css">
            <!--
            .tableTitleF, .tableTitle{
                background: '.$this->styleCss['Title']['Background'].';
            }
            .tableTitleF td, .tableTitle td{
                color: '.$this->styleCss['Title']['Foreground'].';
            }
             th {
                background: '.$this->styleCss['Subtitle']['Background'].';
                color: '.$this->styleCss['Subtitle']['Foreground'].';
            }
            -->
            </style>';
    }
}
?>
