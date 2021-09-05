<?php

namespace Midnite81\Prowl\Tests\Services;

use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;
use Midnite81\Prowl\Services\LaravelNotification;
use Midnite81\Prowl\Services\Notification;

class LaravelNotificationTest extends NotificationTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        include_once __DIR__ . '/../Functions.php';
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function it_gets_the_api_key()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertIsString($notification->getApiKeys());
        $this->assertEquals('<iphone_api_key>', $notification->getApiKeys());
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function it_uses_default_device_if_not_specified()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertEquals('<iphone_api_key>',config('prowl')['keys'][config('prowl')['defaultKey']]);
        $this->assertEquals('<iphone_api_key>', $notification->getApiKeys());
    }

    /**
     * Overrides the method which is only needed on the base test
     * since Laravel will auto-inject the key
     */
    public function it_returns_null_if_no_api_key_is_set()
    {

    }

    /**
     * @return Notification
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    protected function instantiateNotification(): Notification
    {
        return new LaravelNotification([], [], null);
    }

    /**
     * @param array $attributes
     * @param array $devices
     *
     * @return LaravelNotification
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    protected function factoryCreateFromArray(array $attributes = [], array $devices = []): Notification
    {
        return LaravelNotification::createFromArray($attributes, $devices);
    }

    /**
     * @return Notification
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    protected function factoryCreate(): Notification
    {
        return LaravelNotification::create('testApi', 'testDescription', 'testApplication',
            'testEvent', 1, 'testUrl', 'testProviderKey');
    }

}