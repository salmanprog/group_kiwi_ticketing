<?php

namespace App\Http\Resources;

use App\Helpers\CustomHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       return [
           'name'             => $this->name,
           'slug'             => $this->slug,
           'ticketType'       => $this->ticketType,
           'saleChannel'      => $this->saleChannel,
           'description'      => $this->description,
           'price'             => $this->price,
       ];
    }
}
