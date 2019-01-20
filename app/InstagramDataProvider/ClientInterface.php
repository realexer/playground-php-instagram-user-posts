<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:13 PM
 */

namespace app\InstagramDataProvider;

use app\InstagramDataProvider\Data\UserMedia;

interface ClientInterface
{
    /**
     * @param string $username
     * @param int $amount
     * @return UserMedia[]
     */
    public function getRecentPosts(string $username, int $amount) : array;
}