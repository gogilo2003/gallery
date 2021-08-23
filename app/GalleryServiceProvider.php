<?php
namespace Ogilo\Gallery;

use Illuminate\Support\ServiceProvider;

class GalleryServiceProvider extends ServiceProvider
{
    function register()
    {
        $file = __DIR__.'/Support/helpers.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }

    public function boot()
    {
        config(['admin.menu.admin-gallery'=>'Gallery']);

        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
		$this->loadRoutesFrom(__DIR__.'/../routes/api.php');

		$this->loadViewsFrom(__DIR__.'/../resources/views','gallery');
		$this->loadMigrationsFrom(__DIR__.'/../database/migrations');

		$this->publishes([
			__DIR__.'/../config/gallery.php' => config_path('gallery.php'),
		], 'gallery-config');

		$this->publishes([
			__DIR__.'/../public/css' => public_path('vendor/gallery/css'),
			__DIR__.'/../public/img' => public_path('vendor/gallery/img'),
            __DIR__.'/../public/js' => public_path('vendor/gallery/js'),
        ],'gallery-resources');

		$this->publishes([
            __DIR__.'/../resources/views'=>resource_path('views/vendor/gallery'),
        ],'gallery-views');
    }
}
