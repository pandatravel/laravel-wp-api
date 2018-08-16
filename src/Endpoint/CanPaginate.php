<?php

namespace Ammonkc\WpApi\Endpoint;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class CustomPostType
 *
 * @package Ammonkc\WpApi\Endpoint
 */
trait CanPaginate
{
    /**
      * Add pagination to array or collection.
      *
      * @param array|Collection      $params
      * @param int $perPage
      * @param int $page
      * @param array $options
      *
      * @return LengthAwarePaginator
      */
    public function paginate(array $items, $total, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items, $total, $perPage, $page, $options);
    }
}
