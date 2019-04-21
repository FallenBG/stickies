Stickies is a laravel-admin extension that will allow you to create sticky notes inside your admin-panel.
======

This extension combines [laravel-admin](https://github.com/z-song/laravel-admin) and [Post It All!](https://github.com/txusko/PostItAll)

The main features are that the notes are not only localstorage saved but put into DB and loaded on refresh into the storage so nothing can be lost by mistake.
This can be used during development for throwing notes and links for JIRA tickets for specific pages and elements for example.

The main idea behind this package is to begin learning laravel and packages so it will be far from perfect - I'm open for suggestions.  

- Each page have it's own Stickies.
- Delete all will delete the Stickies for the current page.
- Create news Sticky with the button at the top right corner.

![alt text](https://i.imgur.com/apGY0hA.png)

## Requirements

laravel-admin >= 1.6.0
PHP >= 7.1.3

## Installation

```
$ composer require fallenbg/stickies

$ php artisan vendor:publish --provider=Encore\Stickies\StickiesServiceProvider --force

$ php artisan migrate --path=\vendor\fallenbg\stickies\database\migrations
```

Add the Following code inside \app\Admin\Bootstrap.php to see the create new sticky icon
```
Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
    $navbar->right('<a class="btn btn-app btn-small" onclick="createSticky(window.location.pathname)"><i class="fa fa-sticky-note"></i></a>');
});
```

License
------------
Licensed under [The MIT License (MIT)](LICENSE).