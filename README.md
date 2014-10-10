Guzzle Subscriber for Hawk Authentication
====
Hawk specification can be found here: http://alexbilbie.com/2012/11/hawk-a-new-http-authentication-scheme/

[Using Symfony2? Check out Hawk Authentication bundle that uses Symfony2 Security component.][1]

Usage
------------

```php
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
    'json'        => array('a' => 'b'),
    'subscribers' => array($hawkAuthenticationPlugin),
]);
```

[1]: https://github.com/vytautasgimbutas/lib-hawk-authentication-bundle