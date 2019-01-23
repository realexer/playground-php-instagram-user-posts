<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/23/2019
 * Time: 4:43 PM
 */

namespace app\InstagramDataProvider\Clients\HTMLClient\parsers;

class PostsRequestDataParser
{
    public static function getPostsRequestData(string $documentBody)
    {
        $requestData = new PostsRequestData();

        $documentBody = preg_replace("|(<svg .*</svg>)|", '', $documentBody);

        $htmlDoc = new \DOMDocument();
        $htmlDoc->loadHTML($documentBody);
        $htmlDoc->preserveWhiteSpace = FALSE;

        $scripts = $htmlDoc->getElementsByTagName("script");

        foreach($scripts as $script)
        {
            if($script->nodeValue)
            {
                if (preg_match("/^window._sharedData = .+$/", $script->nodeValue))
                {
                    if(preg_match('/edge_owner_to_timeline_media.+"has_next_page":(true|false)/', $script->nodeValue, $hasNextPage)) {
                        $requestData->hasNextPage = boolval($hasNextPage[1]);
                    }
                    if(preg_match('/edge_owner_to_timeline_media.+"end_cursor":"([^\"]+)/', $script->nodeValue, $endCursor)) {
                        $requestData->endCursor = $endCursor[1];
                    }
                    if(preg_match('/profilePage_([\d]+)/', $script->nodeValue, $userId)) {
                        $requestData->userId = $userId[1];
                    }
                    if(preg_match('/"rhx_gis":"([\w0-9]+)"/', $script->nodeValue, $rhx_gis)) {
                        $requestData->rhx_gis = $rhx_gis[1];
                    }

                }
            }
        }

        return $requestData;
    }
}

class PostsRequestData
{
    public $userId;
    public $hasNextPage;
    public $endCursor;
    public $rhx_gis;

    public function completenessCheck()
    {
        $errors = [];

        if(empty($this->userId)) {
            $errors[] = "'userId' was not set.";
        }

        if(empty($this->hasNextPage)) {
            $errors[] = "'hasNextPage' was not set.";
        }

        if(empty($this->endCursor)) {
            $errors[] = "'endCursor' was not set.";
        }

        if(empty($this->rhx_gis)) {
            $errors[] = "'rhs_gis' was not set.";
        }

        if(!empty($errors)) {
            throw new PostsRequestDataIncompleteException(implode("; ", $errors));
        }
    }
}

class PostsRequestDataIncompleteException extends \Exception
{

}