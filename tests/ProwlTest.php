<?php

namespace Midnite81\Prowl\Tests;

use Carbon\Carbon;
use Http\Adapter\Guzzle7\Client;
use Http\Client\Exception as HttpClientException;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;
use Midnite81\Prowl\Prowl;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Services\Response;
use Midnite81\Prowl\Tests\Traits\GenerateStrings;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProwlTest extends TestCase
{
    use GenerateStrings;

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_constructs_without_parameters(): void
    {
        $prowl = $this->instantiateProwl();

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    /**
     * @test
     */
    public function it_constructs_using_factory_method(): void
    {
        $prowl = $this->factoryCreate();

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    /**
     * @test
     */
    public function it_constructs_using_custom_factory_method(): void
    {
        $httpClient = new Client();
        $requestFactory = new GuzzleMessageFactory();

        $prowl = $this->factoryCreateCustom($httpClient, $requestFactory);

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    /**
     * @test
     */
    public function it_creates_message_object(): void
    {
        $prowl = $this->factoryCreate();

        $message = $prowl->createMessage();

        $this->assertInstanceOf(Notification::class, $message);
    }

    /**
     * @test
     */
    public function it_throws_exception_for_incorrect_priority_range(): void
    {
        $this->expectException(IncorrectPriorityValueException::class);

        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setPriority(6);
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     */
    public function it_throws_exception_for_too_long_url(): void
    {
        $this->expectException(ValueTooLongException::class);

        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setUrl($this->stringLength(513));
    }

    /**
     * @test
     * @expectedException ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_throws_exception_for_too_long_application(): void
    {
        $this->expectException(ValueTooLongException::class);

        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setApplication($this->stringLength(260));
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     */
    public function it_throws_exception_for_too_long_event(): void
    {
        $this->expectException(ValueTooLongException::class);

        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setEvent($this->stringLength(1030));
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     */
    public function it_throws_exception_for_too_long_description(): void
    {
        $this->expectException(ValueTooLongException::class);

        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setDescription($this->stringLength(100100));
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function it_creates_message_alias_object(): void
    {
        $prowl = $this->factoryCreate();
        $message = $prowl->createNotification();

        $this->assertInstanceOf(Notification::class, $message);
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     * @throws HttpClientException
     */
    public function it_adds_notification_and_returns_response_object(): void
    {
        $client = new Client();
        $mockedHttpClient = Mockery::mock($client);

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
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     * @throws HttpClientException
     */
    public function it_send_notification_and_returns_response_object(): void
    {
        $client = new Client();
        $mockedHttpClient = Mockery::mock($client);

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
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     * @throws HttpClientException
     */
    public function it_push_notification_and_returns_response_object(): void
    {
        $client = new Client();
        $mockedHttpClient = Mockery::mock($client);

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
     * @throws HttpClientException
     */
    public function it_verifies_and_returns_response_object(): void
    {
        $client = new Client();
        $mockedHttpClient = Mockery::mock($client);

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
     * @throws HttpClientException
     */
    public function it_retrieves_token_and_returns_response_object(): void
    {
        $client = new Client();
        $mockedHttpClient = Mockery::mock($client);

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
     * @throws HttpClientException
     */
    public function it_retrieves_api_key_and_returns_response_object(): void
    {
        $client = new Client();
        $mockedHttpClient = Mockery::mock($client);

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
     * @return \GuzzleHttp\Psr7\Response|string
     */
    protected function successResponse(): \GuzzleHttp\Psr7\Response|string
    {
        $body = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<prowl>
    <success code="200" remaining="998" resetdate="1531016789" />
</prowl>
EOF;

        return new \GuzzleHttp\Psr7\Response(200, [], $body, '1.1', null);
    }

    /**
     * @return Prowl
     */
    protected function instantiateProwl(): Prowl
    {
        return new Prowl();
    }

    /**
     * @return Prowl
     */
    protected function factoryCreate(): Prowl
    {
        return Prowl::create();
    }

    /**
     * @param $httpClient
     * @param $requestFactory
     *
     * @return Prowl
     */
    protected function factoryCreateCustom($httpClient, $requestFactory): Prowl
    {
        return Prowl::createCustom($httpClient, $requestFactory);
    }
}