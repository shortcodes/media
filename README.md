# Mediable

Package to organise media files and it's customization

# Install

    composer require shortcodes/media
    
# Usage

Model must use trait `Shortcodes/Media/Mediable` and implement `HasMedia` interface

### Register collections

To register collections add method  `registerMediaCollections` and add collections you need 

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('my-other-collection');
    }

You can define if collection is singular (add `singleFile()` method) or multiple (by default)

### Uploading media files

To be able to upload media files you have to set media routes. You can do that by placing `Route::mediaRoutes();
` in your routes file.

Then you will be able to use it under `POST /media` in basic namespace while providing `file` field


In response you will get:

```
"data": {
  "id": 1,
  "name": "example",
  "size": 272605,
  "url": "http://localhost/storage/60/example.jpg"
}
```

> If your disk is not set to public the URL you receive will be proper for chosen disk.

After uploading media it is placed in `MediaLibrary` and can be attach to model

### Attaching single file to model

To attach file to model you need simply to make request (`POST` or `PATCH`) to create or modify model with parameters previously described in `registerMediaCollections` method like in example

    Model::create([...$someArticleAttributes,
        'avatar': 1
    ]);

In `avatar` single collection you need to provide media id received while uploading media.

> While updating model object that already have an avatar and collection is set to `singleFile()` avatar will be replaced 

### Attaching multiple files to model

To attach multiple media files to model you need simply to make request (`POST` or `PATCH`) to create or modify model with parameters previously described in `registerMediaCollections` method like in example

    Model::create([...$someArticleAttributes,
        'my-other-collection': [1,2,3,4]
    ]);

In `my-other-collection` property collection you need to provide array of media ids received while uploading media.

> REMEMBER! While updating model object collection not set to single all skipped media id in array will be deleted. 

In this case images are automatically reordered by provided `ids`.

### Attaching multiple files to model without removing actual

In case you need to add media file without removing all missing in array you can use `add` key in collection data request

    Model::create([...$someArticleAttributes,
        'my-other-collection': [
            'add' => [1]
        ]
    ]);
    
### Deleting selected files from model

In case you need to remove selected media from object you can use `delete` key in collection data request


    Model::create([...$someArticleAttributes,
        'my-other-collection': [
            'delete' => [3, 4]
        ]
    ]);

### Attaching file while uploading

You may also attach file directly to demanded models providing proper fields in request `model_type`,`model_id`,`model_collection`. 

The `model_collection` field is optional. Remember that `model_type` must use trait `Mediable`

### Attach manipulations to uploaded image

You may also add some manipulations to uploaded media.

For now only available manipulation ther is is cropping.

You can perform it by performing PATCH request 

    PATCH /media/{mediaId}
        
    {
       "manipulations": [
           {
               "type":"crop",
               "x":0, 
               "y":0,
               "width":10,
               "height": 500
           }
       ]
    }

where `x` and `y` are coordinates to starting point and `width` and `height` are dimentions of cropped rectangle.

### Additional features

More about library can be found at official documentation page https://github.com/spatie/laravel-medialibrary
