<?php

/* ##########################

	Devart HttpTunnel v1.69.    

	HTTP tunnel script.    

	This script allows you to manage database server even if the corresponding port is blocked or remote access to database server is not allowed.    

   ##########################

*/

$LOG = 1;       // Set to "0" to disable logging
$LOG_DEBUG = 1; // Set to "0" to disable additional debug logging
$LOGFILE = "httptunnel_server.log";
$LOGFILEHANDLE = 0;
$MAXLOGSIZE = "4000000";
$LIFETIME = 180; // script lifetime in seconds. If script was started and got no client within that time - it exits.
$READ_WRITE_ATTEMPTS = 100;
$CONN_FILE = "_connections.id.php";

function checkFunctionExists($functionName) {
  if (!function_exists($functionName)) {
      echo "Required function <b>$functionName</b> does not exist.</br>";
      return false;
  }

  return true;
}

// Creates connection temporary  file if not exists and checks permission to write
function CreateAndCheckConnFile($fileName) {

  if (file_exists($fileName)){
      $newFile = @fopen($fileName, 'a');
      if($newFile)
         fclose($newFile);
      else
         echo "<b>Error</b>: Failed to open ($fileName) file: Permission denied.";
          
  }
  else{
      $newFile = @fopen($fileName, 'w');
      if($newFile){
         fwrite($newFile, "<?php echo 'Devart HTTP tunnel temporary file.'; exit; ?>\r\n"); // forbid viewing this file through browser
         fclose($newFile);
      }
      else
         echo "<b>Error</b>: Failed to create ($fileName) file: Permission denied.";
      }
        
  if(!$newFile)
    exit;
}

if (!isset($_REQUEST["a"])) {  // query from browser

	echo "Devart HttpTunnel v1.69<br />";

    $functionList = array(
	    "set_time_limit",
        "stream_socket_server",
		"stream_socket_client",
		"stream_socket_get_name",
		"stream_set_blocking",
		"stream_socket_accept",
    );
    
    $exist = true;
    foreach($functionList as $functionName) {
	  $result = checkFunctionExists($functionName);
      $exist = $exist && $result;
    }

    if ($exist)
       CreateAndCheckConnFile($CONN_FILE);
    
    if ($exist)
      echo "Tunnel script is installed correctly. <br />You can establish connections through the HTTP tunnel.";
    else
      echo "Required PHP functions listed above are not available. Tunneling script will not work without these functions. Please read PHP manuals about how to install listed functions.";
    
    exit;
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
	switch ($errno) {
	case E_ERROR:
		$errfile=preg_replace('|^.*[\\\\/]|','',$errfile);
		echo $ERRSTR."Error in line $errline of file $errfile: [$errno] $errstr\n";
		exit;
	}
}	

function shutdown () {
	global $ipsock, $rmsock, $outcount, $incount, $td, $te, $sockname, $useunix;

	if (connection_status() & 1) { // ABORTED
		logline ($_SERVER["REMOTE_ADDR"].": Irregular tunnel disconnect -> disconnecting server");
		logline ($_SERVER["REMOTE_ADDR"].": Sent ".$outcount." bytes, received ".$incount." bytes");
	} elseif (connection_status() & 2) { // TIMEOUT
		logline ($_SERVER["REMOTE_ADDR"].": PHP script timeout -> disconnecting server");
		logline ($_SERVER["REMOTE_ADDR"].": Sent ".$outcount." bytes, received ".$incount." bytes");
	}
	
	if ($ipsock) fclose($ipsock);
	if ($rmsock) fclose($rmsock);
}

function logline ($msg) {
  log_line_to_file(0, $msg);
}

function logdebug($msg) {
  log_line_to_file(1, $msg);
}

function logerr($msg) {
  global $ERRSTR;
  
  logline($msg);
  echo $ERRSTR;
  echo $msg;
}

function log_line_to_file ($debug, $msg) {
	global $LOG, $LOG_DEBUG, $MAXLOGSIZE, $LOGFILE, $LOGFILEHANDLE;	
	if ($LOG && ((! $debug) || $LOG_DEBUG)) {
		$LOGFILEHANDLE=fopen ($LOGFILE, "a");
		if ($LOGFILEHANDLE) {			
			fwrite ($LOGFILEHANDLE, date("d.m.Y H:i:s")." - $msg\r\n");
			$lstat=fstat($LOGFILEHANDLE);
			if ($lstat["size"]>$MAXLOGSIZE) rotatelog();
			fclose($LOGFILEHANDLE);
		}
	}
}

