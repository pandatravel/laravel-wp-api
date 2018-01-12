<?php

namespace Ammonkc\WpApi;

use Vnn\WpApiClient\WpClient;

/**
 * Class WpApiClient
 * @package Ammonkc\WpApi
 *
 * @method Endpoint\Categories categories()
 * @method Endpoint\Comments comments()
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
     * @param $endpoint
     * @param array $args
     * @return Endpoint\AbstractWpEndpoint
     */
    public function __call($endpoint, array $args)
    {
        if (!isset($this->endPoints[$endpoint])) {
            $class = 'Vnn\WpApiClient\Endpoint\\' . ucfirst($endpoint);
            if (class_exists($class)) {
                if ($endpoint == 'CustomPostType' && ! empty($args)) {
                    $this->endPoints[$endpoint] = new $class($this, $args[0]);
                } else {
                    $this->endPoints[$endpoint] = new $class($this);
                }
            } else {
                throw new RuntimeException('Endpoint "' . $endpoint . '" does not exist"');
            }
        }

        return $this->endPoints[$endpoint];
    }
}
