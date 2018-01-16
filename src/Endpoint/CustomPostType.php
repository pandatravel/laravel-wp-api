<?php

namespace Ammonkc\WpApi\Endpoint;

use Ammonkc\WpApi\WpApiClient;
use Vnn\WpApiClient\Endpoint\AbstractWpEndpoint;

/**
 * Class CustomPostType
 *
 * @package Ammonkc\WpApi\Endpoint
 */
class CustomPostType extends AbstractWpEndpoint
{
    /**
     * @var WpApiClient
     */
    private $client;

    /**
     * @var string
     */
    private $type;

    /**
     * CustomPostType constructor.
     *
     * @param WpClient $client
     */
    public function __construct(WpApiClient $client, $type)
    {
        $this->type = $type;
        parent::__construct($client);
    }


    /**
     * {@inheritdoc}
     */
    protected function getEndpoint()
    {
        return '/wp-json/wp/v2/' . $this->type;
    }
}
