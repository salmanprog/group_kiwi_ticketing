<?php

namespace App\Http\Resources;

use App\Helpers\CustomHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Organization extends JsonResource
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
           'id'               => $this->id,
           'name'             => $this->name,
           'slug'             => $this->slug,
           'email'             => $this->email,
           'mobile_no'        => $this->phone,
           'department'            => $this->department,
           'country'          => $this->country,
           'city'          => $this->city,
           'address'          => $this->address,
           'state'          => $this->state,
           'zip'          => $this->zip,
       ];
    }
}
