<?php
namespace Midnite81\Prowl\Tests;

use Carbon\Carbon;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Prowl;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Services\Response;
use Midnite81\Prowl\Tests\Traits\GenerateStrings;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class ProwlTest extends TestCase
{
    use GenerateStrings;

    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_constructs_without_parameters()
    {
        $prowl = $this->instantiateProwl();

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_constructs_using_factory_method()
    {
        $prowl = $this->factoryCreate();

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_constructs_using_custom_factory_method()
    {
        $httpClient = new \Http\Adapter\Guzzle7\Client();
        $requestFactory = new GuzzleMessageFactory();

        $prowl = $this->factoryCreateCustom($httpClient, $requestFactory);

        $this->assertInstanceOf(Prowl::class, $prowl);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_message_object()
    {
        $prowl = $this->factoryCreate();

        $message = $prowl->createMessage();

        $this->assertInstanceOf(Notification::class, $message);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_exception_for_incorrect_priority_range()
    {
        $this->expectException(IncorrectPriorityValueException::class);

        /** @var Prowl $prowl */
        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setPriority(6);
    }

     #[\PHPUnit\Framework\Attributes\Test]
     public function it_throws_exception_for_too_long_url()
     {
         $this->expectException(\Midnite81\Prowl\Exceptions\ValueTooLongException::class);

         /** @var Prowl $prowl */
         $prowl = $this->factoryCreate();
         $prowl->createMessage()->setUrl($this->stringLength(513));
     }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_exception_for_too_long_application()
    {
        $this->expectException(\Midnite81\Prowl\Exceptions\ValueTooLongException::class);

        /** @var Prowl $prowl */
        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setApplication($this->stringLength(260));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_exception_for_too_long_event()
    {
        $this->expectException(\Midnite81\Prowl\Exceptions\ValueTooLongException::class);

        /** @var Prowl $prowl */
        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setEvent($this->stringLength(1030));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_exception_for_too_long_description()
    {
        $this->expectException(\Midnite81\Prowl\Exceptions\ValueTooLongException::class);

        /** @var Prowl $prowl */
        $prowl = $this->factoryCreate();
        $prowl->createMessage()->setDescription($this->stringLength(100100));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_message_alias_object()
    {
        $prowl = $this->factoryCreate();

        $message = $prowl->createNotification();

        $this->assertInstanceOf(Notification::class, $message);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_adds_notification_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(ClientInterface::class);

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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_send_notification_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(ClientInterface::class);

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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_push_notification_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(ClientInterface::class);

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

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_verifies_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(ClientInterface::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->verify('test');

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());

    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_retrieves_token_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(ClientInterface::class);

        $mockedHttpClient->shouldReceive('sendRequest')->once()->andReturn($this->successResponse());

        $prowl = Prowl::createCustom($mockedHttpClient, new GuzzleMessageFactory(), []);
        $prowlResponse = $prowl->retrieveToken('test');

        $this->assertInstanceOf(Response::class, $prowlResponse);
        $this->assertEquals(true, $prowlResponse->isSuccess());
        $this->assertEquals(200, $prowlResponse->getStatusCode());
        $this->assertEquals(998, $prowlResponse->getRemaining());
        $this->assertInstanceOf(Carbon::class, $prowlResponse->getResetDate());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_retrieves_api_key_and_returns_response_object()
    {
        $mockedHttpClient = \Mockery::mock(ClientInterface::class);

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

    /**
     * @return Prowl
     */
    protected function instantiateProwl()
    {
        return new Prowl();
    }

    /**
     * @return static
     */
    protected function factoryCreate()
    {
        return Prowl::create();
    }

    /**
     * @param $httpClient
     * @param $requestFactory
     * @return static
     */
    protected function factoryCreateCustom($httpClient, $requestFactory)
    {
        return Prowl::createCustom($httpClient, $requestFactory);
    }
}
