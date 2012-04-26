<?php

/*
 * FileSender www.filesender.org
 * 
 * Copyright (c) 2009-2012, AARNet, Belnet, HEAnet, SURFnet, UNINETT
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 * *	Redistributions of source code must retain the above copyright
 * 	notice, this list of conditions and the following disclaimer.
 * *	Redistributions in binary form must reproduce the above copyright
 * 	notice, this list of conditions and the following disclaimer in the
 * 	documentation and/or other materials provided with the distribution.
 * *	Neither the name of AARNet, Belnet, HEAnet, SURFnet and UNINETT nor the
 * 	names of its contributors may be used to endorse or promote products
 * 	derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/*
 * loads javascript
 * js/upload.js   manages all html5 related functions and uploading
 */

if(session_id() == ""){
	// start new session and mark it as valid because the system is a trusted source
	session_start();
	$_SESSION['validSession'] = true;
} 

require_once('../classes/_includes.php');

$flexerrors = "true";
// load config
$authsaml = AuthSaml::getInstance();
$authvoucher = AuthVoucher::getInstance();
$functions = Functions::getInstance();
$CFG = config::getInstance();
$config = $CFG->loadConfig();
$sendmail = Mail::getInstance();
$log = Log::getInstance();

$messageArray = array(); // messages to display to client

date_default_timezone_set($config['Default_TimeZone']);

$useremail = "";
$s = "";

$isAuth = $authsaml->isAuth();
$isVoucher = $authvoucher->aVoucher();
$isAdmin = $authsaml->authIsAdmin();

