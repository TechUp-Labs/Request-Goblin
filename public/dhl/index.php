<?php
//Argument from runDHLClient.cmd
//Input for dhlclient path 
$arg0 = "";
//Input for directory path
$arg1 = "";
//Input path for Request XML files
$arg2 = "request.xml";
//Input path for Response XML Files
$arg3 = "response.xml";
//Input path for Server url details
$arg4 = "https://xmlpitest-ea.dhl.com/XMLShippingServlet";
//%FUTURE_DAY% 
$arg5 = false;
//%TIMEZONE%
$arg6 = "+08:00";


//FILE PATH
$dir_url = $arg1;
//REQUEST PATH & REQUEST FILE
$filename = $arg1.$arg2;
//RESPONSE PATH 
$response_url = $arg1.$arg3;
//SERVER URL
$server_url = $arg4;
//Future Date
$futureDate = $arg5;

//Starting the StopWatch
StopWatch::start();

//IP ADDRESS
$localIPAddress = getHostByName(getHostName());

//Set Cookie to store Client's IP address
$_COOKIE['info[0]'] = $localIPAddress;

//Set Cookie to store filename that is being executed
$_COOKIE['info[1]'] = $arg0;

//Setting timezone to UTC
date_default_timezone_set("UTC");
$utc = $arg6;
$utc_parsed_1 = str_replace(":",".",$utc);
$utc_parsed_2 = str_replace(".30",".50",$utc_parsed_1);
$utc_parsed_2 = str_replace(".45",".75",$utc_parsed_1);
$ts = (time() + ($utc_parsed_2*3600));
$dtformat = "Y_m_d_H_i_s_";

//Set Cookie for timestamp after timezone is applied
$_COOKIE['info[2]'] = $ts;

//Logger 
require_once('KLogger.php');
$log = new KLogger ($dir_url."logs/DHLClient_".date('Ymd').".log" , KLogger::DEBUG ); 

$count = 0;

goto A;
echo "\n";

A: 

//Getting the .xml file.
$file = file_get_contents($filename, true);
$len = strlen($file);

$log->LogInfo(" | START DHLClient"); 
$log->LogInfo(" | futureDate set to :: ".$futureDate);
echo  "futureDate set to :: ".$futureDate."\n";

$log->LogInfo(" | TimeZone set to :: UTC".$arg6); 
echo "TimeZone set to :: UTC".$arg6."\n";

//UTF-8 checking for .xml file.
$encoding = mb_detect_encoding($file, 'UTF-8');
if ($encoding == "UTF-8") {
	$new_server_url = $server_url.'?isUTF8Support=true';
	$reqxml= $file;
	$el_start = "<MessageReference>";
	$el_end = "</MessageReference>";
	$MessageReference = getBetween($reqxml,$el_start,$el_end);
	$log->LogInfo(" | isUTF8Support set to :: true");
} else {
	$new_server_url = $server_url;
	$MessageReference = "";
	$log->LogWarn(" | isUTF8Support set to :: false");
}

$log->LogInfo($MessageReference." | Connecting to Server IP: ".$localIPAddress." URL:".$new_server_url);
echo "Opening the connection ..... : ".$server_url."\n\n";
//echo "Connecting to Server IP: ".$localIPAddress." URL:".$new_server_url."\n\n";

//Check whether url exist.
$invalidurl = "";
$file_headers = @get_headers($new_server_url);
if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
	$invalidurl = true;
	$flushDNS = true;
	$retry = true;
} else {
	$log->LogInfo($MessageReference." | Connected to IP: ".$localIPAddress." URL:".$new_server_url." | ".StopWatch::elapsed()); 
	$flushDNS = false;
	$retry = false;
}

if ($invalidurl == true) {
}
else {
$log->LogInfo($MessageReference." | Begin sending request to XML Appl"); 

if ($encoding == "UTF-8") {
$post_header = 'Content-type: application/x-www-form-urlencoded'."\r\n".'Accept-Charset: UTF-8'."\r\n".'Content-Length: '.$len."\r\n".'futureDate: '.$futureDate."\r\n".'languageCode: PHP'."\r\n";
}
else {
$post_header = 'Content-type: application/x-www-form-urlencoded'."\r\n".'Content-Length: '.$len."\r\n".'futureDate: '.$futureDate."\r\n".'languageCode: PHP'."\r\n";
}

//Sending the Request
$stream_options = array(
	'http' => array(
	'method' => 'POST',
	'header' => $post_header,
	'content' => $file
	)
);

$log->LogInfo($MessageReference." | Finish sending request to XML Appl | ".StopWatch::elapsed()); 

$log->LogInfo($MessageReference." | Begin receiving reply from XML Appl"); 

//Getting the response
$context  = stream_context_create($stream_options);
$response = file_get_contents($new_server_url, false, $context);
$resxml=simplexml_load_string($response) or die("Error: Cannot create object");

if ($response != "") {
//Get microtime and convert into miliseconds and assign to date for .xml file creation.
$microstamp = microtime(true);
$micro = sprintf("%06d",($microstamp - floor($microstamp)) * 1000);
$milli = substr($micro, -3);
$ndatetime = date($dtformat, $ts).$milli;

//Create and write response into .xml file.
$action = fopen($response_url.$resxml->getName()."_".$ndatetime.'.xml', 'w') or die('Unable to open file!');
fwrite($action, $response);
fclose($action);

$log->LogInfo($MessageReference." | Response received and saved successfully at :".$response_url); 
echo "Response received and saved successfully at :".$response_url."\n\n";
$log->LogInfo($MessageReference." | The file name is:".$resxml->getName()."_".$ndatetime.".xml"); 
echo "The file name is:".$resxml->getName()."_".$ndatetime.".xml \n\n";
} else {
$log->LogWarn($MessageReference."| Failed to receive response."); 
echo "Failed to receive response \n\n";
}
$log->LogInfo($MessageReference." | Finished receving reply from XML Appl | ".StopWatch::elapsed()); 

$log->LogInfo($MessageReference." | Total time taken to process request and respond back to client | ".StopWatch::elapsed()); 
echo "Total time taken to process request and respond back to client | ".StopWatch::elapsed()."\n";

$log->LogInfo($MessageReference." | END DHLClient");			

}

