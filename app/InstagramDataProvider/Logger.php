<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:14 PM
 */

namespace app\InstagramDataProvider;

class Logger
{
    public static function log(string $message)
    {
        echo $message."\n";
    }
}