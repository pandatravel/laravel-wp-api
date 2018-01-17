<?php

namespace Ammonkc\WpApi\Auth;

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
        if (is_null($this->token)) {
            return $request;
        }

        return $request->withHeader(
            'Authorization',
            'Bearer ' . $this->decrypt($this->token)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($token)
    {
        return decrypt($token);
    }
}
