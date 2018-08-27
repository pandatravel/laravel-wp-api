<?php

namespace Ammonkc\WpApi\Facades;

use Ammonkc\WpApi\WpApiClient;
use Illuminate\Support\Facades\Facade;

class WpApi extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return WpApiClient::class;
    }
}
