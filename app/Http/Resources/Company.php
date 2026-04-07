<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Company extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $logoUrl = $this->logo_url ? Storage::url($this->logo_url) : null;
        $logoUrl = $logoUrl ? url($logoUrl) : asset('images/kiwi-logo.png');

        return [
            'id' => $this->id,
            'login_url' => $this->login_url,
            'logo_url' => $logoUrl,
            'auth_code' => $this->auth_code,
            'name' => $this->name,
            'slug' => $this->slug,
            'image_url' => $this->image_url,
            'address' => $this->address,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'mobile_no' => $this->mobile_no,
            'email' => $this->email,
            'website' => $this->website,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
