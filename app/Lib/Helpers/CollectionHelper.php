<?php

namespace App\Lib\Helpers;

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class CollectionHelper
{
    /**
     * Paginate a collection
     *
     * @param \Illuminate\Support\Collection $results
     * @param integer                        $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate(Collection $results, int $pageSize): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage('page');

        return self::paginateManually($results, $pageSize, $page);
    }

    /**
     * Paginate a collection
     *
     * @param \Illuminate\Support\Collection $results
     * @param integer                        $pageSize
     * @param integer                        $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginateManually(Collection $results, int $pageSize, int $page = 1): LengthAwarePaginator
    {
        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection $items
     * @param  integer                        $total
     * @param  integer                        $perPage
     * @param  integer                        $currentPage
     * @param  array                          $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator(Collection $items, int $total, int $perPage, int $currentPage, array $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, [
            'items' => $items,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'options' => $options
        ]);
    }
}
