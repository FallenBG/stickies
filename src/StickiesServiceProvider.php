<?php

namespace Encore\Stickies;

use Illuminate\Support\ServiceProvider;
use Encore\Admin\Admin;

class StickiesServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Stickies $extension)
    {
        // THIS IS TO FORCE PUBLISHING CHANGES TO CSS AND JS in the local environment
        if (env('APP_ENV') === 'local') {
            exec("php ../../../../../artisan vendor:publish --provider=Encore\Stickies\StickiesServiceProvider --force");
        }

        // TODO:: Investigate what is this function purpose and is it overlapping with $this->app->booted down bellow
        if (! Stickies::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'stickies');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/stickies')],
                'stickies'
            );
        }

        $this->app->booted(function () {
            Stickies::routes(__DIR__.'/../routes/web.php');
            Admin::booting(function () {
                Admin::js('vendor/laravel-admin-ext/stickies/jquery-ui.min.js');
                Admin::js('vendor/laravel-admin-ext/stickies/jquery-ui-timepicker-addon.min.js');
                Admin::js('vendor/laravel-admin-ext/stickies/trumbowyg.min.js');
                Admin::js('vendor/laravel-admin-ext/stickies/jquery.minicolors.minn.js');
                Admin::js('vendor/laravel-admin-ext/stickies/jquery.postitall.js');
                Admin::js('vendor/laravel-admin-ext/stickies/stickies.js');
                Admin::css('vendor/laravel-admin-ext/stickies/jquery-ui.min.css');
                Admin::css('vendor/laravel-admin-ext/stickies/jquery-ui-timepicker-addon.min.css');
                Admin::css('vendor/laravel-admin-ext/stickies/trumbowyg.min.css');
                Admin::css('vendor/laravel-admin-ext/stickies/jquery.minicolorss.css');
                Admin::css('vendor/laravel-admin-ext/stickies/jquery.postitall.css');


//                var_dump(Admin::$baseCss);die;
                array_push(Admin::$baseCss, "vendor/laravel-admin-ext/stickies/stickies.css");
                Admin::baseCss(Admin::$baseCss);
            });


        });
    }
}