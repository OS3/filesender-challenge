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

	// Start of configurable settings
	// For more information about these settings please see the
	// Administrator Reference Manual in the documentation section
	// at www.filesender.org

	// General settings
	$config['admin'] = ''; // UID's (from $config['saml_uid_attribute']) that have Administrator permissions
	$config['adminEmail'] = 'Edwin.Schaap@os3.nl,Rene.Klomp@os3.nl'; // Email address(es, separated by ,) to receive administrative messages (low disk space warning)
	$config['Default_TimeZone'] = 'Europe/Amsterdam';
	$config['site_defaultlanguage'] = 'en_GB'; // for available languages see the ./language directory
	$config['site_name'] = 'FileSender'; // Friendly name used for your FileSender instance

	// UI Settings
	$config['datedisplayformat'] = "d-m-Y"; // Format for displaying date/time, use PHP date() format string syntax
	$config["versionNumber"] = true; // Show version number (true/false)
	$config['site_showStats'] = false; // Show site upload/download stats (true/false)
	$config['displayUserName'] = true; // Show 'Welcome user' (true/false)
	
	// options - stored as array in fileoptions 
	// Voucher Locked To Sender
	$config["vlts"] = true;  	// true/false - custom per voucher option to force voucher to return file upload to sender only 

	// debug settings
	$config["debug"] = true; // Debug logging on/off (true/false)
	$config["displayerrors"] = true; // Display debug errors on screen (true/false)
	$config['dnslookup'] = true; // log includes DNS lookup (true/false)
	$config["client_specific_logging"] = false; // client logging (true/false)
	$config["client_specific_logging_uids"] = ""; // "" is log all clients, or log for specific userid's or voucheruid's seperated by comma 'xxxx,zzzzz'

	// saml settings
	$config['saml_email_attribute'] = 'mail'; // Attribute used for email address
	$config['saml_name_attribute'] = 'cn'; // Attribute used to get the user's name
#	$config['saml_uid_attribute'] = 'eduPersonTargetedID'; // Attribute to uniquely identify the user
	$config['saml_uid_attribute'] = 'uidNumber'; // Attribute to uniquely identify the user

	// AuP settings
	$config["AuP_default"] = false; //AuP value is already ticked
	$config["AuP"] = true; // AuP is displayed

	// Server settings
	$config['default_daysvalid'] = 20; // Maximum number of days before file/voucher is expired
	$config['ban_extension'] = 'exe,bat'; // Possibly dangerous file extensions that are disallowed
	$config["max_email_recipients"] = 100; // maximum email addresses allowed to send at once for voucher or file sending, a value of 0 allows unlimited emails.

	$config['max_flash_upload_size'] = '2147483648'; // 2GB
	$config['max_html5_upload_size'] = '107374182400'; // 100  GB
	$config["upload_chunk_size"]  = '2000000';//

	// update max_flash_upload_size if php.ini post_max_size and upload_max_filesize is set lower
	$config['max_flash_upload_size'] = min(let_to_num(ini_get('post_max_size'))-2048, let_to_num(ini_get('upload_max_filesize')),$config['max_flash_upload_size']);

	$config["server_drivespace_warning"] = 20; // as a percentage 20 = 20% space left on the storage drive

	// Advanced server settings, do not change unless you have a very good reason.
	$config['db_dateformat'] = "Y-m-d H:i:sP"; // Date/Time format for PostgreSQL, use PHP date format specifier syntax
	$config["crlf"] = "\n"; // for email CRLF can be changed to \r\n if required
	$config['voucherRegEx'] = "'[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}'";
	$config['voucherUIDLength'] = 36;
	$config['emailRegEx'] = "[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?";

	// site URL settings
	if ( isset($_SERVER['SERVER_NAME']) ) {
	$prot =  isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$config['site_url'] = $prot . $_SERVER['SERVER_NAME'] . '/master/www/'; // URL to Filesender
	$config['site_simplesamlurl'] =  $prot . $_SERVER['SERVER_NAME'] . '/simplesamlphp/';
	$config['site_authenticationSource'] ="example-ldap";
	$config['site_logouturl'] = $config['site_url'] . 'logout.php';
	$config['site_downloadurl'] = $config['site_url'] . 'files/'; // * Deprecated *
	}
	$config['forceSSL'] = true; // Always use SSL (true/false)

	// Support links
	$config['aboutURL'] = "";
	$config['helpURL'] = "";

	// (absolute) file locations
	$config['site_filestore'] = '/usr/share/filesender/files/';
	$config['site_temp_filestore'] = '/usr/share/filesender/tmp/';
	$config['site_simplesamllocation'] = '/usr/share/simplesamlphp/';
	$config['log_location'] = '/usr/share/filesender/log/';

	$config["db_type"] = "mysql";// pgsql or mysql
	$config['db_host'] = 'localhost';
	$config['db_database'] = 'filesender';
	$config['db_port'] = '5432';
	// database username and password
	$config['db_username'] = 'filesender';
	$config['db_password'] = 'password';

	//Optional DSN format overides db_ settings
	//$config['dsn'] = "pgsql:host=localhost;dbname=filesender";
	//$config['dsn'] = 'pgsql:host=localhost;dbname=filesender';
	//$config['dsn'] = 'sqlite:/usr/share/filesender/db/filesender.sqlite';
	//$config['dsn_driver_options'] = array();
	// dsn requires username and password in $config['db_username'] and $config['db_password']

	// cron settings
	$config['cron_exclude prefix'] = '_'; // exclude deletion of files with the prefix character listed (can use multiple characters eg '._' will ignore .xxxx and _xxxx
	$config['cron_shred'] = false; // instead of simply unlinking, overwrite expired files so they are hard to recover
	$config['cron_shred_command'] = '/usr/bin/shred -f -u -n 1 -z'; // overwrite once (-n 1) with random data, once with zeros (-z), then remove (-u)

	// email templates section
	$config['default_emailsubject'] = "{siteName}: {filename}";
	$config['filedownloadedemailbody'] = '{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset={charset}{CRLF}{CRLF}
