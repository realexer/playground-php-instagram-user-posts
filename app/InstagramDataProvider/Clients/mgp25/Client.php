<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:16 PM
 */

namespace app\InstagramDataProvider\Clients\mgp25;

use app\InstagramDataProvider\ClientInterface;
use app\InstagramDataProvider\Data\PostImage;
use app\InstagramDataProvider\Data\UserMedia;

class Client implements ClientInterface
{
    private $config = [
        "username" => "why_ig_sux",
        "password" => "\$ht00ng",
        "debug" => false, //true,
        "truncatedDebug" => false,
        "requests_frequency_seconds" => 5

    ];

    /**
     * @param string $username
     * @param int $amount
     * @return InstagramUserPost[]
     */
    public function getRecentPosts(string $username, int $amount) : array
    {
        $postsList = [];

        $ig = $this->connect();

        try {
            // Get the UserPK ID for "natgeo" (National Geographic).
            $userId = $ig->people->getUserIdForName($username);
            // Starting at "null" means starting at the first page.
            $maxId = null;
            do {
                // Request the page corresponding to maxId.
                $response = $ig->timeline->getUserFeed($userId, $maxId);
                // In this example we're simply printing the IDs of this page's items.
                foreach ($response->getItems() as $item)
                {
                    $post = new UserMedia();
                    $post->mediaUrl = $item->getLink();
                    $imageVersions = $item->getImageVersions2();
                    if(!$imageVersions)
                    {
                        if($item->isCarouselMedia())
                        {
                            foreach($item->getCarouselMedia() as $carouselMedia)
                            {
                                $imageVersions = $carouselMedia->getImageVersions2();
                                break;
                            }
                        }
                    }

                    if($imageVersions) {
                        foreach ($imageVersions->getCandidates() as $candidate) {
                            $postImage = new PostImage();
                            $postImage->url = $candidate->getUrl();
                            $postImage->width = $candidate->getWidth();
                            $postImage->height = $candidate->getHeight();
                            $post->imageVersions[] = $postImage;
                        }
                    }


                    $post->likesAmount = $item->getLikeCount();
                    $post->commentsAmount = $item->getCommentCount();

                    $postsList[] = $post;
                    if(count($postsList) == $amount) {
                        break 2;
                    }
                    //printf("[%s] https://instagram.com/p/%s/\n", $item->getId(), $item->getCode());
                }
                // Now we must update the maxId variable to the "next page".
                // This will be a null value again when we've reached the last page!
                // And we will stop looping through pages as soon as maxId becomes null.
                $maxId = $response->getNextMaxId();
                // Sleep for 5 seconds before requesting the next page. This is just an
                // example of an okay sleep time. It is very important that your scripts
                // always pause between requests that may run very rapidly, otherwise
                // Instagram will throttle you temporarily for abusing their API!
//                echo "Sleeping for ".$this->config['requests_frequency_seconds']."s...\n";
                sleep($this->config['requests_frequency_seconds']);
            } while ($maxId !== null); // Must use "!==" for comparison instead of "!=".
        } catch (\Exception $e) {
            throw $e;
        }

        return $postsList;
    }

    /**
     * @return \InstagramAPI\Instagram
     * @throws \Exception
     */
    private function connect()
    {
        \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

        $ig = new \InstagramAPI\Instagram($this->config['debug'], $this->config['truncatedDebug']);
        try {
            $ig->login($this->config['username'], $this->config['password']);
        } catch (\Exception $e) {
            throw $e;
        }

        return $ig;

    }
}