function rotatelog() {
	global $LOG, $MAXLOGSIZE, $LOGFILE, $LOGFILEHANDLE;
	if ($LOG) {
     	fwrite ($LOGFILEHANDLE, date("d.m.Y H:i:s")." - Logfile reached maximum size ($MAXLOGSIZE)- rotating.\r\n");
		fclose ($LOGFILEHANDLE);
		rename ($LOGFILE,"$LOGFILE.old");
		$LOGFILEHANDLE=fopen ($LOGFILE, "a");
		if (!$LOGFILEHANDLE)
    		$LOG=0;
		else 
		    fwrite ($LOGFILEHANDLE, date("d.m.Y H:i:s")." - Opening new Logfile.\r\n");
	}
}

function create_client_socket() {
  global $_REQUEST;
  
    if (!isset($_REQUEST["port"])) {
	  echo $ERRSTR."Port not set.";
	  return 0;
	}
	
	$port = $_REQUEST["port"];
    $client = stream_socket_client("tcp://127.0.0.1:".$port);
	if ($client) {
	  stream_set_blocking($client, 1);
	}
	return $client;
}

function send_server_script_message($command) {
    global $_REQUEST;
	
	$client = create_client_socket();
	if (!$client) {
	  logerr("Failed to create client socket");
	  return FALSE;
	}
	if (fwrite($client, $command, 1) === FALSE) {
	  logerr("Failed to send message to server script.");
	  fclose($client);
	  return FALSE;
	}
	fclose($client);
	return TRUE;
}

function increase_script_lifetime() {
  global $LIFETIME;
  
  set_time_limit($LIFETIME);
  logdebug("Script liftetime incremented with $LIFETIME");
}

function write_to_socket($socket, $buffer, $count) {
  global $READ_WRITE_ATTEMPTS;
  
  $totalCount = 0;
  $retryCount = 0;
  
  do {
    if ($retryCount > 0) {
	    usleep(10000); // 10ms
    }
    
    if (!$socket)
      break;
    
	  $written = fwrite($socket, $buffer, $count);
    $buffer = substr($buffer, $written);
	  $totalCount += $written;
    
    if ($retryCount > 0) {
      logdebug("Attempt to write #".($retryCount + 1)." Write: ".$written);
    }
    
    $retryCount = $retryCount + 1;
  } while($totalCount < $count && $retryCount < $READ_WRITE_ATTEMPTS);
  
  if ($totalCount != $count)
    logline("ERROR: Failed to write to socket $count bytes, $totalCount actually written.");
	
  return $totalCount;
}

// reads specified byte count from socket
function read_from_socket($socket, &$buffer, $count) {
  global $READ_WRITE_ATTEMPTS;
  
  $totalCount = 0;
  $retryCount = 0;
  
  $buffer = "";
  $readBuffer;
  
  do {
    if ($retryCount > 0) {
	    usleep(10000); // 10ms
    }
    
    if (!$socket)
      break;
    
    $readBuffer = fread($socket, $count);
    $read = strlen($readBuffer);
    $buffer = $buffer.$readBuffer;
    
    if ($retryCount > 0) {
      logdebug("Attempt to read #".($retryCount + 1)." Read: ".$read);
    }
    
    $totalCount += $read;
    $retryCount = $retryCount + 1;
    
  } while($totalCount < $count && $retryCount < $READ_WRITE_ATTEMPTS);
  
  if ($totalCount != $count)
    logerr("Failed to read from socket $count bytes, $totalCount actually read.");
	
  return $totalCount;
}

// packet:  size of data count |                      data count | data
// lengths:             1 byte | up to 255 bytes, typically 1 - 5| up to $MaxCount
function write_data_packet($socket, &$buffer, $count) {
    
	$countLength = strlen($count);
	// write length of data count digit
	write_to_socket($socket, $countLength, 1);
	// write data count
	write_to_socket($socket, $count, $countLength);
	// write data
	$writeCount = write_to_socket($socket, $buffer, $count);
	if ($writeCount == $count)
	  return $writeCount;
	else
	  return 0;
}

function read_data_packet($socket, &$buffer) {
    
	// obtain data length digit length
	read_from_socket($socket, $countSize, 1);
	// read data length
	read_from_socket($socket, $readCount, $countSize);
	$expectedReadCount = $readCount;
	// read data
    $readCount = read_from_socket($socket, $buffer, $readCount);
	if ($readCount == $expectedReadCount)
	  return $readCount;
	else
	  return FALSE;
}

