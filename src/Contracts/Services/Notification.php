<?php
namespace Midnite81\Prowl\Contracts\Services;

use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;

interface Notification
{

    /**
     * Return attributes to json
     *
     * @return string
     */
    public function toJson();

    /**
     * Return an array of Form Parameters
     *
     * @return array
     */
    public function formParams();

    /**
     * Get the api key
     *
     * @return mixed
     */
    public function getApiKeys();

    /**
     * Set Api Key [unlimited]
     * API keys separated by commas. Each API key is a 40-byte hexadecimal string.
     * When using multiple API keys, you will only get a failure response if all API keys are not valid.
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     */
    public function setApiKeys($value);

    /**
     * Get Provider Key
     *
     * @return mixed
     */
    public function getProviderKey();

    /**
     * Set Provider API key. [40]
     * Only necessary if you have been whitelisted.
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     * @throws ValueTooLongException
     */
    public function setProviderKey($value);

    /**
     * Get Priority
     *
     * @return mixed
     */
    public function getPriority();

    /**
     * Default value of 0 if not provided. An integer value ranging [-2, 2] representing:
     * -2 Very Low
     * -1 Moderate
     * 0 Normal
     * 1 High
     * 2 Emergency
     * Emergency priority messages may bypass quiet hours according to the user's settings.
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     * @throws IncorrectPriorityValueException
     */
    public function setPriority($value);

    /**
     * Get Url
     *
     * @return mixed
     */
    public function getUrl();

    /**
     * Set the url [512]
     * The URL which should be attached to the notification.
     * This will trigger a redirect when launched, and is viewable in the notification list.
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     * @throws ValueTooLongException
     */
    public function setUrl($value);

    /**
     * Get Application
     *
     * @return mixed
     */
    public function getApplication();

    /**
     * Set application [256]
     * The name of your application or the application generating the event.
     *
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     * @throws ValueTooLongException
     */
    public function setApplication($value);

    /**
     * Get Event
     *
     * @return mixed
     */
    public function getEvent();

    /**
     * Set event [1024]
     * The name of the event or subject of the notification.
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     * @throws ValueTooLongException
     */
    public function setEvent($value);

    /**
     * Get Url
     *
     * @return mixed
     */
    public function getDescription();

    /**
     * Set description [10000]
     * A description of the event, generally terse.
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     * @throws ValueTooLongException
     */
    public function setDescription($value);

    /**
     * Alias for Get Description
     *
     * @return mixed
     */
    public function getMessage();

    /**
     * Alias for Set Description
     *
     * @param $value
     * @return \Midnite81\Prowl\Services\Notification
     * @throws ValueTooLongException
     */
    public function setMessage($value);
}