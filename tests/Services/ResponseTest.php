<?php

namespace Midnite81\Prowl\Tests\Services;

use Midnite81\Prowl\Services\Response;
use Midnite81\Xml2Array\Xml2Array;
use Midnite81\Xml2Array\XmlResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseTest extends TestCase
{
    protected $successResponseObject;

    protected $failureResponseObject;

    protected $response;

    protected $failedResponse;

    protected $retrieveApiKeyResponse;

    protected $retrieveTokenResponse;


    /**
     * @test
     */
    public function it_constructs()
    {
        $this->assertInstanceOf(Response::class, $this->response);
    }

    /**
     * @test
     */
    public function it_returns_an_array()
    {
        $array = $this->response->toArray();

        $this->assertInternalType('array', $array);
        $this->assertArrayHasKey('isSuccess', $array);
        $this->assertArrayHasKey('isError', $array);
        $this->assertArrayHasKey('statusCode', $array);
        $this->assertArrayHasKey('remaining', $array);
        $this->assertArrayHasKey('resetDate', $array);
        $this->assertArrayHasKey('errorCode', $array);
        $this->assertArrayHasKey('errorMessage', $array);
        $this->assertArrayHasKey('retrieveApiKey', $array);
        $this->assertArrayHasKey('retrieveToken', $array);
    }

    /**
     * @test
     */
    public function it_returns_json()
    {
        $json = $this->response->toJson();

        $this->assertInternalType('string', $json);
        $this->assertJson($json);
    }

    /**
     * @test
     */
    public function it_returns_underlying_response()
    {
        $response = $this->response->getResponse();

        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $response);
    }

    /**
     * @test
     */
    public function it_returns_the_body()
    {
        $response = $this->response->getContents();

        $this->assertInternalType('string', $response);
        $this->stringContains('xml');
    }
    
    /** 
     * @test 
     */
    public function it_returns_formatted_xml()
    {
        $response = $this->response->getFormattedXml();

        $this->assertInstanceOf(XmlResponse::class, $response);
    }

    /**
     * @test
     */
    public function it_returns_error_codes()
    {
        $response = $this->failedResponse;

        $this->assertEquals(404, $response->getErrorCode());
        $this->assertEquals('Some message', $response->getErrorMessage());
        $this->assertEquals(true, $response->isError());
    }

    /**
     * @test
     */
    public function it_returns_retrieve_key()
    {
        $response = $this->retrieveApiKeyResponse;

        $this->assertEquals('APIKEY', $response->getRetrieveApiKey());
    }

    /**
     * @test
     */
    public function it_returns_token()
    {
        $response = $this->retrieveTokenResponse;

        $this->assertEquals('TOKEN', $response->getToken());
    }


    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        $successMessage = <<<body
<?xml version="1.0" encoding="UTF-8"?>
<prowl>
    <success code="200" remaining="998" resetdate="1531016789" />
</prowl>
body;

        $this->successResponseObject = new \GuzzleHttp\Psr7\Response($status = 200, [], $successMessage, '1.1', null);

        $failureMessage = <<<body
<?xml version="1.0" encoding="UTF-8"?><prowl>
    <error code="404">Some message</error>
</prowl>
body;

        $this->failureResponseObject = new \GuzzleHttp\Psr7\Response($status = 200, [], $failureMessage, '1.1', null);

        $retrieveApiKeyObject = <<<body
<?xml version="1.0" encoding="UTF-8"?><prowl>
    <success code="200" remaining="REMAINING" resetdate="1531016789"
    />
    <retrieve apikey="APIKEY" />
</prowl>
body;

        $retrieveApiKeyResponseObject = new \GuzzleHttp\Psr7\Response($status = 200, [], $retrieveApiKeyObject, '1.1', null);

        $retrieveTokenObject = <<<body
<?xml version="1.0" encoding="UTF-8"?><prowl>
    <success code="200" remaining="REMAINING" resetdate="1531016789"
    />
    <retrieve token="TOKEN" url="URL" />
</prowl>
body;
        $retrieveTokenResponseObject = new \GuzzleHttp\Psr7\Response($status = 200, [], $retrieveTokenObject, '1.1', null);

        $this->response = new Response($this->successResponseObject);
        $this->failedResponse = new Response($this->failureResponseObject);
        $this->retrieveApiKeyResponse = new Response($retrieveApiKeyResponseObject);
        $this->retrieveTokenResponse = new Response($retrieveTokenResponseObject);
    }


}