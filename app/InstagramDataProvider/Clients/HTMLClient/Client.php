<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:16 PM
 */

namespace app\InstagramDataProvider\Clients\HTMLClient;

use app\InstagramDataProvider\ClientInterface;
use app\InstagramDataProvider\Clients\HTMLClient\http\CurlClient;
use app\InstagramDataProvider\Clients\HTMLClient\http\Request;
use app\InstagramDataProvider\Clients\HTMLClient\parsers\PostsRequestDataParser;
use app\InstagramDataProvider\Data\PostImage;
use app\InstagramDataProvider\Data\UserMedia;

class Client implements ClientInterface
{
    private $config = [
        "base_url" => "https://www.instagram.com/",
        "query_id" => "17888483320059182"
    ];

    /**
     * @param string $username
     * @param int $amount
     * @return InstagramUserPost[]
     * @throws \Exception
     */
    public function getRecentPosts(string $username, int $amount) : array
    {
        $httpClient = new CurlClient();
        $postsRequestData = $this->retrievePostsRequestData($username);

        $postsRequestData->completenessCheck();

        $data["query_id"] = $this->config['query_id'];
        $data['variables'] = json_encode([
            'id' => $postsRequestData->userId,
            'first' => $amount,
            //'after' => $postsRequestData->endCursor
        ]);

        $postsRequest = new Request();
        $postsRequest->url = $this->config['base_url']."graphql/query/?".http_build_query($data);
        $postsRequest->headers = [
            'x-instagram-gis: '.md5($postsRequestData->rhx_gis.":".$data['variables'])
        ];

        $postsResponse = $httpClient->request($postsRequest);

        return $this->retrievePostsFromResponse(json_decode($postsResponse->body, true));
    }

    /**
     * @param $username
     * @return \app\InstagramDataProvider\Clients\parsers\PostsRequestData
     * @throws UserDataNotFoundException
     */
    private function retrievePostsRequestData($username)
    {
        $httpClient = new CurlClient();
        $request = new Request();
        $request->url = $this->config['base_url'].$username;
        $response = $httpClient->request($request);

        if($response->code == 200)
        {
            return PostsRequestDataParser::getPostsRequestData($response->body);
        } else {
            throw new UserDataNotFoundException("User data not found.");
        }
    }

    private function retrievePostsFromResponse(array $data)
    {
        $posts = [];
        // TODO: add safe array lookups
        if($data['status'] == 'ok') {
            $userData = $data['data']['user']['edge_owner_to_timeline_media'];

            foreach($userData['edges'] as $edge)
            {
                $post = new UserMedia();
                $nodeData = $edge['node'];
                $type = $nodeData["__typename"];
                $post->likesAmount = $nodeData["edge_media_preview_like"]["count"];
                $post->commentsAmount = $nodeData["edge_media_to_comment"]["count"];

                $image = new PostImage();
                $image->url = $nodeData["display_url"];
                $image->height = $nodeData["dimensions"]["height"];
                $image->width = $nodeData["dimensions"]["width"];

                $post->imageVersions[] = $image;

                $posts[] = $post;
            }
        }

        return $posts;
    }
}

class UserDataNotFoundException extends \Exception
{

}