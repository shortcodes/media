<?php

namespace Shortcodes\Media\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ProductsMediaTransform extends Command
{
    protected $signature = "media:transform-products";
    protected $description = "Transform products media";

    public function handle()
    {
        $productModel = 'App\Models\Product\Product';
        $folderModel = 'App\Models\Import\Folder';


        $productModel::chunk(100, function ($objects) {
            foreach ($objects as $object) {

                foreach ($object->media()->orderBy('position', 'asc')->get() as $media) {
                    $storageFile = storage_path('app/' . $media->url);
                    $object->addMedia($storageFile)->toMediaCollection('images');
                }

                $this->info('Product ID ' . $object->id . ' done.');
            }
        });

        $this->info('Done all products');

        $folderModel::chunk(100, function ($objects) {
            foreach ($objects as $object) {

                foreach ($object->images()->orderBy('position', 'asc')->get() as $media) {
                    $storageFile = storage_path('app/' . $media->url);
                    $object->addMedia($storageFile)->toMediaCollection('images');
                }

                $this->info('Folder ID ' . $object->id . ' done.');
            }

        });

        $this->info('Done all folders');
    }

}