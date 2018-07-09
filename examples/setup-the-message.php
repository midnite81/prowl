<?php

use Midnite81\Prowl\Prowl;
use Midnite81\Prowl\Services\Notification;

include __DIR__ . '../vendor/autoload.php';

/**
 * The Prowl add method requires a Notification object to be passed to it.
 * This constructs the message up so that it can be sent.
 *
 * The prowl add method is aliased so you can use push or send instead of add
 * depending on your preference.
 *
 * There are several ways of constructing the Notification object. The first is to
 * create a new message from the Prowl Class itself.
 */

$prowl = Prowl::create();
$newMessageObject = $prowl->createMessage(); // you can also user $prowl->createNotification();

$newMessageObject->setApiKeys($myKey)
                 ->setPriority(0)
                 ->setEvent('The Event')
                 ->setDescription('The Description')
                 ->setApplication('The Application')
                 ->setMessage('The Message');

// Your new message object has been created and filled, you
// can now pass this to the add/push/send method.

$prowl->send($newMessageObject);

/**
 * You can also call the Notification class directly
 */

$notificationObject = Notification::create($apiKey, $description, $application, $event, $priority, $url, $providerKey);

/**
 * You can also create it from an array
 */

$notificationObject = Notification::createFromArray([
    'apiKey' => ['some-value'], // this can be an array or string, you can pass multiple api keys
    'providerKey' => 'some-value',
    'priority' => 'some-value',
    'url' => 'some-value',
    'application' => 'some-value',
    'event' => 'some-value',
    'description' => 'some-value',
]);

/**
 * Finally you can just instantiate the class yourself.
 */
$array = ['foo' => 'bar'];

$notification = new Notification($array);