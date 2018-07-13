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
        $notification = new Notification([], [], null);

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @test
     */
    public function it_constructs_using_factory_create_from_array()
    {
        $notification = Notification::createFromArray();

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @test
     */
    public function it_constructs_using_create_factory_method()
    {
        $notification = Notification::create('test', 'test', 'test', 'test',
                                            0, 'test', 'test');

        $this->assertInstanceOf(Notification::class, $notification);
    }
}