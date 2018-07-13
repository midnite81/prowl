<?php

namespace Midnite81\Prowl;

use Http\Adapter\Guzzle6\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\RequestFactory;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Midnite81\Prowl\Contracts\Prowl as Contract;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Services\Response;

class Prowl implements Contract
{
    protected $apiUrl;

    protected $httpClient;

    protected $requestFactory;

    /**
     * Prowl constructor.
     *
     * @param null                $httpClient
     * @param RequestFactory|null $requestFactory
     * @param array               $config
     */
    public function __construct($httpClient = null, RequestFactory $requestFactory = null, array $config = [])
    {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
        $this->processConfig($config);
    }

    /**
     * Create a prowl instance
     *
     * @param array $config
     * @return static
     */
    public static function create($config = [])
    {
        return new static(new Client(), new GuzzleMessageFactory(), $config);
    }

    /**
     * @param                $httpClient
     * @param RequestFactory $requestFactory
     * @param array          $config
     * @return static
     */
    public static function createCustom($httpClient, RequestFactory $requestFactory, $config = [])
    {
        return new static($httpClient, $requestFactory, $config);
    }


    /**
     * Add [POST]
     *
     * Add a notification for a particular user.
     * You must provide either event or description or both.
     *
     * @param Notification $notification
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function add(Notification $notification)
    {
        $request = $this->requestFactory->createRequest('POST', $this->makeUrl('add'), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ], http_build_query($notification->formParams(), null, '&'));

        $request = $this->httpClient->sendRequest($request);

        return Response::create($request);
    }

    /**
     * Create a message
     *
     * @param array $attributes
     * @param array $devices
     * @return Notification
     * @throws Exceptions\IncorrectPriorityValueException
     * @throws Exceptions\ValueTooLongException
     */
    public function createMessage($attributes = [], $devices = [])
    {
        return new Notification($attributes, $devices, $this);
    }

    /**
     * Alias of Create Message
     *
     * @param array $attributes
     * @param array $devices
     * @return Notification
     * @throws Exceptions\IncorrectPriorityValueException
     * @throws Exceptions\ValueTooLongException
     */
    public function createNotification($attributes = [], $devices = [])
    {
        return $this->createMessage($attributes, $devices);
    }

    /**
     * Alias of Add
     *
     * @param Notification $notification
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function push(Notification $notification)
    {
        return $this->add($notification);
    }

    /**
     * Alias of Add
     *
     * @param Notification $notification
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function send(Notification $notification)
    {
        return $this->add($notification);
    }

    /**
     * Verify [GET]
     * Verify an API key is valid.
     * For the sake of adding a notification do not call verify first;
     * it costs you an API call. You should only use verify to confirm an API key is valid in situations
     * like a user entering an API key into your program. If it's not valid while posting the notification,
     * you will get the appropriate error.
     *
     * @param $apiKey
     * @param $providerKey
     * @return Response
     * @throws \Http\Client\Exception
     */
    public function verify($apiKey, $providerKey = null)
    {
        $request = $this->requestFactory->createRequest('GET',
            $this->makeUrl('verify', [
                'apikey' => $apiKey,
                'providerkey' => $providerKey,
            ]));

        $request = $this->httpClient->sendRequest($request);

        return Response::create($request);
    }

    /**
     * Retrieve Token [GET]
     *
     * Get a registration token for use in retrieve/apikey and the associated URL for the user to approve the request.
     * This is the first step in fetching an API key for a user. The token retrieved expires after 24 hours.
     *
     * @param $providerKey
     * @return Response|string
     * @throws \Http\Client\Exception
     */
    public function retrieveToken($providerKey)
    {
        $request = $this->requestFactory->createRequest('GET',
            $this->makeUrl('retrieve/token', [
                'providerkey' => $providerKey,
            ]));

        $request = $this->httpClient->sendRequest($request);

        $response = Response::create($request);

        return (! empty($response->getToken())) ? $response->getToken() : $response;
    }

    /**
     * Retrieve API Key [GET]
     * [no call cost]
     *
     * Get an API key from a registration token retrieved in retrieve/token.
     * The user must have approved your request first, or you will get an error response.
     * This is the second/final step in fetching an API key for a user.
     *
     * @param $providerKey
     * @param $token
     * @return Response|string
     * @throws \Http\Client\Exception
     */
    public function retrieveApiKey($providerKey, $token)
    {
        $request = $this->requestFactory->createRequest('GET',
            $this->makeUrl('retrieve/apikey', [
                'providerkey' => $providerKey,
                'token' => $token,
            ]));

        $request = $this->httpClient->sendRequest($request);

        $response = Response::create($request);

        return (! empty($response->getRetrieveApiKey())) ? $response->getRetrieveApiKey() : $response;
    }


    /**
     * Get the api url
     *
     * @return string
     */
    protected function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Make the url
     *
     * @param       $action
     * @param array $queryString
     * @return string
     */
    protected function makeUrl($action, array $queryString = [])
    {
        $queryString = http_build_query($queryString, null, "&");
        $queryString = (! empty($queryString)) ? '?' . $queryString : '';

        return $this->getApiUrl() . '/' . $action . $queryString;
    }

    /**
     * @param array $config
     */
    protected function processConfig(array $config)
    {
        $this->apiUrl = ! empty($config['apiUrl']) ? $config['apiUrl'] : 'https://api.prowlapp.com/publicapi';
    }
}