<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 6:37 PM
 */

namespace app\View;

use app\InstagramDataProvider\Data\UserMedia;
use app\InstagramDataProvider\InstagramDataProvider;
use app\InstagramDataProvider\Clients\mgp25\Client as MGP25Client;

class InstagramPosts
{
    public function load($amount = 9)
    {
        $username = isset($_POST['username']) ? trim($_POST['username']) : null;

        if(!empty($username))
        {
            $datProvider = new InstagramDataProvider();
            $datProvider->connect(new MGP25Client());
            $posts = $datProvider->getRecentPosts($username, $amount);

            $this->showPosts($username, $posts);
        } else {
            $this->showForm();
        }
    }

    private function showForm()
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