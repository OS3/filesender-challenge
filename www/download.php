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

/* ---------------------------------
 * download using PHP from a non web accessible folder
 * ---------------------------------
 *
 */
require_once('../classes/_includes.php');

global $config;

$authsaml = AuthSaml::getInstance();
$authvoucher = AuthVoucher::getInstance();
$functions = Functions::getInstance();
$saveLog = Log::getInstance();
$sendmail = Mail::getInstance();

date_default_timezone_set($config['Default_TimeZone']);

if(session_id() == ""){
	// start new session and mark it as valid because the system is a trusted source
	// set cache headers to 'private' to allow IE downloads
	session_cache_limiter('private_no_expire');
	session_start();
	$_SESSION['validSession'] = true;
}

// check we are authenticated as SAML or voucher user
if(!$authvoucher->aVoucher()) {
		logEntry("Download: Failed authentication","E_ERROR");
		echo "notAuthenticated";
} else {
if (isset($_REQUEST["vid"])) {

// load the voucher
$fileArray =  $authvoucher->getVoucher();
$fileoriginalname = $fileArray[0]['fileoriginalname'];
$fileuid = $fileArray[0]['fileuid'];	
$file=$config['site_filestore'].$fileuid.".tmp";
$filestatus = $fileArray[0]['filestatus'];

//$download_rate = 20000.5;

// check if file physically exists and is marked 'Available' before downloading
if(file_exists($file) && is_file($file) && $filestatus == 'Available')
{
        // Check the encoding for the filename and convert if necessary
        if (detect_char_encoding($fileoriginalname) == 'ISO-8859-1') { 
            $fileoriginalname = iconv("UTF-8", "ISO-8859-1", $fileoriginalname);
        }

	// set download file headers
	logEntry("Download: Start Downloading - ".$file,"E_NOTICE");
	header("Content-Type: application/force-download");
	header('Content-Type: application/octet-stream');
	header('Content-Length: '.$functions->getFileSize($file));
	header('Content-Disposition: attachment; filename="'.$fileoriginalname.'"');
	
	// as files may be very large - stop it timing out
	set_time_limit(0);
	
	session_write_close();

	// if the complete file is downloaded then send email
	if(readfile_chunked($file) === $functions->getFileSize($file)); 
	// email completed
		$tempEmail = $fileArray[0]["fileto"];
		$fileArray[0]["fileto"] = $fileArray[0]["filefrom"];	
		$fileArray[0]["filefrom"] = $tempEmail;
		$saveLog->saveLog($fileArray[0],"Download","");
		$sendmail->sendEmail($fileArray[0],$config['filedownloadedemailbody']);
		logEntry("Download: Email Sent - To:".$fileArray[0]["fileto"]."  From: ".$fileArray[0]["filefrom"] . " [".$file."]");
}
else 
{	
	print_r("file not found clause");
	// physical file was not found
	logEntry("Download: File Not Found - ".$file);
	// redirect to file is no longer available
	 header( 'Location: invalidvoucher.php' ) ;
}
}
}

// function read the chunks from the non web enabled folder
function readfile_chunked($filename,$retbytes=true) {

ob_start();

	$chunksize = 1*(1024*1024); // how many bytes per chunk
    $buffer = '';
    $cnt =0;
   	$handle = fopen($filename, 'rb'); // open the file
   	if ($handle === false) {
   	    return false;
   	}
   	while (!feof($handle)) { 
       $buffer = fread($handle, $chunksize);
       echo $buffer;
       ob_flush();
       flush();
       if ($retbytes) {
           $cnt += strlen($buffer);
       }
   }
       $status = fclose($handle);

		// log the download progress 
	   logEntry("Download Pogress: [". $filename. "] cnt-".$cnt.":retbytes-". $retbytes.": status-".$status);
   if ($retbytes && $status) {
       return $cnt; // return num. bytes delivered like readfile() does.
   }
   return $status;
   }

?>
