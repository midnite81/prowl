<?php

namespace Midnite81\Prowl\Tests\Services;

use Midnite81\Prowl\Services\Notification;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    /**
     * @test
     */
    public function it_constructs()
    {
        $notification = $this->instantiateNotification();

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @test
     */
    public function it_constructs_using_factory_create_from_array()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @test
     */
    public function it_constructs_using_create_factory_method()
    {
        $notification = $this->factoryCreate();

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @return Notification
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function instantiateNotification()
    {
        return new Notification([], [], null);
    }

    /**
     * @return static
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function factoryCreateFromArray()
    {
        return Notification::createFromArray();
    }

    /**
     * @return static
     * @throws \Midnite81\Prowl\Exceptions\IncorrectPriorityValueException
     * @throws \Midnite81\Prowl\Exceptions\ValueTooLongException
     */
    protected function factoryCreate()
    {
        return Notification::create('test', 'test', 'test', 'test',
            0, 'test', 'test');
    }
}