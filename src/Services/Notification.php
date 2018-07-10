<?php

namespace Midnite81\Prowl\Services;

use Midnite81\Prowl\Contracts\Services\Notification as Contract;
use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;

class Notification implements Contract
{
    /**
     * Notification Attributes
     *
     * @var array
     */
    protected $notification = [];

    /**
     * @var array
     */
    protected $devices = [];

    /**
     * Notification constructor.
     *
     * @param array $attributes
     * @param array $devices
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function __construct($attributes = [], $devices = [])
    {
        $this->addAttributes($attributes);
        $this->devices = $devices;
    }

    /**
     * Factory Create Method
     *
     * @param       $apiKey
     * @param       $description
     * @param       $application
     * @param       $event
     * @param       $priority
     * @param       $url
     * @param       $providerKey
     * @param array $devices
     * @return static
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public static function create($apiKey, $description, $application = null, $event = null, $priority = null,
                                  $url = null, $providerKey = null, $devices = [])
    {
        return new static([
            'apiKey' => $apiKey,
            'providerKey' => $providerKey,
            'priority' => $priority,
            'url' => $url,
            'application' => $application,
            'event' => $event,
            'description' => $description,
        ], $devices);
    }

    /**
     * Factory Create From Array Method
     *
     * @param       $attributes
     * @param array $devices
     * @return static
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public static function createFromArray($attributes, $devices = [])
    {
        return new static($attributes, $devices);
    }

    /**
     * Return attributes to json
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->formParams());
    }

    /**
     * Return an array of Form Parameters
     *
     * @return array
     */
    public function formParams()
    {
        $formParams = [
            'apikey' => $this->getApiKeys(),
            'priority' => $this->getPriority(),
            'application' => $this->getApplication(),
            'event' => $this->getEvent(),
            'description' => $this->getDescription(),
        ];

        if ($this->getProviderKey()) {
            $formParams['providerkey'] = $this->getProviderKey();
        }
        if ($this->getUrl()) {
            $formParams['url'] = $this->getUrl();
        }

        return $formParams;
    }

    /**
     * Get the api key
     *
     * @return mixed
     */
    public function getApiKeys()
    {
        if (! empty($this->notification['apiKey'])) {
            return implode(',', $this->processApiKeys());
        }
        return null;
    }

    /**
     * Set Api Key [unlimited]
     * API keys separated by commas. Each API key is a 40-byte hexadecimal string.
     * When using multiple API keys, you will only get a failure response if all API keys are not valid.
     *
     * @param $value
     * @return Notification
     */
    public function setApiKeys($value)
    {
        $value = (is_string($value)) ? [$value] : $value;

        if (! empty($this->notification['apiKey'])) {
            $this->notification['apiKey'] = array_merge($this->notification['apiKey'], $value);
        } else {
            $this->notification['apiKey'] = $value;
        }
        return $this;
    }

    /**
     * Get Provider Key
     *
     * @return mixed
     */
    public function getProviderKey()
    {
        return ! empty($this->notification['providerKey']) ? $this->notification['providerKey'] : null;
    }

    /**
     * Set Provider API key. [40]
     * Only necessary if you have been whitelisted.
     *
     * @param $value
     * @return Notification
     * @throws ValueTooLongException
     */
    public function setProviderKey($value)
    {
        if (strlen($value) > 40) {
            throw new ValueTooLongException('The provider key is too long');
        }

        $this->notification['providerKey'] = $value;
        return $this;
    }

    /**
     * Get Priority
     *
     * @return mixed
     */
    public function getPriority()
    {
        return ! empty($this->notification['priority']) ? $this->notification['priority'] : 0;
    }

    /**
     * Default value of 0 if not provided. An integer value ranging [-2, 2] representing:
     * -2 Very Low
     * -1 Moderate
     * 0 Normal
     * 1 High
     * 2 Emergency
     * Emergency priority messages may bypass quiet hours according to the user's settings.
     *
     * @param $value
     * @return $this
     * @throws IncorrectPriorityValueException
     */
    public function setPriority($value)
    {
        if (! is_numeric($value) && ($value < -2 || $value > 2)) {
            throw new IncorrectPriorityValueException('You have provided an incorrect priority value');
        }

        $this->notification['priority'] = $value;
        return $this;
    }

