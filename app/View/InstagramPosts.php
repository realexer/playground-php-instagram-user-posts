<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 6:37 PM
 */

namespace app\View;

use app\InstagramDataProvider\Clients\HTMLClient\Client as HTMLClient;
use app\InstagramDataProvider\Data\UserMedia;
use app\InstagramDataProvider\InstagramDataProvider;
use app\InstagramDataProvider\Clients\mgp25\Client as MGP25Client;

class InstagramPosts
{
    public function load()
    {
        $username = isset($_POST['username']) ? trim($_POST['username']) : null;
        $amount = isset($_POST['amount']) ? trim($_POST['amount']) : 9;

        $this->showForm($username, $amount);

        if(!empty($username))
        {
            try
            {
                $datProvider = new InstagramDataProvider();
                $datProvider->connect(new HTMLClient());
                $posts = $datProvider->getRecentPosts($username, $amount);

                $this->showPosts($username, $posts);
            }
            catch (\Throwable $ex)
            {
                echo $ex->getMessage();
            }
        }
    }

    private function showForm($username, $amount)
    {
        include 'app/html/view_form.php';
    }

    /**
     * @param UserMedia[] $posts
     */
    private function showPosts(string $userName, array $posts)
    {
        include 'app/html/view_posts.php';
    }
}