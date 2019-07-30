# Mediable

Package to organise media files and it's customization

#Install

    composer require shortcodes/media
    
# Usage

Add trait  `Shortcodes/Media/Mediable`

Add method  `registerMediaCollections` to register collections

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('my-other-collection');
    }


If your collection is single file just add `singleFile()` method to registered collection

More about library can be found at official documentation page https://github.com/spatie/laravel-medialibrary