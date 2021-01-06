<?php


function exception_handler($exception) {
	echo '<pre>';
	echo $exception->getMessage();
	echo "\n";
	echo $exception->getTraceAsString();
}
  
set_exception_handler('exception_handler');
  


function config() {

	static $config = null;
	
	if ($config === null) {
		$config = loadConfiguration();
	}
	
	return $config;
}

function loadConfiguration() {
		
	$currentDirectory = dirname(__FILE__);
	$defaultPath = $currentDirectory . "/config.php";

	$config = new stdClass();
	if (file_exists(($defaultPath))) {
		require $defaultPath;
		if (!isset($config->client_id)) {
			throw new Exception("The client_id has not been defined in the config.php file.");
		}
		if (!isset($config->client_secret)) {
			throw new Exception("The client_secret has not been defined in the config.php file.");
		}	
	}
	else {
		if (isset($_ENV["APP_CLIENT_ID"])) {
			$config->client_id = $_ENV["APP_CLIENT_ID"];
		}
		else {
			throw new Exception("Either you need to define a local.config.php file or you have to define the environment variable APP_CLIENT_ID.");
		}
		if (isset($_ENV["APP_CLIENT_SECRET"])) {
			$config->client_secret = $_ENV["APP_CLIENT_SECRET"];
		}
		else {
			throw new Exception("Either you need to define a local.config.php file or you have to define the environment variable APP_CLIENT_SECRET.");
		}
	}

	if (!isset($config->endpoint)) { 
		if(isset($_ENV["APP_ENDPOINT"])) {
			$config->endpoint = $_ENV["APP_ENDPOINT"];
		}
		else {
			$config->endpoint = 'https://app-wallee.com';
		}
	}

	return $config;
}

function url()
{
    $s = &$_SERVER;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    $uri = $protocol . '://' . $host . $s['REQUEST_URI'];
    $segments = explode('?', $uri, 2);
    $url = $segments[0];
    return $url;
}