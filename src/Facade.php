<?php

namespace Ammonkc\WpApi;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return WpApiClient::class;
    }
}
