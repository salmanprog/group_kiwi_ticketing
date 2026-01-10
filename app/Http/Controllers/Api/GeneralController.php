<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Storage;
use FFMpeg;

class GeneralController extends Controller
{
    public function videoThumbnail(Request $request)
    {
        $param_rule['video_file'] = 'required|mimes:mp4';

        $response = $this->__validateRequestParams($request->all(),$param_rule);
        if( $this->__is_error )
            return $response;

        $data['thumbnail_url'] = Storage::url(generateVideoThumb($request->file('video_file')->path()));

        $this->__is_paginate = false;
        $this->__collection  = false;

        return $this->__sendResponse($data,200,'Thumbnail has been generated successfully');
    }
}
