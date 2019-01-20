<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:12 PM
 */

namespace app\InstagramDataProvider;

class InstagramDataProvider
{
    /**
     * @var ClientInterface
     */
    private $client;
    public function connect(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getRecentPosts(string $pageLink, int $amount = 6)
    {
        return $this->client->getRecentPosts(UrlParser::parse($pageLink), $amount);
    }
}