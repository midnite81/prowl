<?php
namespace Midnite81\Prowl\Services;

use Midnite81\Prowl\Exceptions\IncorrectPriorityValueException;
use Midnite81\Prowl\Exceptions\ValueTooLongException;

class Notification
{
    const VERY_LOW = -2;
    const MODERATE = -1;
    const NORMAL = 0;
    const HIGH = 1;
    const EMERGENCY = 2;

    /**
     * Notification Attributes
     *
     * @var array
     */
    protected $notification = [];

    /**
     * Notification constructor.
     *
     * @param array $attributes
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public function __construct($attributes = [])
    {
        $this->addAttributes($attributes);
    }

    /**
     * Factory Create Method
     *
     * @param $apiKey
     * @param $description
     * @param $application
     * @param $event
     * @param $priority
     * @param $url
     * @param $providerKey
     * @return static
     * @throws IncorrectPriorityValueException
     * @throws ValueTooLongException
     */
    public static function create($apiKey, $description, $application = null, $event = null, $priority = null, $url = null, $providerKey = null)
    {
        return new static([
            'apiKey' => $apiKey,
            'providerKey' => $providerKey,
            'priority' => $priority,
            'url' => $url,
            'application' => $application,
            'event' => $event,
            'description' => $description,
        ]);
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
        return implode(',', $this->notification['apiKey']);
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

        if (! empty($this->getApiKeys())) {
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
        return $this->notification['providerKey'];
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
        return $this->notification['url'];
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
        return $this->notification['application'];
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
        return $this->notification['event'];
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
        return $this->notification['description'];
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
        return $this->getDescription();
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

        if (! empty($attributes['providerKey'])) {}
        $this->setProviderKey($attributes['providerKey']);

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
}