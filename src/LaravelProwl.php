<?php

namespace Midnite81\Prowl;

use Midnite81\Prowl\Services\Notification;

class LaravelProwl extends Prowl
{
    /**
     * Prowl constructor.
     *
     * @param null                $httpClient
     * @param RequestFactory|null $requestFactory
     * @param array               $config
     */
    public function __construct($httpClient = null, RequestFactory $requestFactory = null, array $config = [])
    {
        $config = (empty($config)) ? config('prowl', []) : $config;
        parent::__construct($httpClient, $requestFactory, $config);
    }

    /**
     * @param array $attributes
     * @return Notification
     * @throws Exceptions\IncorrectPriorityValueException
     * @throws Exceptions\ValueTooLongException
     */
    public function createMessage($attributes = [])
    {
        return parent::createMessage($attributes, config('prowl.keys'));
    }

    /**
     * Alias of Create Message
     *
     * @param array $attributes
     * @return Notification
     * @throws Exceptions\IncorrectPriorityValueException
     * @throws Exceptions\ValueTooLongException
     */
    public function createNotification($attributes = [])
    {
        return parent::createNotification($attributes, config('prowl.keys'));
    }
}