<?php

use Wallee\Sdk\Model\WebhookListener;
use Wallee\Sdk\Model\WebhookUrl;

require_once 'common.php';



$requestParameters = array();
$requestParameters['state'] = $_REQUEST['state'];
$requestParameters['space_id'] = $_REQUEST['space_id'];
$requestParameters['timestamp'] = $_REQUEST['timestamp'];
$requestParameters['code'] = $_REQUEST['code'];
$hmac = $_REQUEST['hmac'];

// 1. We need to check that the request has not been tampered.
checkHmac($requestParameters, $hmac);

// 2. Install the app.
// Setup API client
$client = new \Wallee\Sdk\ApiClient(config()->client_id, config()->client_secret);
$client->setBasePath(config()->endpoint . '/api');

$request = array(
	'code' => $_REQUEST['code'],
);

$headers = array(
	'content-type' => 'application/json'
);

$response = $client->callApi("/web-app/confirm", "POST", "", json_encode($requestParameters), $headers);

if ($response->getStatusCode() == 200) {
	echo "<strong>The installation was successful.</strong><br /><br />";
	

	$currentUrl = url();

	$webhookUrl = new WebhookUrl();
	$webhookUrl->setName("Test");
	$webhookUrl->setUrl("http://requestbin.net/r/1460p281");
	$webhookUrlPersisted = $client->getWebhookUrlService()->create($response->getData()->space->id, $webhookUrl);
	
	$webhookUrlReload = $client->getWebhookUrlService()->read($response->getData()->space->id, $webhookUrlPersisted->getId());

	echo "<strong>Webhook URL created.</strong><br /><br />";
	

	$webhookListener = new WebhookListener();
	$webhookListener->setUrl($webhookUrlReload);
	$webhookListener->setEntity(1487165678181);
	$webhookListener->setName("Test App: Manual Task");
	$webhookListener->setNotifyEveryChange(true);
	$client->getWebhookListenerService()->create($response->getData()->space->id, $webhookListener);

	echo "<strong>Webhook Listener created.</strong><br /><br />";


}
else {
	echo '<pre>';
	print_r($response);
}




