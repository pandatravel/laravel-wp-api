<?php

namespace Ammonkc\WpApi\Endpoint;

use Ammonkc\WpApi\WpApiClient;
use GuzzleHttp\Psr7\Request;
use RuntimeException;
use Vnn\WpApiClient\Endpoint\AbstractWpEndpoint;

/**
 * Class JwtAuthToken
 *
 * @package Ammonkc\WpApi\Endpoint
 */
class JwtAuthToken extends AbstractWpEndpoint
{
    /**
     * @var WpApiClient
     */
    private $client;

    /**
     * CustomPostType constructor.
     *
     * @param WpClient $client
     */
    public function __construct(WpApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/jwt-auth/v1/token';
    }

    /**
     * @param array $data
     * @return array
     */
    public function authenticate(array $data)
    {
        $url = $this->getEndpoint();
        $request = new Request('POST', $url, ['Content-Type' => 'application/json'], json_encode($data));
        $response = $this->client->send($request);

        if ($response->hasHeader('Content-Type')
            && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json') {
            $body = json_decode($response->getBody()->getContents(), true);
            \Cookie::queue(cookie()->forever('wpapi_jwt_token', $body['token']));
            return response($body);
        }

        throw new RuntimeException('Unexpected response');
    }

    /**
     * @param array $data
     * @return array
     */
    public function validate()
    {
        $url = $this->getEndpoint() . '/validate';

        $request = new Request('POST', $url, ['Content-Type' => 'application/json']);
        $response = $this->client->send($request);

        if ($response->hasHeader('Content-Type')
            && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json') {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new RuntimeException('Unexpected response');
    }
}
