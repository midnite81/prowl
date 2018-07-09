<?php

namespace Midnite81\Prowl\Services;

class LaravelNotification extends Notification
{
    /**
     * LaravelNotification constructor.
     *
     * @param array $attributes
     * @param array $devices
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    public function __construct(array $attributes = [], array $devices = [])
    {
        $devices = (empty($devices)) ? config('prowl.keys', []) : $devices;
        parent::__construct($attributes, $devices);
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