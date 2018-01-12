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
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * WpJwtAuth constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function addCredentials(RequestInterface $request)
    {
        return $request->withHeader(
            'Authorization',
            'Basic ' . base64_encode($this->username . ':' . $this->password)
        );
    }
}
