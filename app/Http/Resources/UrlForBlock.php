<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UrlForBlock extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'web' => $this->when(isset($this->url) , $this->url),
            'api' => $this->when(isset($this->url) , $this->url)
        ];
    }}