// Start of the tunnel script
$isServer = FALSE;

if (version_compare("5.0.0",phpversion())==1) die ("Only PHP 5 or above supported");
error_reporting(0);
set_error_handler("myErrorHandler");
register_shutdown_function ("shutdown");
// no-cache
Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache"); // HTTP/1.1 
Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");

header("Content-Type: application/octet-stream");

ob_implicit_flush();

// Maximum bytes to read at once
$MaxReadCount = 16*1024;

// operation success identification
$OKSTR = "OK:";
$ERRSTR = "ER:";

$CONN_FILE_MAXSIZE = 100;

// Primary tunnel connection
// need the following REQUEST vars:
// a: "c"
// s: remote server name
// p: remote server port

// every operation output at least first three chars identifying the success of operation: "OK:" if succeeded, "ER:" if not.
//

if ($_REQUEST["a"]=="c") {  // run server script
    $isServer=TRUE;
    // clear log
	if ($LOG_DEBUG) {
	  // truncate log file
	  $logfile = fopen($LOGFILE, 'w');
	  fclose($logfile);
	}

    $dad=$_REQUEST["s"];
	$dpo=$_REQUEST["p"];

	// open the interprocess socket
	$errno = 0;
	$errstr = "";
	$ipsock = stream_socket_server("tcp://127.0.0.1:0", $errno, $errstr);
		
	if (!$ipsock) {
		logerr("stream_socket_server() failed: reason:".$errno." ".$errstr);
		exit;
	}
	
	$port=stream_socket_get_name($ipsock,false);
	$port=preg_replace('/^.*?:/','', $port);
	
	stream_set_blocking($ipsock, 1);
	
    // open the remote socket
	$rmsock = stream_socket_client("tcp://".$dad.":".$dpo, $errno, $errstr);
	
	if (!$rmsock) {
	  logerr("Failed to create remote socket at $dad: $dpo. ".$errno." ".$errstr);
	  exit;
	}
	else {
    if (isset($_REQUEST["nonblock"]))
      $block = 0;
    else
      $block = 1;
    stream_set_blocking($rmsock, $block);
	  logline("Connected to remote  $dad: $dpo");
	}
	
	// write connection identificator to file. Echo'ing is not appropriate in case of antiviral software, it would be blocked until script finishes
	$newConnFile = FALSE;
	$connFileMode = "a";
	if (file_exists($CONN_FILE)) {
	  $connFile = fopen($CONN_FILE, "r");
	  $lstat=fstat($connFile);
	  fclose($connFile);
	  if ($lstat["size"]>$CONN_FILE_MAXSIZE) {
	    $connFileMode = "w";
		$newConnFile = TRUE;
	  }
	}
	else {
	  $newConnFile = TRUE;
	}
	
	$connFile = fopen($CONN_FILE, $connFileMode);
	if ($connFile) {
	    if ($newConnFile) {
	      fwrite($connFile, "<?php echo 'Devart HTTP tunnel temporary file.'; exit; ?>\r\n"); // forbid viewing this file through browser
	    }
        $connectionId = str_replace("_", " ", $_REQUEST["id"]);	
		fwrite ($connFile, $connectionId." ".$port."\r\n");
		fclose($connFile);
	}
	else {
	  logerr("Failed to create connection temporary file.");
	  exit;
	}
	
	set_time_limit($LIFETIME);

	$exit = false;
	$buffer = array();
	$countBuffer = array();
	
	while (!$exit) {
	  logdebug("Waiting for client...");
	  $client = stream_socket_accept($ipsock);
		logline("Client accepted");
		if ($client === FALSE) {
		  logline("ERROR: Bad client.");
		  continue;
		}
	    // read command
		$count = read_from_socket($client, $buffer, 1);
		if ($count == 0) {
		  logline("Error reading client command.");
		  $exit = true;
		}
		
		logdebug("Read from client ($count): ".$buffer[0]);
				
		$command = $buffer[0];
		
		increase_script_lifetime();
		
		if ($command == "x") {  // close
		    logline("Shutting down on client request.");
		    $exit = true;  // shutdown
		}
		else if ($command == "r") { // read
		
		    if (!$rmsock) {
			  logline("ERROR: rmsock is off");
			  $exit = true;
			  break;
			}
			
			$readCount = 0;
			
      $buffer = fread($rmsock, $MaxReadCount);
      if ($buffer === FALSE) {
        logline("ERROR: Remote server disconnected.");
        $exit = true;
        break;
      }
      else {
        $readCount = strlen($buffer);
          logline("Read from remote:($readCount)");
      }
			  
		  if ($readCount >= 0) {
        if ($readCount == 0)
          logline("Nothing to read from remote.");
        
			  $writeCount = write_data_packet($client, $buffer, $readCount);
			  logdebug("Write to client($writeCount): $buffer");
			  if ($readCount > 0 && $writeCount == 0) {
          logerr("Failed to write to client.");
			    $exit = true;
			  }
			}
		}
		else if ($command == "w") { // write
			
		    if (!$rmsock) {
			  logline("ERROR: rmsock is off");
			  $exit = true;
			  break;
			}
			$readCount = read_data_packet($client, $buffer);
		    logline("Write from client: $readCount");
		    if ($readCount > 0) {
			    $writeCount = write_to_socket($rmsock, $buffer, $readCount);
			    logdebug("Write to remote($writeCount): $buffer");
			}
		}
		else if ($command == "l") {   // increment lease time
		  logline("Lease time increased.");
		}
		else if ($command == "t") {  // test connection command
		    $writeCount = write_to_socket($client, $OKSTR, strlen($OKSTR));
			if ($writeCount == 0)
			  $exit = true;
		}
        else {
		  logline("ERROR: Unknown command: $command. Exiting.");
		  $exit = true;
		}
	}
	
	logline("Server script closed.");
	exit;
}

