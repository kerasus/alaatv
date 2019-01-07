<?php
/**
 * Created by PhpStorm.
 * User: sohrab
 * Date: 2018-09-17
 * Time: 18:41
 */

namespace App\Classes\Search;


use App\Classes\Search\{Filters\Tags, Tag\ProductTagManagerViaApi};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\{Builder};
use Illuminate\Support\Facades\{Cache};

class ProductSearch extends SearchAbstract
{
    protected $model        = "App\Product";
    protected $pageName     = 'productPage';
    protected $numberOfItemInEachPage = 28;
    protected $validFilters = [
        'name',
        'tags',
    ];

    /**
     * @param array $filters
     *
     * @return mixed
     */
    protected function apply(array $filters) : LengthAwarePaginator
    {
        $this->pageNum = $this->setPageNum($filters);
//        dd($this->pageNum);
        $key = $this->makeCacheKey($filters);
        return Cache::tags([
                               'product',
                               'search',
                           ])
                    ->remember($key, $this->cacheTime, function () use ($filters) {
                        $query = $this->applyDecoratorsFromFiltersArray($filters, $this->model->newQuery());
//                        dd($this->getResults($query));
                        return $this->getResults($query);
                    });
    }

    /**
     * @param Builder $query
     *
     * @return mixed
     */
    protected function getResults(Builder $query)
    {
        $result = $query
//            Conflict with admin panel
                      ->active()
                        ->doesntHave('parents')
                        ->whereNull('deleted_at')
                        ->orderBy("created_at", "desc")
                        ->paginate($this->numberOfItemInEachPage,
                                   ['*'],
                                   $this->pageName,
                                   $this->pageNum
                        );
        return $result;
    }

    /**
     * @param $decorator
     *
     * @return mixed
     */
    protected function setupDecorator($decorator)
    {
        $decorator = (new $decorator);
        if ($decorator instanceof Tags)
            $decorator->setTagManager(new ProductTagManagerViaApi());
        return $decorator;
    }
}