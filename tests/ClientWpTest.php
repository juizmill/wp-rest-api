<?php

namespace WpRestApiTest;

use RuntimeException;
use GuzzleHttp\Client;
use WpRestApi\ClientWp;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

class ClientWpTest extends TestCase
{
    public function testMethodNormalizeResponse()
    {
        $expected = ['a' => 'a1', 'b' => 'b2'];

        $client = new ClientWp($this->mockClientGuzzle(200, ClientWp::ENDPOINT_POSTS));
        $result = $client->normalizeResponse($this->mockResponse(200, $expected));

        $this->assertEquals($expected, $result);
    }

    public function testReturnIfStatusCodeIsDifferentFrom200 ()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Endpoint not found, status code: 401');

        $clientGuzzle = $this->mockClientGuzzle(401, ClientWp::ENDPOINT_POSTS);

        $client = new ClientWp($clientGuzzle);
        $client->get(ClientWp::ENDPOINT_POSTS);
    }

    public function testReturnMethodGetWithoutInformingOptions()
    {
        $expected = ['a' => 'a1', 'b' => 'b2'];
        $clientGuzzle = $this->mockClientGuzzle(200, ClientWp::ENDPOINT_POSTS, $expected);

        $client = new ClientWp($clientGuzzle);
        $result = $client->get(ClientWp::ENDPOINT_POSTS);

        $this->assertEquals($expected, $result);
    }

    public function testReturnMethodGetInformingOptions()
    {
        $expected = ['a' => 'a1', 'b' => 'b2'];
        $clientGuzzle = $this->mockClientGuzzle(200, ClientWp::ENDPOINT_POSTS, $expected, ['status' => 'publish']);

        $client = new ClientWp($clientGuzzle);
        $result = $client->get(ClientWp::ENDPOINT_POSTS, ['status' => 'publish']);

        $this->assertEquals($expected, $result);
    }

    public function mockClientGuzzle($statusCode, $endpoint, $expected = null, array $options = [])
    {
        $client = new Client(['base_uri' => 'http://google.com']);

        $mock = \Mockery::mock($client);
        $mock->shouldReceive('get')->with($endpoint, $options)->andReturn($this->mockResponse($statusCode, $expected));

        return $mock;
    }

    public function mockResponse($statusCode, $expected = null)
    {
        $mockStream = \Mockery::mock(StreamInterface::class);
        $mockStream->shouldReceive('getContents')->andReturn(json_encode($expected));

        $mockResponse = \Mockery::mock(ResponseInterface::class);
        $mockResponse->shouldReceive('getStatusCode')->andReturn($statusCode);
        $mockResponse->shouldReceive('getBody')->andReturn($mockStream);

        return $mockResponse;
    }
}
