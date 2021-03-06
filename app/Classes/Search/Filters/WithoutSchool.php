<?php
/**
 * Created by PhpStorm.
 * User: sohrab
 * Date: 2018-09-04
 * Time: 15:30
 */

namespace App\Classes\Search\Filters;

use Illuminate\Database\Eloquent\Builder;

class WithoutSchool extends FilterAbstract
{
    protected $attribute = "school";

    public function apply(Builder $builder, $value, FilterCallback $callback): Builder
    {
        return $builder->where(function ($q) {
            $q->whereNull($this->attribute)
                ->orWhere($this->attribute, "");
        });
    }
}
