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