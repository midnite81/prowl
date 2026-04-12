<?php

namespace Midnite81\Prowl\Tests;

use Carbon\Carbon;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Midnite81\Prowl\LaravelProwl;
use Midnite81\Prowl\Prowl;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Services\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class LaravelProwlTest extends ProwlTest
{

    public static function setUpBeforeClass(): void
    {
        include_once 'Functions.php';
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_sends_message_when_created_through_prowl()
    {
        $mockedHttpClient = \Mockery::mock(ClientInterface::class);

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
