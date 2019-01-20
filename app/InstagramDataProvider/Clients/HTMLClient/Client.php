<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/20/2019
 * Time: 3:16 PM
 */

namespace app\InstagramDataProvider\Clients\HTMLClient;

use app\InstagramDataProvider\ClientInterface;

class Client implements ClientInterface
{
    private $config = [
        "url" => "https://instagram.com/"
    ];
    /**
     * @param string $username
     * @param int $amount
     * @return InstagramUserPost[]
     */
    public function getRecentPosts(string $username, int $amount) : array
    {
        $posts = [];

        $pageContent = $this->loadHTML($this->config['url'].$username);

        $htmlDoc = $this->getDocument($pageContent);

        throw new Exception("Not implemented.");
        //$posts = $this->queryDom($htmlDoc, "");

        return $posts;
    }

    private function getDocument(string $content, $query)
    {
        $doc = new DOMDocument;
        $doc->preserveWhiteSpace = false;
        $doc->loadHTML($content);

        return $doc;
    }

    private function queryDom($doc, $query)
    {
        $xpath = new DOMXPath($doc);

        return $xpath->query($query);
    }

    private function loadHTML($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}