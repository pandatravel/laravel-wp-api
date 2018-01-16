<?php

namespace Ammon\WpApi\Auth;

use Psr\Http\Message\RequestInterface;
use Vnn\WpApiClient\Auth\AuthInterface;

/**
 * Class WpJwtAuth
 *
 * @package Ammon\WpApi\Auth
 */
class WpJwtAuth implements AuthInterface
{
    /**
     * @var string
     */
    private $token;

    /**
     * WpJwtAuth constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function addCredentials(RequestInterface $request)
    {
        return $request->withHeader(
            'Authorization',
            'Bearer ' . $this->token
        );
    }
}
