<?php

namespace App\Providers;

use App\Models\CmsModule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('portal/*', function ($view) {
            $getCmsModules = CmsModule::getUserModules();
            // dd($getCmsModules);
            $view->with('cmsModules',$getCmsModules);
        });
    }
}
