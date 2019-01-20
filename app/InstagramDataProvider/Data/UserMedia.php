<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:16 PM
 */

namespace app\InstagramDataProvider\Data;

class UserMedia
{
    public $mediaUrl;
    /**
     * @var PostImage[]
     */
    public $imageVersions;
    public $likesAmount;
    public $commentsAmount;

    public function __construct()
    {
        $this->imageVersions = [];
    }
}