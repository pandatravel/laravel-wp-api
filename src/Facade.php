<?php

namespace Ammonkc\WpApi;

use Illuminate\Support\Facades\Facade;

class WpApi extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'wpapi';
    }
}
