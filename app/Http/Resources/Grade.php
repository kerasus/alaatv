<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

/**
 * Class Grade
 *
 * @mixin \App\Grade
 * */
class Grade extends AlaaJsonResourceWithPagination
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->when(isset($this->displayName), $this->displayName),
        ];
    }
}
