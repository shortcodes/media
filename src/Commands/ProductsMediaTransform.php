<?php

namespace Shortcodes\Media\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductsMediaTransform extends Command
{
    protected $signature = "media:transform-products";
    protected $description = "Transform products media";

    public function handle()
    {
        $productModel = 'App\Models\Product\Product';
        $folderModel = 'App\Models\Import\Folder';

        try {

            DB::beginTransaction();

            $productModel::chunk(100, function ($objects) {
                foreach ($objects as $object) {

                    foreach ($object->medias()->orderBy('position', 'asc')->get() as $media) {
                        $storageFile = storage_path('app/' . $media->url);

                        if (file_exists($storageFile)) {
                            $object->addMedia($storageFile)
                                ->preservingOriginal()
                                ->usingName(($media->name ? $media->name : $media->title) ?? 'No name')
                                ->toMediaCollection('images');
                        }
                    }

                    $this->info('Product ID ' . $object->id . ' done.');
                }
            });

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        try {
            $this->info('Done all products');

            $folderModel::chunk(100, function ($objects) {
                foreach ($objects as $object) {

                    foreach ($object->images()->orderBy('position', 'asc')->get() as $media) {
                        $storageFile = storage_path('app/' . $media->url);

                        if (file_exists($storageFile)) {
                            $object->addMedia($storageFile)
                                ->preservingOriginal()
                                ->usingName(($media->name ? $media->name : $media->title) ?? 'No name')
                                ->toMediaCollection('images');
                        }
                    }

                    $this->info('Folder ID ' . $object->id . ' done.');
                }

            });

            $this->info('Done all folders');

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}