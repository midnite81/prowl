<?php

namespace Midnite81\Prowl\Tests;

use Exception;
use Http\Adapter\Guzzle7\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ProwlNotAvailableException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;
use Midnite81\Prowl\LaravelProwl;
use Midnite81\Prowl\Prowl;
use Midnite81\Prowl\Services\Response;

class LaravelProwlTest extends ProwlTest
{

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        include_once 'Functions.php';
    }

    /**
     * @test
     * @throws Exception
     * @throws IncorrectPriorityValueException
     * @throws ProwlNotAvailableException
     * @throws ValueTooLongException
     * @throws \Http\Client\Exception
     */
    public function it_sends_message_when_created_through_prowl()
    {
        $client = new Client();
        $mockedHttpClient = \Mockery::mock($client);

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

        $this->assertInstanceOf(Response::class, $send);
    }

    /**
     * @return Prowl
     */
    protected function instantiateProwl(): Prowl
    {
        return new LaravelProwl();
    }

    /**
     * @return Prowl
     */
    protected function factoryCreate(): Prowl
    {
        return LaravelProwl::create();
    }

    /**
     * @param $httpClient
     * @param $requestFactory
     *
     * @return Prowl
     */
    protected function factoryCreateCustom($httpClient, $requestFactory): Prowl
    {
        return LaravelProwl::createCustom($httpClient, $requestFactory);
    }
}