//Unset Cookie
unset($_COOKIE['ipaddress']);

//StopWatch
class StopWatch {
  //@var $startTimes array The start times of the StopWatches
  private static $startTimes = array();

  //Start the timer 
  //@param $timerName string The name of the timer
  //@return void
  public static function start($timerName = 'default') {
    self::$startTimes[$timerName] = microtime(true);
  }

  //Get the elapsed time in seconds
  //@param $timerName string The name of the timer to start
  //@return float The elapsed time since start() was called
  public static function elapsed($timerName = 'default') {
    return round((microtime(true) - self::$startTimes[$timerName]) * 1000);
  }
}

function getBetween($reqxml,$el_start,$el_end){
  $el_config = explode($el_start, $reqxml);
  if (isset($el_config[1])){
      $el_config = explode($el_end, $el_config[1]);
      return $el_config[0];
  }
  return '';
}

//Flush DNS 
if ($flushDNS == true) {
	$getOSName = PHP_OS_FAMILY;
	//Windows', 'BSD', 'Darwin', 'Solaris', 'Linux' or 'Unknown'.
	$count = $count + 1;		
	
	if ($count > 1) {
	} else {
		echo "\n================= Please Wait for 60 seconds; Retry in progress ...... ================= \n\n";
		Switch ($getOSName) {
			case "Windows": //Windows OS
				$cmd_str = "ipconfig /flushdns";
				$responsetxt = exec($cmd_str);
				$log->LogInfo($MessageReference."WINDOWS OS -> ".$cmd_str." -> ".$responsetxt); 
				echo "WINDOWS OS -> ".$cmd_str." -> ".$responsetxt."\n\n";
				break;
				
			case "Darwin": //Macintosh
				$cmd_str = "dscacheutil -flushcache";
				$responsetxt = exec($cmd_str);
				$log->LogInfo($MessageReference."MAC OS -> ".$cmd_str." -> ".$responsetxt); 
				echo "MAC OS -> ".$cmd_str." -> ".$responsetxt."\n\n";
				break;
				
			case "Linux": //Unix/Linux OS
				$cmd_str_1 = "nscd -I hosts";
				$responsetxt_1 = exec($cmd_str_1);
				$log->LogInfo($MessageReference."Unix/Linux OS -> ".$cmd_str_1." -> ".$responsetxt_1); 
				echo "Unix/Linux OS -> ".$cmd_str_1." -> ".$responsetxt_1."\n\n";
				
				$cmd_str_2 = "dnsmasq restart";
				$responsetxt_2 = exec($cmd_str_2);
				$log->LogInfo($MessageReference."Unix/Linux OS -> ".$cmd_str_2." -> ".$responsetxt_2); 
				echo "Unix/Linux OS -> ".$cmd_str_2." -> ".$responsetxt_2."\n\n";
	
				$cmd_str_3 = "rndc restart";
				$responsetxt_3 = exec($cmd_str_3);
				$log->LogInfo($MessageReference."Unix/Linux OS -> ".$cmd_str_3." -> ".$responsetxt_3); 
				echo "Unix/Linux OS -> ".$cmd_str_3." -> ".$responsetxt_3."\n\n";
				break;
			
			case "Solaris":
			case "BSD":
			case "Unknown": //Unknown
				$log->LogInfo($MessageReference." | Unable to flush DNS");	
				$log->LogWarn($MessageReference." | Unable to flush DNS"); 
				echo "Unable to flush DNS \n\n";
				break;
		}
		sleep(60);
		
	}
	if ($count > 3) {
		echo "=================    Three (3) retries are done - please contact DHL Support Team       ====================== \n\n";
		$log->LogInfo($MessageReference." | Total time taken to process request and respond back to client | ".StopWatch::elapsed()); 
		echo "Total time taken to process request and respond back to client | ".StopWatch::elapsed()."\n";
		$log->LogInfo($MessageReference." | END DHLClient");			
		exit();
	} else {
		$log->LogInfo(" | RETRY =========> ".($count));
		echo "\nRETRY =========> ".($count)."\n";

		goto A;
	}
} else {}

?>