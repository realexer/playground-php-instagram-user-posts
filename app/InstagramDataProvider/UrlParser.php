<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:12 PM
 */

namespace app\InstagramDataProvider;

class UrlParser
{
    private static $allowedUrlSchemes = [
        [
            "pattern" => "https?:\/\/(www.)?instagram.com\/([a-zA-Z0-9._-]+)\/?",
            "match" => 2
        ],
        [
            "pattern" => "^([a-zA-Z0-9._-]+)$",
            "match" => 1
        ]
    ];

    public static function parse(string $url)
    {
        foreach(self::$allowedUrlSchemes as $scheme)
        {
            if(preg_match("/{$scheme['pattern']}/", $url, $matches)) {
                return $matches[$scheme['match']];
            }
        }

        throw new Exception("User name '{$url}' is incorrect.");
    }
}