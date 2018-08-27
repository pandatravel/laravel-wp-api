<?php

namespace Ammonkc\WpApi\Endpoint;

use Ammonkc\WpApi\WpApiClient;
use GuzzleHttp\Psr7\Request;
use RuntimeException;

/**
 * Class AbstractWpEndpoint
 * @package Ammonkc\WpApi\Endpoint
 */
abstract class AbstractWpEndpoint
{
    use CanPaginate;

    /**
     * @var WpApiClient
     */
    private $client;

    /**
     * Users constructor.
     * @param WpApiClient $client
     */
    public function __construct(WpApiClient $client)
    {
        $this->client = $client;
    }

    abstract protected function getEndpoint();

    /**
     * @param int $id
     * @param array $params - parameters that can be passed to GET
     *        e.g. for tags: https://developer.wordpress.org/rest-api/reference/tags/#arguments
     * @return array
     */
    public function get($id = null, array $params = null)
    {
        $uri = $this->getEndpoint();
        $uri .= (is_null($id)?'': '/' . $id);
        $uri .= (is_null($params)?'': '?' . http_build_query(array_filter($params)));

        $request = new Request('GET', $uri);
        $response = $this->client->send($request);

        if ($response->hasHeader('Content-Type')
            && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json') {
            $data = json_decode($response->getBody()->getContents(), true);

            if ($response->hasHeader('X-WP-Total') && $response->hasHeader('X-WP-TotalPages')) {
                $perPage = isset($params['per_page']) ? $params['per_page'] : 10;
                $page = isset($params['page']) ? $params['page'] : null;
                return $this->paginate($data, $response->getHeader('X-WP-Total')[0], $perPage, $page);
            }

            return $data;
        }

        throw new RuntimeException('Unexpected response');
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data)
    {
        $url = $this->getEndpoint();

        if (isset($data['id'])) {
            $url .= '/' . $data['id'];
            unset($data['id']);
        }

        $request = new Request('POST', $url, ['Content-Type' => 'application/json'], json_encode($data));
        $response = $this->client->send($request);

        if ($response->hasHeader('Content-Type')
            && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json') {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new RuntimeException('Unexpected response');
    }

    /**
     * @param int $id
     * @param array $params - parameters that can be passed to DELETE
     *        e.g. for tags: https://developer.wordpress.org/rest-api/reference/tags/#arguments
     * @return array
     */
    public function delete($id, array $params = null)
    {
        $uri = $this->getEndpoint();
        $uri .= ('/' . $id);
        $uri .= (is_null($params)?'': '?' . http_build_query(array_filter($params)));

        $request = new Request('DELETE', $uri);
        $response = $this->client->send($request);

        if ($response->hasHeader('Content-Type')
            && substr($response->getHeader('Content-Type')[0], 0, 16) === 'application/json') {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new RuntimeException('Unexpected response');
    }
}
