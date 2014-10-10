<?php

namespace Tornado\Component\HawkAuthentication;

use Dflydev\Hawk\Client\ClientInterface;
use Dflydev\Hawk\Client\Request;
use Dflydev\Hawk\Credentials\CredentialsInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\SubscriberInterface;

/**
 * HawkAuthenticationSubscriber
 *
 * @author Vytautas Gimbutas <vytautas@gimbutas.net>
 */
class HawkAuthenticationSubscriber implements SubscriberInterface
{
    protected $client;
    protected $credentials;

    public function __construct(ClientInterface $client, CredentialsInterface $credentials)
    {
        $this->client = $client;
        $this->credentials = $credentials;
    }

    public function getEvents()
    {
        return [
            'before' => ['signRequest', 'last'],
        ];
    }

    public function signRequest(BeforeEvent $event)
    {
        $request = $event->getRequest();

        $options = array();
        if ($request->hasHeader('Content-Type')) {
            $options['content_type'] = $request->getHeader('Content-Type');
        }

        $requestBody = (string) $request->getBody();
        if (!empty($requestBody)) {
            $options['payload'] = $requestBody;
        }

        $queryParameters = $request->getQuery()->toArray();
        if (!empty($queryParameters)) {
            $options['ext'] = http_build_query($queryParameters);
        }

        /** @var Request $hawkRequest */
        $hawkRequest = $this->client->createRequest(
            $this->credentials,
            $request->getUrl(),
            $request->getMethod(),
            $options
        );

        $request->setHeader($hawkRequest->header()->fieldName(), $hawkRequest->header()->fieldValue());
    }
}
