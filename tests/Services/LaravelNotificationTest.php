<?php

namespace Midnite81\Prowl\Tests\Services;

use Midnite81\Prowl\Services\LaravelNotification;

class LaravelNotificationTest extends NotificationTest
{
    public static function setUpBeforeClass(): void
    {
        include_once __DIR__ . '/../Functions.php';
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_gets_the_api_key()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertIsString($notification->getApiKeys());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_uses_default_device_if_not_specified()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertStringContainsString(
            config('prowl')['keys'][config('prowl')['defaultKey']],
            $notification->getApiKeys()
        );
    }

    /**
     * Overrides the method which is only needed on the base test
     * since Laravel will auto inject the key
     */
    public function it_returns_null_if_no_api_key_is_set()
    {

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
     * @param array $attributes
     * @param array $devices
     * @return LaravelNotification
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function factoryCreateFromArray($attributes = [], $devices = [])
    {
        return LaravelNotification::createFromArray($attributes, $devices);
    }

    /**
     * @return LaravelNotification
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function factoryCreate()
    {
        return LaravelNotification::create('testApi', 'testDescription', 'testApplication',
            'testEvent',1, 'testUrl', 'testProviderKey');
    }

}
