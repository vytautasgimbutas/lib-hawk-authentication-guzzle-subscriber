<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tornado\Component\HawkAuthentication\HawkAuthenticationSubscriber;
use Dflydev\Hawk\Client\ClientBuilder;
use Dflydev\Hawk\Credentials\Credentials;
use GuzzleHttp\Client;

$id = '12345';
$secret = 'asd';

$client = ClientBuilder::create()->build();
$credentials = new Credentials($secret, 'sha256', $id);

$hawkAuthenticationPlugin = new HawkAuthenticationSubscriber(
    $client,
    $credentials
);

$httpClient = new Client();
/** @var \GuzzleHttp\Message\Response $response */
$response = $httpClient->post('https://test.dev/app_dev.php/rest?a=b', [
    'json' => array('a' => 'b'),
    'subscribers' => array($hawkAuthenticationPlugin),
]);

echo (string) $response;
