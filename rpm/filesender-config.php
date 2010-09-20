<?php

/*
 *  Filsender www.filesender.org
 *      
 *  Copyright (c) 2009-2010, Aarnet, HEAnet, UNINETT
 * 	All rights reserved.
 *
 * 	Redistribution and use in source and binary forms, with or without
 *	modification, are permitted provided that the following conditions are met:
 *	    * 	Redistributions of source code must retain the above copyright
 *   		notice, this list of conditions and the following disclaimer.
 *   	* 	Redistributions in binary form must reproduce the above copyright
 *   		notice, this list of conditions and the following disclaimer in the
 *   		documentation and/or other materials provided with the distribution.
 *   	* 	Neither the name of Aarnet, HEAnet and UNINETT nor the
 *   		names of its contributors may be used to endorse or promote products
 *   		derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY Aarnet, HEAnet and UNINETT ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL Aarnet, HEAnet or UNINETT BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
 
class config {

private static $instance = NULL;

	public static function getInstance() {
		// Check for both equality and type		
		if(self::$instance === NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	} 
	
public function loadConfig() {
	
	$config = array();

	// set  configs
	// v Beta 0.1.10 additions
	$config['postgresdateformat'] = "Y-m-d H:i:sP";
	$config['datedisplayformat'] = "DD-MM-YYYY";
	$config["crlf"] = "\n"; // for email CRLF can be changed to \r\n if required 
	$config["site_splashtext"] = "FileSender is a web based application that allows authenticated users to securely and easily send arbitrarily large files to other users. Authentication of users is provided through SAML2, LDAP and RADIUS. Users without an account can be sent an upload voucher by an authenticated user. FileSender is developed to the requirements of the higher education and research community.";

	$config["max_email_recipients"] = 100; // maximum email addresses allowed to send at once for voucher or file sending
	$config["server_drivespace_warning"] = 20; // as a percentage 20 = 20% space left on the storage drive

	// UI Settings
	$config["versionNumber"] = true;
	$config['about'] = true;
	$config['site_showStats'] = false;
	$config['displayUserName'] = true; 
	$config["help_link_visible"] = true;
	
	$config['aboutURL'] = "about.php";
	$config['helpURL'] = "help.php";
	
	// debug 
	$config["debug"] = true;
	$config['dnslookup'] = true; // log includes DNS lookup true/false

	// saml settings
	$config['saml_email_attribute'] = 'mail';
	$config['saml_name_attribute'] = 'sn';
	$config['saml_uid_attribute'] = 'eduPersonTargetedID';


	// Aup	
	$config["AuP_default"] = false; //AuP value is already ticked
	$config["AuP"] = true; // Aup is displayed
	$config["AuP_label"] = "I accept the terms and conditions of this service";
	$config["AuP_terms"] = "AuP Terms and conditions";
		
	$config['voucherRegEx'] = "'[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}'";
	$config['voucherUIDLength'] = 36;
	$config['ban_extension'] = 'exe,bat';
	$config['admin'] = '';
	$config['adminEmail'] = '';
	$config['Default_TimeZone'] = 'Australia/Sydney';

	$config['max_flash_upload_size'] = '2147483648'; // 2GB
	$config['max_gears_upload_size'] = '100000000000'; // 100 GB
	
	$config['available_space'] = '20000M';
	
	// site URLS
	$prot =  isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$config['site_url'] = $prot . $_SERVER['SERVER_NAME'] . '/filesender/'; // URL to Filesender
	$config['site_simplesamlurl'] =  $prot . $_SERVER['SERVER_NAME'] . '/simplesaml/';
	$config['site_downloadurl'] = $config['site_url'] . 'files/';
	$config['site_logouturl'] = $config['site_url'] . 'logout.php';

    // (absolute) file locations
	$config['site_filestore'] = '/usr/share/filesender/www/files/'; // Make sure this is accessible by webserver
	$config['site_temp_filestore'] = '/usr/share/filesender/tmp/'; 
	$config['site_simplesamllocation'] = '/usr/share/simplesamlphp/';
	$config['log_location'] = '/usr/share/filesender/log/';	

    // site settings
	$config['site_authenticationSource'] ="default-sp";
	$config['site_defaultlanguage'] = 'EN_AU';
	$config['site_name'] = 'FileSender';
	$config['site_icon'] = 'cloudstor.png';
	$config['site_css'] = '';
	$config['forceSSL'] = false;

	$config['default_daysvalid'] = 20;
	$config['gearsURL'] = 'http://tools.google.com/gears/';

	// database settings	
	$config['pg_host'] = 'localhost';
	$config['pg_database'] = 'filesender';
	$config['pg_port'] = '5432';
	$config['pg_username'] = 'postgres';
	$config['pg_password'] = 'yoursecretpassword';

	// email
	$config['default_emailsubject'] = "{siteName}: {filename}";
	$config['filedownloadedemailbody'] = '{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset=iso-8859-1{CRLF}{CRLF}
Dear Sir, Madam,

The file below has been downloaded from {siteName} by {filefrom}.

Filename = {fileoriginalname}
Filesize = {filesize}
Download link = {serverURL}?vid={filevoucheruid}
The file is available until {fileexpirydate} after which it will be automatically deleted

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset=iso-8859-1{CRLF}{CRLF}
<HTML>
<BODY>
<P>Dear Sir, Madam,</P>
<P>The file below has been downloaded from {siteName} by {filefrom}.</P>
<TABLE WIDTH=100% BORDER=1 BORDERCOLOR="#000000" CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=600>
	<COL WIDTH=80>
	<COL WIDTH=800>
	<COL WIDTH=70>
	<TR>
		<TD WIDTH=600 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Filename</B></P>
		</TD>
		<TD WIDTH=80 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Filesize</B></P>
		</TD>
		<TD WIDTH=600 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Download link</B></P>
		</TD>
		<TD WIDTH=70 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Valid until</B></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=600 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{fileoriginalname}</P>
		</TD>
		<TD WIDTH=80 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{filesize}</P>
		</TD>
		<TD WIDTH=800 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER><A HREF="{serverURL}?vid={filevoucheruid}">{serverURL}?vid={filevoucheruid}</A></P>
		</TD>
		<TD WIDTH=70 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{fileexpirydate}</P>
		</TD>
	</TR>
</TABLE>
<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--';
	$config['fileuploadedemailbody'] = '{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset=iso-8859-1{CRLF}{CRLF}
Dear Sir, Madam,

The file below has been uploaded to {siteName} by {filefrom} and you have been granted permission to download this file.

Filename = {fileoriginalname}
Filesize = {filesize}
Download link = {serverURL}?vid={filevoucheruid}
The file is available until {fileexpirydate} after which it will be automatically deleted
Personal message from {filefrom} (optional) = {filemessage}

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset=iso-8859-1{CRLF}{CRLF}
<HTML>
<BODY>
<P>Dear Sir, Madam,</P>
<P>The file below has been uploaded to {siteName} by {filefrom} and you have been granted permission to download this file.</P>
<TABLE WIDTH=100% BORDER=1 BORDERCOLOR="#000000" CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=600>
	<COL WIDTH=80>
	<COL WIDTH=800>
	<COL WIDTH=70>
	<TR>
		<TD WIDTH=600 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Filename</B></P>
		</TD>
		<TD WIDTH=80 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Filesize</B></P>
		</TD>
		<TD WIDTH=600 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Download link</B></P>
		</TD>
		<TD WIDTH=70 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Valid until</B></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=600 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{fileoriginalname}</P>
		</TD>
		<TD WIDTH=80 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{filesize}</P>
		</TD>
		<TD WIDTH=800 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER><A HREF="{serverURL}?vid={filevoucheruid}">{serverURL}?vid={filevoucheruid}</A></P>
		</TD>
		<TD WIDTH=70 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{fileexpirydate}</P>
		</TD>
	</TR>
</TABLE>
<P></P>
<TABLE WIDTH=100% BORDER=1 BORDERCOLOR="#000000" CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=100%>
	<TR>
		<TD WIDTH=100% BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Personal message from {filefrom} (optional):</B></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=100% BGCOLOR="#e6e6e6">
			<P><I><pre>{filemessage}</pre></I></P>
		</TD>
	</TR>
</TABLE>
<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--';
	$config['voucherissuedemailbody'] = '{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset=iso-8859-1{CRLF}{CRLF}
Dear Sir, Madam,

Please, find below a voucher which grants access to {siteName}.
With this voucher you can upload once one file and make it available for download to a group of people.

Issuer = {filefrom}
Voucher link = {serverURL}?vid={filevoucheruid}
The file is available until {fileexpirydate} after which it will be automatically deleted

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset=iso-8859-1{CRLF}{CRLF}
<HTML>
<BODY>
<P>Dear Sir, Madam,</P>
<P>Please, find below a voucher which grants access to {siteName}.</P>
<P>With this voucher you can upload once one file and make it available for download to a group of people.</P>
<TABLE WIDTH=100% BORDER=1 BORDERCOLOR="#000000" CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=75>
	<COL WIDTH=800>
	<COL WIDTH=70>
	<TR>
		<TD WIDTH=75 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Issuer</B></P>
		</TD>
		<TD WIDTH=800 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Voucher link</B></P>
		</TD>
		<TD WIDTH=70 BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Valid until</B></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=75 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{filefrom}</P>
		</TD>
		<TD WIDTH=800 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER><A HREF="{serverURL}?vid={filevoucheruid}">{serverURL}?vid={filevoucheruid}</A></P>
		</TD>
		<TD WIDTH=70 BGCOLOR="#e6e6e6">
			<P ALIGN=CENTER>{fileexpirydate}</P>
		</TD>
	</TR>
</TABLE>
<P></P>
<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--';

$config['defaultvouchercancelled'] = "{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset=iso-8859-1{CRLF}{CRLF}
Dear Sir, Madam,

A voucher from {filefrom} has been cancelled.
Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset=iso-8859-1{CRLF}{CRLF}
<HTML>
<BODY>
Dear Sir, Madam,<BR><BR>A voucher from {filefrom} has been cancelled.<BR><BR>
	<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--";

	$config['site_sendfileinstructions'] = '<B>To send a file.</B><BR>Type an email address into the To: box<BR>Select BROWSE to choose a file on your computer.<BR>Select SEND FILE to upload and send the file.';
	$config['site_voucherinstructions'] = 'A Voucher allows someone to send you a file.<BR>To create a voucher. Enter an email address then select Send Voucher.<BR>An email will be send to the recipient with a link to use the Voucher.';
	$config['site_downloadinstructions'] = 'A file is available for you.<BR>Select Download File to download the file to your computer.';
	

	
	return $config;
	}
	
	}
	?>