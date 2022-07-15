<?php
namespace Midnite81\Prowl\Contracts;

use Http\Client\Exception;
use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Services\Response;

interface Prowl
{

    /**
     * Add [POST]
     *
     * Add a notification for a particular user.
     * You must provide either event or description or both.
     *
     * @param Notification $notification
     * @return Response
     * @throws Exception
     */
    public function add(Notification $notification);

    /**
     * Alias of Add
     *
     * @param Notification $notification
     * @return Response
     * @throws Exception
     */
    public function push(Notification $notification);

    /**
     * Alias of Add
     *
     * @param Notification $notification
     * @return Response
     * @throws Exception
     */
    public function send(Notification $notification);

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
     * @throws Exception
     */
    public function verify($apiKey, $providerKey = null);

    /**
     * Retrieve Token [GET]
     *
     * Get a registration token for use in retrieve/apikey and the associated URL for the user to approve the request.
     * This is the first step in fetching an API key for a user. The token retrieved expires after 24 hours.
     *
     * @param $providerKey
     * @return Response|string
     * @throws Exception
     */
    public function retrieveToken($providerKey);

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
     * @throws Exception
     */
    public function retrieveApiKey($providerKey, $token);

    /**
     * Create a message
     *
     * @param array $attributes
     * @param array $devices
     * @return Notification
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function createMessage($attributes = [], $devices = []);
}