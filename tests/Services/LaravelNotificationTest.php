<?php

namespace Midnite81\Prowl\Tests\Services;

use Midnite81\Prowl\Services\LaravelNotification;

class LaravelNotificationTest extends NotificationTest
{
    public static function setUpBeforeClass()
    {
        include_once __DIR__ . '/../Functions.php';
    }
    
    public function it_gets_the_api_key()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertInternalType('array', $this->getApiKeys);
    }

    /**
     * @return LaravelNotification
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function instantiateNotification()
    {
        return new LaravelNotification([], [], null);
    }

    /**
     * @return LaravelNotification
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function factoryCreateFromArray()
    {
        return LaravelNotification::createFromArray();
    }

    /**
     * @return LaravelNotification
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function factoryCreate()
    {
        return LaravelNotification::create('test', 'test', 'test', 'test',
            0, 'test', 'test');
    }

}