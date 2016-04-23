<?php

namespace Midnite81\Prowl\Services;

use Midnite81\Prowl\Contracts\Services\ProwlNotifier as ProwlNotifierContract;
use Midnite81\Prowl\Exceptions\OutOfRangeException;
use Midnite81\Prowl\Exceptions\ValueIsNotANumberException;
use Prowl\Connector;
use Prowl\Message;


class ProwlNotifier implements ProwlNotifierContract
{

    /**
     * @var Message prowlMessage
     */
    protected $prowlMessage;

    /**
     * @var Connector prowlConnector
     */
    protected $prowlConnector;

    /**
     * The message (description)
     *
     * @var Message
     */
    protected $message;

    /**
     * The connector
     *
     * @var Connector
     */
    protected $connector;

    /**
     * The priority (importance)
     *
     * @var $importance
     */
    protected $importance;

    /**
     * The Application Name
     *
     * @var $application
     */
    protected $application;

    /**
     * The URL
     *
     * @var $url
     */
    protected $url;

    /**
     * The Subject
     *
     * @var $subject
     */
    protected $subject;

    /**
     * The Device
     *
     * @var $device
     */
    protected $devices = [];


    /**
     * ProwlNotifier constructor.
     *
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->prowlConnector = $connector;
    }


    /**
     * Send Notification to Device
     *
     * @return mixed
     */
    public function sendNotification()
    {
        try {

            $this->prowlMessage = new Message();

            $this->prowlConnector->setFilterCallback(function($sText) { return $sText; });

            $this->prowlConnector->setIsPostRequest(true);

            $this->prepareMessage();

            // Push the message to the device
            $notificationResponse = $this->prowlConnector->push($this->prowlMessage);

            if ($notificationResponse->isError()) {
                $response['status'] = 400;
                $response['message'][] = $notificationResponse->getErrorAsString();
            } else {
                $response['status'] = 200;
                $response['message']['message_response'] = "Message sent";
                $response['message']['remaining_messages'] = $notificationResponse->getRemaining();
                $response['message']['counter_reset'] = date('Y-m-d H:i:s', $notificationResponse->getResetDate());
            }
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $response['status'] = 400;
            $response['message'][] = $invalidArgumentException->getMessage();
        } catch (\OutOfRangeException $outOfRangeException) {
            $response['status'] = 400;
            $response['message'][] = $outOfRangeException->getMessage();
        }

        return $response;
    }

    /**
     * Alias for sendNotification
     *
     * @return mixed
     */
    public function send()
    {
        return $this->sendNotification();
    }


    /**
     * Set the message
     *
     * @param $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Alias for setMessage
     *
     * @param $message
     * @return ProwlNotifier
     */
    public function message($message)
    {
        return $this->setMessage($message);
    }

    /**
     * Set the Priority
     *
     * @param $priority
     * @return mixed
     * @throws OutOfRangeException
     * @throws ValueIsNotANumberException
     */
    public function setPriority($priority)
    {
        if (! is_numeric($priority)) {
            throw new ValueIsNotANumberException;
        }

        if ($priority < -2 || $priority > 2) {
            throw new OutOfRangeException;
        }

        $this->importance = $priority;

        return $this;
    }


    /**
     * Alias for setPriority
     *
     * @param $priority
     * @return mixed
     * @throws OutOfRangeException
     * @throws ValueIsNotANumberException
     */
    public function priority($priority)
    {
        return $this->setPriority($priority);

    }

    /**
     * Set the Subject
     *
     * @param $subject
     * @return mixed
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Alias for setSubject
     *
     * @param $subject
     * @return mixed
     */
    public function subject($subject)
    {
        return $this->setSubject($subject);
    }

    /**
     * Set the application name
     *
     * @param $application
     * @return mixed
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Alias for setApplication
     *
     * @param $application
     * @return mixed
     */
    public function app($application)
    {
        return $this->setApplication($application);
    }


    /**
     * Set the URL (if applicable)
     *
     * @param $url
     * @return mixed
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Alias for setUrl
     *
     * @param $url
     * @return mixed
     */
    public function url($url)
    {
        return $this->setUrl($url);
    }

    /**
     * Set Device/API
     *
     * @param $device
     * @return $this
     */
    public function setDevice($device) {
        $this->devices[] = $device;

        return $this;
    }

    /**
     * Alias for setDevice
     *
     * @param $device
     * @return $this|ProwlNotifier
     */
    public function device($device) {
        return $this->setDevice($device);

        return $this;
    }

    /**
     * Get the device key
     *
     * @param $device
     * @return mixed
     */
    public function getDevice($device) {

        if (empty ($device)) {
            $defaultKey = "prowl.keys." . config('prowl.default-key');
            return config($defaultKey);
        }

        if (array_key_exists($device, config('prowl.keys'))) {
            return config('prowl.keys.' . $device);
        }

        return $device;

    }

    /**
     * Reset the class variables
     *
     * @return mixed
     */
    public function reset()
    {
        $this->message = '';
        $this->importance = '';
        $this->url = '';
        $this->application = '';
        $this->subject = '';
        $this->devices = [];
    }

    /**
     * Alias for reset
     *
     * @return mixed
     */
    public function clear()
    {
        return $this->reset();
    }

    /**
     * Prepare the message for sending
     */
    protected function prepareMessage()
    {
        // Set the priority
        $priority = ! empty($this->importance) ? $this->importance : 0;
        $this->prowlMessage->setPriority($priority);

        // Set the device(s) to send to
        if (empty($this->devices)) {
            $deviceKey = $this->getDevice('');
            $this->prowlMessage->addApiKey($deviceKey);
        } else {
            foreach($this->devices as $device) {
                $deviceKey = $this->getDevice($device);
                $this->prowlMessage->addApiKey($deviceKey);
            } 
        }

        // Set the Subject
        $subject = (! empty($this->subject)) ? $this->subject : "";
        $this->prowlMessage->setEvent($subject);

        // Set the Application
        $application = (! empty($this->application)) ? $this->application : 'WebApp';
        $this->prowlMessage->setApplication($application);

        // Set the Message
        $this->prowlMessage->setDescription($this->message);

        // Set the URL (if there is one to set
        if (! empty($this->url)) {
            $this->prowlMessage->setUrl($this->url) ;
        }
    }
}