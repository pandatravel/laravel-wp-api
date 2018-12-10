<?php

namespace Ammonkc\WpApi;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Vnn\WpApiClient\Auth\AuthInterface;
use Vnn\WpApiClient\Http\ClientInterface;
use Vnn\WpApiClient\WpClient;

/**
 * Class WpApiClient
 * @package Ammonkc\WpApi
 *
 * @method Endpoint\Categories categories()
 * @method Endpoint\Comments comments()
 * @method Endpoint\CustomPostType customPostType()
 * @method Endpoint\JwtAuthToken jwtAuthToken()
 * @method Endpoint\Media media()
 * @method Endpoint\Pages pages()
 * @method Endpoint\Posts posts()
 * @method Endpoint\PostStatuses postStatuses()
 * @method Endpoint\PostTypes postTypes()
 * @method Endpoint\Tags tags()
 * @method Endpoint\Users users()
 */
class WpApiClient extends WpClient
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var AuthInterface
     */
    private $credentials;

    /**
     * @var string
     */
    private $wordpressUrl;

    /**
     * @var array
     */
    private $endPoints = [];

    /**
     * WpClient constructor.
     * @param ClientInterface $httpClient
     * @param string $wordpressUrl
     */
    public function __construct(ClientInterface $httpClient, $wordpressUrl = '')
    {
        $this->httpClient = $httpClient;
        $this->wordpressUrl = $wordpressUrl;
    }

    /**
     * @param $wordpressUrl
     */
    public function setWordpressUrl($wordpressUrl)
    {
        $this->wordpressUrl = $wordpressUrl;
    }

    /**
     * @param AuthInterface $auth
     */
    public function setCredentials(AuthInterface $auth)
    {
        $this->credentials = $auth;
    }

    /**
     * @param $endpoint
     * @param array $args
     * @return Endpoint\AbstractWpEndpoint
     */
    public function __call($endpoint, array $args)
    {
        if (!isset($this->endPoints[$endpoint])) {
            $class = 'Vnn\WpApiClient\Endpoint\\' . ucfirst($endpoint);
            if (class_exists($class)) {
                $this->endPoints[$endpoint] = new $class($this);
            } else {
                $class = 'Ammonkc\WpApi\Endpoint\\' . ucfirst($endpoint);
                if (class_exists($class)) {
                    if (! empty($args)) {
                        $this->endPoints[$endpoint] = new $class($this, $args[0]);
                    } else {
                        $this->endPoints[$endpoint] = new $class($this);
                    }
                } else {
                    throw new RuntimeException('Endpoint "' . $endpoint . '" does not exist"');
                }
            }
        }

        return $this->endPoints[$endpoint];
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        if ($this->credentials) {
            $request = $this->credentials->addCredentials($request);
        }

        $request = $request->withUri(
            $this->httpClient->makeUri($this->wordpressUrl . $request->getUri())
        );

        $response = $this->httpClient->send($request);

        return $response;
    }
}
