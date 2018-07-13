<?php
namespace Midnite81\Prowl\Tests;

use Carbon\Carbon;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Midnite81\Prowl\Prowl;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Services\Response;
use PHPUnit\Framework\TestCase;

class ProwlTest extends TestCase
{
    /**
     * @test
     */
    public function it_constructs_without_parameters()
    {
        $prowl = new Prowl();

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    /**
     * @test
     */
    public function it_constructs_using_factory_method()
    {
        $prowl = Prowl::create();

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    /**
     * @test
     */
    public function it_constructs_using_custom_factory_method()
    {
        $httpClient = new \Http\Adapter\Guzzle6\Client();
        $requestFactory = new GuzzleMessageFactory();

        $prowl = Prowl::createCustom($httpClient, $requestFactory);

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    /**
     * @test
     */
    public function it_creates_message_object()
    {
        $prowl = Prowl::create();

        $message = $prowl->createMessage();

        $this->assertInstanceOf(Notification::class, $message);
    }

    /**
     * @test
     */
    public function it_creates_message_alias_object()
    {
        $prowl = Prowl::create();

        $message = $prowl->createNotification();

        $this->assertInstanceOf(Notification::class, $message);
    }

    /**
     * @test
     */
    public function it_adds_notification_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(\Http\Adapter\Guzzle6\Client::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $notification = Notification::create('test', 'test', 'test', 'test', 0, 'test');

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->add($notification);

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());

    }

    /**
     * @test
     */
    public function it_send_notification_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(\Http\Adapter\Guzzle6\Client::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $notification = Notification::create('test', 'test', 'test', 'test', 0, 'test');

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->send($notification);

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());

    }

    /**
     * @test
     */
    public function it_push_notification_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(\Http\Adapter\Guzzle6\Client::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $notification = Notification::create('test', 'test', 'test', 'test', 0, 'test');

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->push($notification);

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());

    }

    /**
     * @test
     */
    public function it_verifies_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(\Http\Adapter\Guzzle6\Client::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->verify('test');

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());

    }

    /**
     * @test
     */
    public function it_retrieves_token_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(\Http\Adapter\Guzzle6\Client::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->retrieveToken('test');

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());
    }

    /**
     * @test
     */
    public function it_retrieves_api_key_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(\Http\Adapter\Guzzle6\Client::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->retrieveApiKey('test', 'test');

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());
    }

    /**
     * @return string
     */
    protected function successResponse()
    {
        $body = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<prowl>
    <success code="200" remaining="998" resetdate="1531016789" />
</prowl>
EOF;

        return new \GuzzleHttp\Psr7\Response(200, [], $body, '1.1', null);
    }
}