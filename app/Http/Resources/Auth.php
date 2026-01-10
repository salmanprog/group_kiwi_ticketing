<?php

namespace App\Http\Resources;

use App\Helpers\CustomHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Auth extends JsonResource
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
           'email'            => $this->email,
           'mobile_no'        => $this->mobile_no,
           'image_url'        => !empty($this->image_url) ? Storage::url($this->image_url) : URL::to('images/user-placeholder.jpg'),
           'blur_image'       => !empty($this->image_url) ? $this->blur_image : 'LKQ,L0of~qof_3fQ%Mj[WBfQM{fQ',
           'status'           => $this->status,
           'is_email_verify'  => $this->is_email_verify,
           'is_mobile_verify' => $this->is_mobile_verify,
           'country'          => $this->country,
           'state'            => $this->state,
           'city'             => $this->city,
           'zipcode'          => $this->zipcode,
           'address'          => $this->address,
           'latitude'         => $this->latitude,
           'longitude'        => $this->longitude,
           'api_token'        => base64_encode($this->api_token),
           'device_type'      => $this->device_type,
           'device_token'     => $this->device_token,
           'platform_type'    => $this->platform_type,
           'platform_id'      => $this->platform_id,
           'created_at'       => $this->created_at,
       ];
    }
}