if(isset($_REQUEST["s"]))
{
	$s = $_REQUEST["s"];
}
if(!$isVoucher && !$isAuth && $s != "complete" && $s != "completev")
{
	$s = "logon";
}
// check if authentication data and attributes exist
if($isAuth ) 
{ 
	$userdata = $authsaml->sAuth();
	if($userdata == "err_attributes")
	{
		$s = "error";
		$isAuth = false;
		$isAdmin = false;
		array_push($messageArray,  lang("_ERROR_ATTRIBUTES"));
	} else {
		$useremail = $userdata["email"];
	}
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $config['site_name']; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link type="text/css" href="css/smoothness/jquery-ui-1.8.2.custom.css" rel="Stylesheet" />
<link rel="stylesheet" type="text/css" href="css/default.css" />
<script type="text/javascript" src="js/json2.js" ></script>
<script type="text/javascript" src="js/common.js" ></script>
<script type="text/javascript" src="js/jquery-1.7.min.js" ></script>
<script type="text/javascript" src="js/jquery-ui-1.8.1.custom.min.js"></script>
<script type="text/javascript">

var debug = <?php echo $config["debug"] ? 'true' : 'false'; ?> ;

$(function() {
	
	// display topmenu, content and userinformation
	$("#topmenu").show();
	$("#content").show();
	$("#userinformation").show();
	
	$( "a", ".menu" ).button();
	
	$("#dialog-help").dialog({ autoOpen: false, height: 400,width: 660, modal: true,
		buttons: {
			'helpBTN': function() {
				$( this ).dialog( "close" );
				}
			}
		});
		$('.ui-dialog-buttonpane button:contains(helpBTN)').attr("id","btn_closehelp");            
		$('#btn_closehelp').html('<?php echo lang("_CLOSE") ?>')  
		
		$("#dialog-about").dialog({ autoOpen: false,  height: 400,width: 400, modal: true,
			buttons: {
				'aboutBTN': function() {
					$( this ).dialog( "close" );
				}
			}
		});
		$('.ui-dialog-buttonpane button:contains(aboutBTN)').attr("id","btn_closeabout");            
		$('#btn_closeabout').html('<?php echo lang("_CLOSE") ?>')  
});
	
function openhelp()
	{
		$( "#dialog-help" ).dialog( "open" );
		$('.ui-dialog-buttonpane > button:last').focus();
	}
	
function openabout()
	{
		$( "#dialog-about" ).dialog( "open" );
		$('.ui-dialog-buttonpane > button:last').focus();
	}
</script>
   
<meta name="robots" content="noindex, nofollow" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>
<body>
<div id="wrap">
  <div id="header">
    <div align="center">
      <p><img src="displayimage.php" width="800" height="60" border="0" alt="banner" /></p>
      <noscript>
      <p class="style5 style1">JavaScript is turned off in your web browser. <br />
        This application will not run without Javascript enabled in your web browser. <br /><br />
		See <A href="http://www.filesender.org">www.filesender.org</A> for more information.<br />
        <br />
      </p>
      </noscript>
    </div>
  </div>
  <div id="topmenu" style="display:none">
  <div class="menu" id="menuleft">
      <?php 
  	// create menu
  	// disable all buttons if this is a voucher, even if the user is logged on
 	if (!$isVoucher &&  $s != "completev"){
	if($isAuth) { echo '<a id="topmenu_newupload" href="index.php?s=upload">'.lang("_NEW_UPLOAD").'</a>'; }
	if($isAuth) { echo '<a id="topmenu_vouchers" href="index.php?s=vouchers">'.lang("_VOUCHERS").'</a>'; }
	if($isAuth) {echo '<a id="topmenu_myfiles" href="index.php?s=files">'.lang("_MY_FILES").'</a>'; }
	if($isAdmin) { echo '<a id="topmenu_admin" href="index.php?s=admin">'.lang("_ADMIN").'</a>'; }
  }
  ?>
  	</div>
   <div class="menu" id="menuright">
  <?php
	if($config['helpURL'] == "") {
		echo '<a href="#" id="topmenu_help" onclick="openhelp()">'.lang("_HELP").'</a>';
	} else {
		echo '<a href="'.$config['helpURL'].'" target="_blank" id="topmenu_help">'.lang("_HELP").'</a>';
	}
	if($config['aboutURL'] == "") {
		echo '<a href="#" id="topmenu_about" onclick="openabout()">'.lang("_ABOUT").'</a>';
	} else {
		echo '<a href="'.$config['aboutURL'].'" target="_blank" id="topmenu_about">'.lang("_ABOUT").'</a>';	
	}
	if(!$isAuth && $s != "logon" ) { echo '<a href="'.$authsaml->logonURL().'" id="topmenu_logon">'.lang("_LOGON").'</a>';}
	if($isAuth && !$isVoucher &&  $s != "completev" ) { echo '<a href="'.$authsaml->logoffURL().'" id="topmenu_logoff">'.lang("_LOG_OFF").'</a>'; }
	// end menu
	?>
	</div>
	</div>

	<div id="userinformation" style="display:none">
	<?php 

	// set user attributes from identity provider
	if ($isAuth )
	{
		$attributes = $authsaml->sAuth();
	}


	// display user details if desired
	if($config["displayUserName"])
	{
		echo "<div class='welcomeuser'>";
		if(	$isVoucher || $s == "completev") 
		{ 
			echo lang("_WELCOMEGUEST");
		} 
		else if ($isAuth )
		{
			echo lang("_WELCOME")." ";
			echo utf8tohtml($attributes["cn"],true);
		}
		echo "</div>";
	}
	$versiondisplay = "";
	if($config["site_showStats"])
	{
		$versiondisplay .= $functions->getStats();
	}
	if($config["versionNumber"])
	{
		$versiondisplay .= FileSender_Version::VERSION;
	}
	echo "<div class='versionnumber'>" .$versiondisplay."</div>";
?>
	</div>
		<div id="content" style="display:none">
		<?php
		foreach ($messageArray as $message) 
		{
			echo '<div id="message">'.$message.'</div>';
		}
	?>
<?php
	// checks if url has vid=xxxxxxx and that voucher is valid 
	if(	$isVoucher)
	{
		// check if it is Available or a Voucher for Uploading a New File
		$voucherData = $authvoucher->getVoucher();

		if($voucherData[0]["filestatus"] == "Voucher")
		{ // load voucher upload
			require_once('../pages/upload.php');
		} else if($voucherData[0]["filestatus"] == "Available")
		{ 
			// allow download of voucher
			require_once('../pages/download.php');
		} else if($voucherData[0]["filestatus"] == "Closed")
		{
?>
	<div id="box"><p><?php echo lang("_VOUCHER_USED"); ?></p></div>
<?php
	}
 	else if($voucherData[0]["filestatus"] == "Voucher Cancelled")
	{
?>
		<div id="box"><p><?php echo lang("_VOUCHER_CANCELLED"); ?></p></div>
<?php
		}
		else if($voucherData[0]["filestatus"] == "Deleted")
	{
?>
		<div id="box"><p><?php echo lang("_FILE_DELETED"); ?></p></div>
<?php
		}
	} else if($s == "upload") 
	{
		require_once('../pages/upload.php');
	} else if($s == "vouchers" && !$authvoucher->aVoucher()) 
	{
		require_once('../pages/vouchers.php');
		// must be authenticated and not using a voucher to view files
	} else if($s == "files" && !$authvoucher->aVoucher() && $authsaml->isAuth() ) 
	{
		require_once('../pages/files.php');
	} else if($s == "logon") 
	{
		require_once('../pages/logon.php');
	}	
	else if($s == "admin" && !$authvoucher->aVoucher()) 
	{
		require_once('../pages/admin.php');
	}
		else if($s == "uploaderror") 
	{
?>
	<div id="message"><?php echo lang("_ERROR_UPLOADING_FILE"); ?></div></div>
<?php	
	}	
	else if($s == "filesizeincorrect") 
	{
?>
	<div id="message"><?php echo lang("_ERROR_INCORRECT_FILE_SIZE"); ?></div></div>
<?php	
	}	
	else if($s == "complete" || $s == "completev") 
	{
?>
		<div id="message"><?php echo lang("_UPLOAD_COMPLETE"); ?></div></div>
<?php
	} else if ($s == "" && $isAuth){
		require_once('../pages/upload.php');	
	}else if ($s == "" ){
		require_once('../pages/home.php');	
	}
?>
	</div>
	</div>
	<div id="dialog-help" style="display:none" title="<?php echo lang("_HELP"); ?>">
		<?php echo lang("_HELP_TEXT"); ?>
	</div>
	<div id="dialog-about" style="display:none" title="<?php echo lang("_ABOUT"); ?>">
		<?php echo lang("_ABOUT_TEXT"); ?>
	</div>
		<div id="footer"></div>
		<div id="DoneLoading"></div>
	</body>
</html>
