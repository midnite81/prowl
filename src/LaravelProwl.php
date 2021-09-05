<?php

namespace Midnite81\Prowl;

use Http\Message\RequestFactory;
use Midnite81\Prowl\Services\LaravelNotification;
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
     * Create a message
     *
     * @param array $attributes
     * @param array $devices
     *
     * @return Notification
     * @throws Exceptions\IncorrectPriorityValueException
     * @throws Exceptions\ValueTooLongException
     */
    public function createMessage(array $attributes = [], array $devices = []): Notification
    {
        $devices = (empty($devices)) ? config('prowl.keys') : $devices;
        return new LaravelNotification($attributes, $devices, $this);
    }

    /**
     * Alias of Create Message
     *
     * @param array $attributes
     * @param array $devices
     *
     * @return Notification
     * @throws Exceptions\IncorrectPriorityValueException
     * @throws Exceptions\ValueTooLongException
     */
    public function createNotification(array $attributes = [], array $devices = []): Notification
    {
        $devices = (empty($devices)) ? config('prowl.keys') : $devices;
        return $this->createMessage($attributes, $devices, $this);
    }
}