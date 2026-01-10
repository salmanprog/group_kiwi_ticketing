<?php

namespace App\Helpers;

use App\Models\ApplicationSetting;
use App\Models\CmsModule;
use App\Models\CmsUser;
use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use kornrunner\Blurhash\Blurhash;
use Illuminate\Support\Str;
use URL;

class CustomHelper
{
    /**
     * This function is used to get login user
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function currentUser( $guard )
    {
        $user = User::getAuthUser($guard);
        return $user;
    }

    /**
     * This function is used to send email
     * @param string $to
     * @param string $identifier
     * @param array $params
     * @param array $cc_emails
     * @param array $attachment_path
     */
    public static function sendMail( 
        $to,
        $email_view,
        $subject,
        $params,
        $cc_emails = [],
        $attachment_path = []
    )
    {
        $queue = env('IS_QUEUE_ENABLE',0) ? 'queue' : 'send';
        if( !empty($cc_emails) ){
            Mail::to($to)
                ->cc($cc_emails)
                ->{$queue}(new \App\Mail\DefaultEmail(
                    $email_view,
                    $subject,
                    $params,
                    $attachment_path
                ));
        } else {
            $mail = Mail::to($to)->{$queue}(new \App\Mail\DefaultEmail(
                $email_view,
                $subject,
                $params,
                $attachment_path
            ));
        }
    }

    /**
     * This function is used to upload single file or multiple files
     * @param string $destination_path
     * @param object|array $file
     * @param null $resize
     * @return bool
     */
    // public static function uploadMedia($destination_path,$file)
    // {
    //     if(is_array($file)){
    //         foreach ($file as $value)
    //         {
    //             $extension  = $value->extension();
    //             $fileUrl   = Storage::put($destination_path, $value);
    //             $filename[] = $fileUrl;
    //         }
    //     }else{
    //         $extension  = $file->extension();
    //         $filename = Storage::put($destination_path, $file);
    //         if( in_array($extension,['jpg','png','jpeg']) ){
    //             self::resize($destination_path,$filename);
    //         }
    //     }
    //     return $filename;
    // }

       public static function uploadMedia(string $destination_path, $file, array $resizeOptions = [])
    {
        $filenames = [];

        // Ensure folder exists
        Storage::disk('public')->makeDirectory($destination_path);

        // Multiple files
        if (is_array($file)) {
            foreach ($file as $value) {
                $filenames[] = self::saveFile($destination_path, $value, $resizeOptions);
            }
        } else {
            // Single file
            $filenames = self::saveFile($destination_path, $file, $resizeOptions);
        }

        return $filenames;
    }

    /**
     * Save single file to public disk and resize if image
     */
    protected static function saveFile(string $destination_path, $file, array $resizeOptions = [])
    {
        $extension = $file->extension();
        $filePath = $file->store($destination_path, 'public'); // e.g., company/abc.png

        // Resize if image
        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']) && !empty($resizeOptions)) {
            $fullPath = storage_path('app/public/'.$filePath);

            $img = Image::make($fullPath);

            $width = $resizeOptions['width'] ?? null;
            $height = $resizeOptions['height'] ?? null;

            // Maintain aspect ratio if one dimension missing
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($fullPath);
        }

        return $filePath; // relative path for DB
    }

    public static function uploadMediaByContent($destination_path,$file_content,$resize = null)
    {
        $filename  = time() . uniqid() . '.jpg';
        Storage::put($destination_path . '/' . $filename, $file_content);
        return $destination_path . '/' . $filename;
    }

    /**
     * This function is used to upload single file or multiple files by path
     * @param string $destination_path
     * @param object|array $file
     * @param null $resize
     * @return bool
     */
    public static function uploadMediaByPath($destination_path,$file,$resize)
    {
        if(is_array($file)){
            foreach ($file as $value){
                $extension  = $value->extension();
                $fileUrl   = Storage::putFile($destination_path, new File($value));
                $filename[] = $fileUrl;
            }
        }else{
            $extension  = pathinfo($file,PATHINFO_EXTENSION);
            $filename = Storage::putFile($destination_path, new File($file));
        }
        return $filename;
    }

    /**
     * This function is used to resize upload image
     * @param string $destination_path
     * @param string $file
     * @param string $dimension
     */
    public static function resize($destination_path,$file)
    {
        $image_content     = Storage::get($file);
        $image             = Image::make($image_content);
        $resizeWidth       = round($image->width() / 2);
        $resizeHeight      = round($image->height() / 2);
        $imagesave =  $image->orientate()
            ->resize($resizeWidth, $resizeHeight,function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->stream('jpg',60);
        Storage::put( $destination_path . '/thumb_' . basename($file) ,$imagesave->__toString());
        return $destination_path . '/thumb_' . basename($file);

    }

    /**
     * This function is used to optimize upload image
     * @param string $source_path
     * @param string $destination_path
     * @param integer $quality
     * @return mixed
     */
    public static function optimizeImage($source_path, $destination_path, $quality)
    {
        $info = getimagesize($source_path);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source_path);
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source_path);
        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source_path);

        //save file
        imagejpeg($image, $destination_path, $quality);

        //return destination file
        return $destination_path;
    }

    /**
     * This function is used to get application by identifier
     * @param string $identifer
     * @param string $meta_key
     * @return array | string
     */
    public static function appSetting($identifer, $meta_key)
    {
        Cache::forget('setting_application_setting');
        $meta_value = '';
        $records = Cache::rememberForever('setting_' . $identifer, function () use ($identifer) {
            return ApplicationSetting::getRecords($identifer);
        });
        if( count($records) ){
            foreach($records as $record){
                if( !empty($meta_key) && $record->meta_key == $meta_key ){
                    $meta_value = $record->is_file == 1  ? Storage::url($record->value) : $record->value;
                }
            }
        }
        return $meta_value;
    }

    /**
     * This function is used to get current route privilege
     * @return object $record
     */
    public static function modulePermission()
    {
        return CmsModule::getCurrentRoutePrivilege();
    }

    public static function getBlurHashImage(string $image_path, int $components_x=4, int $components_y=4)
    {
        $blurhash = \BlurHash::encode($image_path);
        return $blurhash;
    }

    public static function generateVideoThumb($source_path)
    {
        $video_dir = public_path('video-thumbnail');
        if( !is_dir($video_dir) ){
            mkdir($video_dir);
        }
        $filename = Str::random(10) . time() . uniqid() . '.jpg';
        $thumbnail_name = 'video-thumbnail' . '/' . $filename;
        $ffmpeg = \FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries'  => env('FFMPEG_BINARIES'),
            'ffprobe.binaries' => env('FFPROBE_BINARIES'),
            'temporary_directory' => public_path('video-thumbnail'),
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ));
        $video = $ffmpeg->open($source_path);
        $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(3));
        $frame->save(public_path($thumbnail_name));
        //save file to storage
        $updated_name = Storage::putFile('video-thumbnail', new File(public_path($thumbnail_name)));
        unlink(public_path($thumbnail_name));
        return $updated_name;
    }

    public static function getDuration($file_path)
    {
        $file_content = Storage::get($file_path);
        $ffmpeg = \FFMpeg\FFProbe::create(array(
            'ffmpeg.binaries'  => env('FFMPEG_BINARIES'),
            'ffprobe.binaries' => env('FFPROBE_BINARIES'),
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ));
        return $ffprobe->format($file_content)->get('duration');
    }
}
