<?php

namespace Shortcodes\Media\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MediaTransform extends Command
{
    protected $signature = "media:transform {model} {--field=*}";
    protected $description = "Transform all old data stored media to store it in media library";

    public function handle()
    {
        $model = $this->argument('model');

        $fields = $this->option('field');

        if (!$model) {
            $this->error('No model has been provided.');
        }

        if (!class_exists($model)) {
            $this->error('Provided model dose not exists.');
        }

        if (!$fields) {
            $this->error('You have to provide at least one option.');
        }

        $modelObject = new $model();

        $this->line('Start transforming ' . $model);

        foreach ($fields as $field) {
            if (!Schema::hasColumn($modelObject->getTable(), $field)) {
                $this->error('Column ' . $field . ' does not exists.');
            }
        }

        try {

            DB::beginTransaction();

            $model::chunk(100, function ($objects) use ($fields) {
                foreach ($objects as $object) {
                    foreach ($fields as $field) {
                        if ($object->$field) {
                            $storageFile = storage_path('app/' . $object->$field);
                            if (file_exists($storageFile)) {
                                $object->addMedia($storageFile)
                                    ->preservingOriginal()
                                    ->toMediaCollection($field);
                            }
                        }
                    }
                    $this->info('Object ID ' . $object->id . ' done.');
                }
            });

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $this->line('Finished transforming ' . $model);
    }
}