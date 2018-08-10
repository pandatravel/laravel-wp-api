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
    public function paginate(array $params, $perPage = 15, $page = null, $options = [])
    {
        $params['per_page'] = $perPage;
        $params['page'] = $page;
        $items = $this->get(null, $params);
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items, $items->count(), $perPage, $page, $options);
    }
}