Dear Sir, Madam,

The file below has been downloaded from {siteName} by {filefrom}.

Filename: {fileoriginalname}
Filesize: {filesize}
Download link: {serverURL}?vid={filevoucheruid}

The file is available until {fileexpirydate} after which it will be automatically deleted.

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset={charset}{CRLF}{CRLF}
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset={charset}">
</HEAD>
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
			<P ALIGN=CENTER>{htmlfileoriginalname}</P>
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
	$config['fileuploadedemailbody'] = '{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset={charset}{CRLF}{CRLF}
Dear Sir, Madam,

The file below has been uploaded to {siteName} by {filefrom} and you have been granted permission to download this file.

Filename: {fileoriginalname}
Filesize: {filesize}
Download link: {serverURL}?vid={filevoucheruid}

The file is available until {fileexpirydate} after which it will be automatically deleted.

{filemessage_start}Personal message from {filefrom}: {filemessage}{filemessage_end}

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset={charset}{CRLF}{CRLF}
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset={charset}">
</HEAD>
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
			<P ALIGN=CENTER>{htmlfileoriginalname}</P>
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
{filemessage_start}<TABLE WIDTH=100% BORDER=1 BORDERCOLOR="#000000" CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=100%>
	<TR>
		<TD WIDTH=100% BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Personal message from {filefrom}:</B></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=100% BGCOLOR="#e6e6e6">
			<P><I>{htmlfilemessage}</I></P>
		</TD>
	</TR>
</TABLE>{filemessage_end}
<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--';

$config['voucherissuedemailsubject'] = 'Voucher';
$config['voucherissuedemailbody'] = '{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset={charset}{CRLF}{CRLF}
Dear Sir, Madam,

Please, find below a voucher which grants access to {siteName}.
With this voucher you can upload once one file and make it available for download to a group of people.

Issuer: {filefrom}
Voucher link: {serverURL}?vid={filevoucheruid}

The voucher is available until {fileexpirydate} after which it will be automatically deleted.

{filemessage_start}Personal message from {filefrom}: {filemessage}{filemessage_end}

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset={charset}{CRLF}{CRLF}
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset={charset}">
</HEAD>
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
{filemessage_start}<TABLE WIDTH=100% BORDER=1 BORDERCOLOR="#000000" CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=100%>
	<TR>
		<TD WIDTH=100% BGCOLOR="#b3b3b3">
			<P ALIGN=CENTER><B>Personal message from {filefrom}:</B></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=100% BGCOLOR="#e6e6e6">
			<P><I>{htmlfilemessage}</I></P>
		</TD>
	</TR>
</TABLE>{filemessage_end}
<p></p>
<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--';

	$config['defaultvouchercancelled'] = "{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset={charset}{CRLF}{CRLF}
Dear Sir, Madam,

A voucher from {filefrom} has been cancelled.

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset={charset}{CRLF}{CRLF}
<HTML>
<HEAD>
<meta http-equiv=\"Content-Type\" content=\"text/html;charset={charset}\">
</HEAD>
<BODY>
Dear Sir, Madam,<BR><BR>A voucher from {filefrom} has been cancelled.<BR><BR>
	<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--";

	$config['defaultfilecancelled'] = "{CRLF}--simple_mime_boundary{CRLF}Content-type:text/plain; charset={charset}{CRLF}{CRLF}
Dear Sir, Madam,

The file '{fileoriginalname}' from {filefrom} has been deleted and is no longer available to download.

Best regards,

{siteName}{CRLF}{CRLF}--simple_mime_boundary{CRLF}Content-type:text/html; charset={charset}{CRLF}{CRLF}
<HTML>
<BODY>
Dear Sir, Madam,<BR><BR>The file '{htmlfileoriginalname}' from {filefrom} has been deleted and is no longer available to download.<BR><BR>
	<P>Best regards,</P>
<P>{siteName}</P>
</BODY>
</HTML>{CRLF}{CRLF}--simple_mime_boundary--";
	// End of email templates section

	// End of configurable settings

	return $config;
	}
}

// Helper function used when calculating maximum upload size from the various maxsize configuration items
function let_to_num($v){ //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
    $ret = trim($v);
    $last = strtoupper($ret[strlen($ret)-1]);
    switch($last) {
    case 'P':
        $ret *= 1024;
    case 'T':
        $ret *= 1024;
    case 'G':
        $ret *= 1024;
    case 'M':
        $ret *= 1024;
    case 'K':
        $ret *= 1024;
        break;
    }
      return $ret;
}
?>
