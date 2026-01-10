<?php

namespace App\Services;

class SeoService
{
    protected static $defaults = [
        'title' => 'Portfolio - Web Developer',
        'description' => 'Portofolio profesional web developer dengan spesialisasi Laravel dan JavaScript.',
        'keywords' => 'web developer, laravel, php, javascript, portfolio',
        'image' => null,
        'url' => null,
    ];

    protected static $metadata = [];

    public static function set($key, $value)
    {
        static::$metadata[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return static::$metadata[$key] ?? static::$defaults[$key] ?? $default;
    }

    public static function getTitle()
    {
        return static::get('title');
    }

    public static function getDescription()
    {
        return static::get('description');
    }

    public static function getKeywords()
    {
        return static::get('keywords');
    }

    public static function getImage()
    {
        return static::get('image', config('app.url') . '/logo.png');
    }

    public static function getUrl()
    {
        return static::get('url', config('app.url'));
    }

    public static function reset()
    {
        static::$metadata = [];
    }
}
