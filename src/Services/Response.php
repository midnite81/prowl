<?php

namespace Midnite81\Prowl\Services;

use Carbon\Carbon;
use Exception;
use Midnite81\Xml2Array\Xml2Array;
use Midnite81\Xml2Array\XmlResponse;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    protected string $contents;

    protected XmlResponse $xml;

    protected array $attributes;

    /**
     * Response constructor.
     *
     * @param ResponseInterface $response
     * @throws Exception
     */
    public function __construct(ResponseInterface $response)
    {
        $this->setUp($response);
        $this->setAttributes();
    }

    /**
     * Factory Create Method
     *
     * @param $response
     * @return static
     * @throws Exception
     */
    public static function create($response): static
    {
        return new static($response);
    }

    /**
     * Attributes to array
     *
     * @return mixed
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Attributes to json
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->attributes);
    }

    /**
     * Get the underlying response
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the contents of the response
     *
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * Get formatted xml
     *
     * @return mixed
     */
    public function getFormattedXml(): mixed
    {
        return $this->xml;
    }


    /**
     * Get status code
     *
     * @return mixed
     */
    public function getStatusCode(): mixed
    {
        return $this->get('statusCode');
    }

    /**
     * Get remaining API calls before reset
     *
     * @return mixed
     */
    public function getRemaining(): mixed
    {
        return $this->get('remaining');
    }

    /**
     * Get the reset date for the API limit
     *
     * @return Carbon|null
     */
    public function getResetDate(): ?Carbon
    {
        return $this->get('resetDate');
    }

    /**
     * Get error code
     *
     * @return mixed
     */
    public function getErrorCode(): mixed
    {
        return $this->get('errorCode');
    }

    /**
     * Get error message
     *
     * @return mixed
     */
    public function getErrorMessage(): mixed
    {
        return $this->get('errorMessage');
    }

    /**
     * Get api key
     *
     * @return mixed
     */
    public function getRetrieveApiKey(): mixed
    {
        return $this->get('retrieveApiKey');
    }

    /*
     * Get Token
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->get('retrieveToken');
    }

    /**
     * Is success response
     *
     * @return mixed
     */
    public function isSuccess(): mixed
    {
        return $this->get('isSuccess');
    }

    /**
     * Is error response
     *
     * @return mixed
     */
    public function isError(): mixed
    {
        return $this->get('isError');
    }

    /**
     * @param ResponseInterface $response
     * @throws Exception
     */
    protected function setUp(ResponseInterface $response): void
    {
        $this->response = $response;
        $this->contents = $this->response->getBody()->getContents();
        $this->xml = Xml2Array::create($this->contents);
    }

    /**
     * Set Attributes
     */
    protected function setAttributes()
    {

        $resetDate = ! empty($this->xml['success']['@attributes']['resetdate']) ?
                            Carbon::createFromTimestamp($this->xml['success']['@attributes']['resetdate']) : null;

        $this->attributes = [
            'isSuccess' => ! empty($this->xml['success']),
            'isError' => ! empty($this->xml['error']),
            'statusCode' => ! empty($this->xml['success']['@attributes']['code']) ?
                                    $this->xml['success']['@attributes']['code'] : null,
            'remaining' => ! empty($this->xml['success']['@attributes']['remaining']) ?
                                    $this->xml['success']['@attributes']['remaining'] : null,
            'resetDate' => $resetDate,
            'errorCode' => ! empty($this->xml['error']['@attributes']['code']) ?
                                    $this->xml['error']['@attributes']['code'] : null,
            'errorMessage' => ! empty($this->xml['error']['@content']) ?
                                    $this->xml['error']['@content'] : null,
            'retrieveApiKey' => ! empty($this->xml['retrieve']['@attributes']['apikey']) ?
                                     $this->xml['retrieve']['@attributes']['apikey'] : null,
            'retrieveToken' => ! empty($this->xml['retrieve']['@attributes']['token']) ?
                                     $this->xml['retrieve']['@attributes']['token'] : null,

        ];
    }

    /**
     * Get key from attribute
     *
     * @param $key
     * @return null
     */
    public function get($key)
    {
        return ! empty($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    /**
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->attributes);
    }
}