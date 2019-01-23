<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 1/23/2019
 * Time: 11:27 AM
 */

namespace app\InstagramDataProvider\Clients\HTMLClient\http;

class CurlClient
{
    public function request(Request $request)
    {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $request->url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_POSTREDIR => 3,
//            CURLOPT_COOKIEJAR => realpath("./")."/cookies.txt",
//            CURLOPT_COOKIEFILE => realpath("./")."/cookies.txt",
            CURLOPT_VERBOSE => TRUE,
            CURLINFO_HEADER_OUT => TRUE,
            CURLOPT_HTTPHEADER => $request->headers,
//            [
//                'accept: */*',
//                //"referer: $reffer",
//                //'accept-language: en-US,en;q=0.9',
//                //'pragma: no-cache',
//                //'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
//                'x-instagram-gis: '.$gis,
//                //'x-requested-with: XMLHttpRequest'
//            ]
        ));

        try
        {
            $responseString = curl_exec($ch);
            if ($responseString === FALSE)
            {
                $ex = new ResponseException("cURL: Failed to load data.");
                $ex->curlErrNo = curl_errno($ch);
                $ex->curlError = curl_error($ch);

                throw $ex;
            }

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

            $response = new Response();
            $response->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $response->headers = substr($responseString, 0, $header_size);
            $response->body = substr($responseString, $header_size);

            return $response;
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
        finally
        {
//            $info = curl_getinfo($ch);
//            print_r($info['request_header']);
            curl_close($ch);
        }
    }
}

class Request
{
    public $url;
    public $headers = [];
}

class Response
{
    public $code;
    public $headers;
    public $body;
}

class ResponseException extends \Exception
{
    public $curlErrNo;
    public $curlError;
}