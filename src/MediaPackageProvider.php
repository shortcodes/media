<?php

namespace Shortcodes\Media;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Shortcodes\Media\Controllers\MediaUploadController;
use Shortcodes\Media\Observers\MediaObserver;
use Spatie\MediaLibrary\Models\Media;

class MediaPackageProvider extends ServiceProvider
{
    public function boot()
    {
        Media::observe(MediaObserver::class);

        Route::macro('mediaRoutes', function () {
            Route::post('/media', [MediaUploadController::class, 'store']);
            Route::patch('/media/{media}', [MediaUploadController::class, 'update']);
        });
    }
}
