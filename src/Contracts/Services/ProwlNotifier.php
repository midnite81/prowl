<?php


namespace Midnite81\Prowl\Contracts\Services;

use Prowl\Connector;
use Prowl\Message;

/**
 * Interface ProwlNotifier
 * @package Midnite81\Prowl\Contracts\Services
 */
interface ProwlNotifier
{

    /**
     * ProwlNotifier constructor.
     *
     * @param Connector $connector
     */
    public function __construct(Connector $connector);

    /**
     * Send Notification to Device
     *
     * @return mixed
     */
    public function sendNotification();

    /**
     * Alias for sendNotification
     *
     * @return mixed
     */
    public function send();

    /**
     * Set the message
     *
     * @param $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * Alias for setMessage
     *
     * @param $message
     * @return ProwlNotifier
     */
    public function message($message);

    /**
     * Set the Priority
     *
     * @param $priority
     * @return mixed
     * @throws OutOfRangeException
     * @throws ValueIsNotANumberException
     */
    public function setPriority($priority);

    /**
       * Alias for setPriority
       *
       * @param $priority
       * @return mixed
       * @throws OutOfRangeException
       * @throws ValueIsNotANumberException
       */
    public function priority($priority);

    /**
     * Set the Subject
     *
     * @param $subject
     * @return mixed
     */
    public function setSubject($subject);

    /**
     * Alias for setSubject
     *
     * @param $subject
     * @return mixed
     */
    public function subject($subject);

    /**
     * Set the application name
     *
     * @param $application
     * @return mixed
     */
    public function setApplication($application);

    /**
     * Alias for setApplication
     *
     * @param $application
     * @return mixed
     */
    public function app($application);


    /**
      * Set the URL (if applicable)
      *
      * @param $url
      * @return mixed
      */
    public function setUrl($url);

    /**
   * Alias for setUrl
   *
   * @param $url
   * @return mixed
   */
    public function url($url);

    /**
     * Set Device/API
     *
     * @param $device
     * @return $this
     */
    public function setDevice($device);

    /**
     * Alias for setDevice
     *
     * @param $device
     * @return $this|ProwlNotifier
     */
    public function device($device);

    /**
     * Get the device key
     *
     * @param $device
     * @return mixed
     */
    public function getDevice($device);

    /**
      * Reset the class variables
      *
      * @return mixed
      */
    public function reset();

    /**
      * Alias for reset
      *
      * @return mixed
      */
    public function clear();


}
