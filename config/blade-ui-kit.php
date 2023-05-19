<?php

use BladeUIKit\Components\Alerts\Alert;
use BladeUIKit\Components\Support\Avatar;
use BladeUIKit\Components\DateTime\Carbon;
use BladeUIKit\Components\Forms\Inputs\Checkbox;
use BladeUIKit\Components\Forms\Inputs\ColorPicker;
use BladeUIKit\Components\DateTime\Countdown;
use BladeUIKit\Components\Support\Cron;
use BladeUIKit\Components\Navigation\Dropdown;
use BladeUIKit\Components\Editors\EasyMDE;
use BladeUIKit\Components\Forms\Inputs\Email;
use BladeUIKit\Components\Forms\Error;
use BladeUIKit\Components\Forms\Form;
use BladeUIKit\Components\Buttons\FormButton;
use BladeUIKit\Components\Layouts\Html;
use BladeUIKit\Components\Forms\Inputs\Input;
use BladeUIKit\Components\Forms\Label;
use BladeUIKit\Components\Buttons\Logout;
use BladeUIKit\Components\Maps\Mapbox;
use BladeUIKit\Components\Markdown\Markdown;
use BladeUIKit\Components\Forms\Inputs\Password;
use BladeUIKit\Components\Forms\Inputs\Pikaday;
use BladeUIKit\Components\Layouts\SocialMeta;
use BladeUIKit\Components\Forms\Inputs\Textarea;
use BladeUIKit\Components\Markdown\ToC;
use BladeUIKit\Components\Editors\Trix;
use BladeUIKit\Components\Support\Unsplash;
use BladeUIKit\Components;

return [

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | Below you reference all components that should be loaded for your app.
    | By default all components from Blade UI Kit are loaded in. You can
    | disable or overwrite any component class or alias that you want.
    |
    */

    'components' => [
        'alert' => Alert::class,
        'avatar' => Avatar::class,
        'carbon' => Carbon::class,
        'checkbox' => Checkbox::class,
        'color-picker' => ColorPicker::class,
        'countdown' => Countdown::class,
        'cron' => Cron::class,
        'dropdown' => Dropdown::class,
        'easy-mde' => EasyMDE::class,
        'email' => Email::class,
        'error' => Error::class,
        'form' => Form::class,
        'form-button' => FormButton::class,
        'html' => Html::class,
        'input' => Input::class,
        'label' => Label::class,
        'logout' => Logout::class,
        'mapbox' => Mapbox::class,
        'markdown' => Markdown::class,
        'password' => Password::class,
        'pikaday' => Pikaday::class,
        'social-meta' => SocialMeta::class,
        'textarea' => Textarea::class,
        'toc' => ToC::class,
        'trix' => Trix::class,
        'unsplash' => Unsplash::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire Components
    |--------------------------------------------------------------------------
    |
    | Below you reference all the Livewire components that should be loaded
    | for your app. By default all components from Blade UI Kit are loaded in.
    |
    */

    'livewire' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Components Prefix
    |--------------------------------------------------------------------------
    |
    | This value will set a prefix for all Blade UI Kit components.
    | By default it's empty. This is useful if you want to avoid
    | collision with components from other libraries.
    |
    | If set with "buk", for example, you can reference components like:
    |
    | <x-buk-easy-mde />
    |
    */

    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Third Party Asset Libraries
    |--------------------------------------------------------------------------
    |
    | These settings hold reference to all third party libraries and their
    | asset files served through a CDN. Individual components can require
    | these asset files through their static `$assets` property.
    |
    */

    'assets' => [

        'alpine' => 'https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.3.5/dist/alpine.min.js',

        'easy-mde' => [
            'https://unpkg.com/easymde/dist/easymde.min.css',
            'https://unpkg.com/easymde/dist/easymde.min.js',
        ],

        'mapbox' => [
            'https://api.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.css',
            'https://api.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.js',
        ],

        'moment' => [
            'https://cdn.jsdelivr.net/npm/moment@2.26.0/moment.min.js',
            'https://cdn.jsdelivr.net/npm/moment-timezone@0.5.31/builds/moment-timezone-with-data.min.js',
        ],

        'pickr' => [
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css',
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js',
        ],

        'pikaday' => [
            'https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css',
            'https://cdn.jsdelivr.net/npm/pikaday/pikaday.js',
        ],

        'trix' => [
            'https://unpkg.com/trix@1.2.3/dist/trix.css',
            'https://unpkg.com/trix@1.2.3/dist/trix.js',
        ],

    ],

];
