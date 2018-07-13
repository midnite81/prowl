<?php

namespace Midnite81\Prowl\Tests;

use Carbon\Carbon;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Midnite81\Prowl\LaravelProwl;
use Midnite81\Prowl\Prowl;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Services\Response;
use PHPUnit\Framework\TestCase;

class LaravelProwlTest extends ProwlTest
{

    public static function setUpBeforeClass()
    {
        include_once 'Functions.php';
    }

    /**
     * @test
     */
    public function it_sends_message_when_created_through_prowl()
    {
        $mockedHttpClient = \Mockery::mock(\Http\Adapter\Guzzle6\Client::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $prowl = $this->factoryCreateCustom($mockedHttpClient, new GuzzleMessageFactory());

        $send = $prowl->createMessage([
            'apiKey' => ['some-value'],
            'providerKey' => 'some-value',
            'priority' => 1,
            'url' => 'some-value',
            'application' => 'some-value',
            'event' => 'some-value',
            'description' => 'some-value',
        ])->add();

        $this->assertInstanceOf(\Midnite81\Prowl\Services\Response::class, $send);
    }

    /**
     * @return Prowl
     */
    protected function instantiateProwl()
    {
        return new LaravelProwl();
    }

    /**
     * @return static
     */
    protected function factoryCreate()
    {
        return LaravelProwl::create();
    }

    /**
     * @param $httpClient
     * @param $requestFactory
     * @return static
     */
    protected function factoryCreateCustom($httpClient, $requestFactory)
    {
        return LaravelProwl::createCustom($httpClient, $requestFactory);
    }
}