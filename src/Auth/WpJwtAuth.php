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
     * @var boolean
     */
    private $serialize;

    /**
     * WpJwtAuth constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($token, $serialize = false)
    {
        $this->token = $token;
        $this->serialize = $serialize;
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
            'Bearer ' . $this->decrypt($this->token, $this->serialize)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($token, $serialize)
    {
        if (is_null($serialize)) {
            $serialize = false;
        }

        return decrypt($token, $serialize);
    }
}