    /**
     * Get Url
     *
     * @return mixed
     */
    public function getUrl()
    {
        return ! empty($this->notification['url']) ? $this->notification['url'] : null;
    }

    /**
     * Set the url [512]
     * The URL which should be attached to the notification.
     * This will trigger a redirect when launched, and is viewable in the notification list.
     *
     * @param $value
     * @return $this
     * @throws ValueTooLongException
     */
    public function setUrl($value)
    {
        if (strlen($value) > 512) {
            throw new ValueTooLongException('The url is too long');
        }

        $this->notification['url'] = $value;
        return $this;
    }

    /**
     * Get Application
     *
     * @return mixed
     */
    public function getApplication()
    {
        return ! empty($this->notification['application']) ? $this->notification['application'] : null;
    }

    /**
     * Set application [256]
     * The name of your application or the application generating the event.
     *
     *
     * @param $value
     * @return $this
     * @throws ValueTooLongException
     */
    public function setApplication($value)
    {
        if (strlen($value) > 256) {
            throw new ValueTooLongException('The application name is too long');
        }

        $this->notification['application'] = $value;
        return $this;
    }

    /**
     * Get Event
     *
     * @return mixed
     */
    public function getEvent()
    {
        return ! empty($this->notification['event']) ? $this->notification['event'] : null;
    }

    /**
     * Set event [1024]
     * The name of the event or subject of the notification.
     *
     * @param $value
     * @return $this
     * @throws ValueTooLongException
     */
    public function setEvent($value)
    {
        if (strlen($value) > 1024) {
            throw new ValueTooLongException('The event name is too long');
        }

        $this->notification['event'] = $value;
        return $this;
    }

    /**
     * Get Url
     *
     * @return mixed
     */
    public function getDescription()
    {
        return ! empty($this->notification['description']) ? $this->notification['description'] : null;
    }

    /**
     * Set description [10000]
     * A description of the event, generally terse.
     *
     * @param $value
     * @return $this
     * @throws ValueTooLongException
     */
    public function setDescription($value)
    {
        if (strlen($value) > 10000) {
            throw new ValueTooLongException('The provider key is too long');
        }

        $this->notification['description'] = $value;
        return $this;
    }

    /**
     * Alias for Get Description
     *
     * @return mixed
     */
    public function getMessage()
    {
        return ! empty($this->getDescription()) ? $this->getDescription() : null;
    }

    /**
     * Alias for Set Description
     *
     * @param $value
     * @return Notification
     * @throws ValueTooLongException
     */
    public function setMessage($value)
    {
        return $this->setDescription($value);
    }

    /**
     * Add the attributes
     *
     * @param $attributes
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    protected function addAttributes($attributes)
    {
        if (! empty($attributes['apiKey'])) {
            $this->setApiKeys($attributes['apiKey']);
        }

        if (! empty($attributes['providerKey'])) {
            $this->setProviderKey($attributes['providerKey']);
        }


        if (! empty($attributes['priority'])) {
            $this->setPriority($attributes['priority']);
        }

        if (! empty($attributes['url'])) {
            $this->setUrl($attributes['url']);
        }

        if (! empty($attributes['application'])) {
            $this->setApplication($attributes['application']);
        }

        if (! empty($attributes['event'])) {
            $this->setEvent($attributes['event']);
        }

        if (! empty($attributes['description'])) {
            $this->setDescription($attributes['description']);
        }
    }

    /**
     * Replace the 'device' names with API key values
     */
    protected function processApiKeys()
    {
        if (! empty($this->notification['apiKey'])) {
            for($i = 0; $i < count($this->notification['apiKey']); $i++) {
                if (array_key_exists($this->notification['apiKey'][$i], $this->devices)) {
                    $this->notification['apiKey'][$i] = $this->devices[$this->notification['apiKey'][$i]];
                }
            }
        }

        return $this->notification['apiKey'];
    }
}