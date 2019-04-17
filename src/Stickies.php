<?php

namespace Encore\Stickies;

use Encore\Admin\Extension;

class Stickies extends Extension
{
    public $name = 'stickies';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

//    public $menu = [
//        'title' => 'Stickies',
//        'path'  => 'stickies',
//        'icon'  => 'fa-sticky-note',
//    ];

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Stickies', 'stickies', 'fa-sticky-note');

        parent::createPermission('Admin Stickies', 'ext.sickies', 'stickies*');
    }
}