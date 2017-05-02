<?php
namespace Dingtalk\Utils;

use Httpful\Request;

class Http extends Request
{

    public static function get($path, $params)
    {
        $url = self::joinParams($path, $params);
        $response = Request::get($url)->send();
        return $response->body;
    }

    public static function post($path, $params, $data)
    {
        $url = self::joinParams($path, $params);
        $response = Request::post($url)
            ->body($data)
            ->sendsJson()
            ->send();
        return $response->body;
    }

    private static function joinParams($path, $params)
    {
        $path = preg_match('/^http/',$path) ? $path : 'https://oapi.dingtalk.com'.$path;
        return $path . '?' . http_build_query($params);
    }

}