if ($_REQUEST["a"]=="r") {  // read
	
  	$client = create_client_socket();
	if (!$client) {
	  logerr("Failed to connect to server script.");
	  exit;
	}
	
	logdebug("Client: Reading from server script");
		
	if (write_to_socket($client, "r", 1) == 0) { // write "Read" command
	  logerr("Write to server script failed.");
	  fclose($client);
	  exit;
	}
	
	$buffer;
	$readCount = read_data_packet($client, $buffer);
	if ($readCount === FALSE) {
	  logerr("Failed to read response from server script.");
	  fclose($client);
	  exit;
	}
	
	$totalCount = strlen($OKSTR) + $readCount;
	
	$outputStr = $OKSTR.$buffer;
	
	header("Content-Length: ".$totalCount);
	
  logline("Client: Read from server $readCount");
	echo $outputStr;
	
	fclose($client);
	exit;
}

if ($_REQUEST["a"]=="w") {  // write
    $client = create_client_socket();
	if (!$client) {
	  logerr("Failed to connect to server script.");
	  exit;
	}
	  
  $postBody= isset($_POST['base64body'])?base64_decode($_POST['base64body']):file_get_contents("php://input");  // Retrieve RAW POST data	
	$writeData = $postBody;
	$expectedWriteCount = strlen($writeData);
	$writeCount = write_to_socket($client, "w", 1);  // indicate that this is the "Write" command
	if ($writeCount > 0)
	  $writeCount = write_data_packet($client, $writeData, $expectedWriteCount);
	
	if ($writeCount == 0) {
	  logerr("Write to server script failed.");
	  fclose($client);
	  exit;
	}
	  
	logdebug("Client: Written $writeCount");
	
	fclose($client);
	echo $OKSTR;
	exit;
}

if ($_REQUEST["a"]=="x") {  // close
	
	echo $OKSTR."Shutted down.";
	send_server_script_message("x");
	exit;
}

if ($_REQUEST["a"] == "l") { // increment server script lease time
  
  if (send_server_script_message("l"))
    echo $OKSTR."Incremented server script lease time.";
  exit;
}

if ($_REQUEST["a"] == "t") { // test newly created connection
    
	$connectionId = str_replace("_", " ", $_REQUEST["id"]);
	logline($connectionId);
	$connections = file_get_contents($CONN_FILE);
  
    if ($connections === FALSE) {
	   logerr("Failed to open $CONN_FILE.");
	   exit;
	}
	
	$lines = explode("\r\n", $connections);
	
	// skip first line
	for($i = 1; $i < count($lines); ++$i) {
	  $line = $lines[$i];
	  $pos = strpos($line, $connectionId);
	  if ($pos === FALSE)
	    continue;
		
	  if ($pos === 0) {  // starts with
	    $parts = explode(" ", $line);
		if (count($parts) != 3) {
		  echo "Invalid connection record";
		  exit;
		}
		
		echo $OKSTR.$parts[2]."\n"."$LIFETIME\n";
		exit;
	  }
	}
	
	logerr("Connection entry not found.");
	exit;
}

logerr("Invalid tunneling script parameter: ".$_REQUEST["a"]);

?>