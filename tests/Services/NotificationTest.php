<?php

namespace Midnite81\Prowl\Tests\Services;

use Http\Client\Exception as HttpClientException;
use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ProwlNotAvailableException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;
use Midnite81\Prowl\Services\Notification;
use Midnite81\Prowl\Tests\Traits\GenerateStrings;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    use GenerateStrings;

    /**
     * @test
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function it_constructs()
    {
        $notification = $this->instantiateNotification();

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function it_constructs_using_factory_create_from_array()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @test
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function it_sets_properties_to_class()
    {
        $notification = $this->factoryCreateFromArray(
            [
                'apiKey' => ['testApi'],
                'providerKey' => 'testProviderKey',
                'priority' => 1,
                'url' => 'testUrl',
                'application' => 'testApplication',
                'event' => 'testEvent',
                'description' => 'testDescription',
            ]
        );

        $this->assertIsString($notification->getApiKeys());
        $this->assertEquals('testApi', $notification->getApiKeys());
        $this->assertEquals('testProviderKey', $notification->getProviderKey());
        $this->assertEquals(1, $notification->getPriority());
        $this->assertEquals('testUrl', $notification->getUrl());
        $this->assertEquals('testApplication', $notification->getApplication());
        $this->assertEquals('testEvent', $notification->getEvent());
        $this->assertEquals('testDescription', $notification->getDescription());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_sets_properties_to_class_via_chain()
    {
        $notification = $this->factoryCreateFromArray()
            ->setApiKeys(['testApi'])
            ->setProviderKey('testProviderKey')
            ->setPriority(1)
            ->setUrl('testUrl')
            ->setApplication('testApplication')
            ->setEvent('testEvent')
            ->setDescription('testDescription');

        $this->assertIsString($notification->getApiKeys());
        $this->assertEquals('testApi', $notification->getApiKeys());
        $this->assertEquals('testProviderKey', $notification->getProviderKey());
        $this->assertEquals(1, $notification->getPriority());
        $this->assertEquals('testUrl', $notification->getUrl());
        $this->assertEquals('testApplication', $notification->getApplication());
        $this->assertEquals('testEvent', $notification->getEvent());
        $this->assertEquals('testDescription', $notification->getDescription());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_sets_properties_to_class_via_create()
    {
        $notification = $this->factoryCreate();

        $this->assertIsString($notification->getApiKeys());
        $this->assertEquals('testApi', $notification->getApiKeys());
        $this->assertEquals('testProviderKey', $notification->getProviderKey());
        $this->assertEquals(1, $notification->getPriority());
        $this->assertEquals('testUrl', $notification->getUrl());
        $this->assertEquals('testApplication', $notification->getApplication());
        $this->assertEquals('testEvent', $notification->getEvent());
        $this->assertEquals('testDescription', $notification->getDescription());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_can_add_more_than_one_key_to_the_class()
    {
        $notification = $this->factoryCreate();

        $this->assertCount(1, explode(',', $notification->getApiKeys()));
        $this->assertEquals('testApi', $notification->getApiKeys());

        $notification->setApiKeys('test123');

        $this->assertCount(2, explode(',', $notification->getApiKeys()));
        $this->assertEquals('testApi,test123', $notification->getApiKeys());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_sets_message_to_class()
    {
        $notification = $this->factoryCreateFromArray();

        $notification->setMessage('Test Message');

        $this->assertEquals('Test Message', $notification->getMessage());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_returns_null_if_no_api_key_is_set()
    {
        $notification = $this->factoryCreateFromArray();

        $this->assertNull($notification->getApiKeys());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_throws_exception_when_the_provider_key_is_too_long()
    {
        $this->expectException(ValueTooLongException::class);

        $notification = $this->factoryCreateFromArray();

        $notification->setProviderKey($this->stringLength(41));
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_returns_to_json()
    {
        $notification = $this->factoryCreate();

        $this->assertJson($notification->toJson());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_returns_an_array()
    {
        $notification = $this->factoryCreate();

        $this->assertIsArray($notification->formParams());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_returns_set_properties_in_array()
    {
        $notification = $this->factoryCreate();
        $notificationArray = $notification->formParams();

        $this->assertIsString($notificationArray['apikey']);
        $this->assertEquals('testApi', $notificationArray['apikey']);
        $this->assertEquals('testProviderKey', $notificationArray['providerkey']);
        $this->assertEquals(1, $notificationArray['priority']);
        $this->assertEquals('testUrl', $notificationArray['url']);
        $this->assertEquals('testApplication', $notificationArray['application']);
        $this->assertEquals('testEvent', $notificationArray['event']);
        $this->assertEquals('testDescription', $notificationArray['description']);
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_can_retrieve_devices_api_key()
    {
        $notification = $this->factoryCreateFromArray(
            [
                'apiKey' => ['testDevice'],
            ],
            [
                'testDevice' => 'testDeviceApiKey'
            ]
        );

        $this->assertIsString($notification->getApiKeys());
        $this->assertEquals('testDeviceApiKey', $notification->getApiKeys());
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     */
    public function it_constructs_using_create_factory_method()
    {
        $notification = $this->factoryCreate();

        $this->assertInstanceOf(Notification::class, $notification);
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     * @throws HttpClientException
     * @throws ProwlNotAvailableException
     */
    public function it_throws_an_exception_when_sending_without_prowl()
    {
        $this->expectException(ProwlNotAvailableException::class);

        $notification = $this->factoryCreate();

        $notification->add();
    }

    /**
     * @test
     * @throws ValueTooLongException
     * @throws IncorrectPriorityValueException
     * @throws HttpClientException
     */
    public function it_throws_an_exception_when_sending_without_prowl_send_alias()
    {
        $this->expectException(ProwlNotAvailableException::class);

        $notification = $this->factoryCreate();

        $notification->send();
    }

    /**
     * @test
     * @throws HttpClientException
     * @throws IncorrectPriorityValueException
     * @throws ProwlNotAvailableException
     * @throws ValueTooLongException
     */
    public function it_throws_an_exception_when_sending_without_prowl_push_alias()
    {
        $this->expectException(ProwlNotAvailableException::class);

        $notification = $this->factoryCreate();

        $notification->push();
    }

    /**
     * @return Notification
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    protected function instantiateNotification(): Notification
    {
        return new Notification([], [], null);
    }

    /**
     * @param array $attributes
     * @param array $devices
     *
     * @return Notification
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    protected function factoryCreateFromArray(array $attributes = [], array $devices = []): Notification
    {
        return Notification::createFromArray($attributes, $devices);
    }

    /**
     * @return Notification
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    protected function factoryCreate(): Notification
    {
        return Notification::create('testApi', 'testDescription', 'testApplication',
            'testEvent', 1, 'testUrl', 'testProviderKey');
    }
}