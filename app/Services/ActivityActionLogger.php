<?php

namespace App\Services;

use App\Jobs\StoreActivityActionLog;

class ActivityActionLogger
{
    public static function log($data)
    {
        StoreActivityActionLog::dispatch($data);
    }
}



?>