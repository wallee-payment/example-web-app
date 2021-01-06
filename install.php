<?php
/**
 * Copyright wallee AG
 */

require_once 'common.php';


if (!isset($_GET['space_id'])) {
	throw new Exception("The 'space_id' parameter is missing.");
}


$currentUrl = url();

$parameters = array();
$parameters['redirect_uri'] = str_replace("install.php", "confirm.php", $currentUrl);
$parameters['space_id'] = $_GET['space_id'];
$parameters['scope'] = "1432203188128"; // This permission allows to update the space.
$parameters['state'] = time();
$parameters['client_id'] = config()->client_id;

$url = config()->endpoint . "/oauth/authorize?" . http_build_query($parameters); 

header('Location: ' . $url, true, 302);