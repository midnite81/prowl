<?php

namespace Midnite81\Prowl\Services;

use Midnite81\Prowl\Contracts\Services\Notification as Contract;

class LaravelNotification extends Notification implements Contract
{
    /**
     * LaravelNotification constructor.
     *
     * @param array $attributes
     * @param array $devices
     * @param null  $prowl
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    public function __construct(array $attributes = [], array $devices = [], $prowl = null)
    {
        $devices = (empty($devices)) ? config('prowl.keys', []) : $devices;
        parent::__construct($attributes, $devices, $prowl);
    }

    /**
     * Factory Create Method
     *
     * @param       $apiKey
     * @param       $description
     * @param null  $application
     * @param null  $event
     * @param null  $priority
     * @param null  $url
     * @param null  $providerKey
     * @param array $devices
     * @return static
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    public static function create($apiKey, $description, $application = null, $event = null, $priority = null,
                                  $url = null, $providerKey = null, $devices = [])
    {
        $devices = (empty($devices)) ? config('prowl.keys', []) : $devices;
        return parent::create($apiKey, $description, $application, $event, $priority, $url, $providerKey, $devices);
    }

    /**
     * Factory Create From Array Method
     *
     * @param       $attributes
     * @param array $devices
     * @return static
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    public static function createFromArray($attributes = [], $devices = [])
    {
        $devices = (empty($devices)) ? config('prowl.keys', []) : $devices;
        return parent::createFromArray($attributes, $devices);
    }

    /**
     * Get Api Keys
     * If none set, set the laravel default.
     *
     * @return mixed|void
     */
    public function getApiKeys()
    {
        if (empty($this->notification['apiKey'])) {
            $this->setApiKeys(config('prowl.defaultKey'));
        }

        return parent::getApiKeys();
    }
}