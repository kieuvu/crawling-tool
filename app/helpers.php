<?php

use App\Libs\Console\BeautyEcho;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

if (!function_exists("pinfo")) {
    function pinfo($label, $message = "")
    {
        logger()->info($label, [$message]);
        BeautyEcho::Info($label, $message);
    }
}
if (!function_exists("pdash")) {
    function pdash()
    {
        BeautyEcho::Dash(intval(exec('tput cols')));
    }
}
if (!function_exists("extractUrlQueries")) {
    function extractUrlQueries($url,  $callback = null)
    {
        [$urlString, $queryString] = array_pad(explode('?', $url), 2, '');
        parse_str($queryString, $queriesArray);

        try {
            $newQueriesArray = is_callable($callback) ? $callback($queriesArray) : $queriesArray;
        } catch (\Throwable $ex) {
            $newQueriesArray = $queriesArray;
        }

        $newQueryString = http_build_query($newQueriesArray);
        if (strlen($newQueryString) == 0)
            return $urlString;
        return $urlString . '?' . $newQueryString;
    }
}
if (!function_exists("withoutUrlQueries")) {
    function withoutUrlQueries($url, $blackLists = [])
    {
        return extractUrlQueries($url, function (&$queriesArray) use ($blackLists) {
            array_walk($blackLists, function ($v) use (&$queriesArray) {
                unset($queriesArray[$v]);
            });
            return $queriesArray;
        });
    }
}
if (!function_exists("onlyUrlQueries")) {
    function onlyUrlQueries($url, $whiteLists = [])
    {
        return extractUrlQueries($url, function (&$queriesArray) use ($whiteLists) {
            return array_intersect_key($queriesArray, array_flip($whiteLists));
        });
    }
}

if (!function_exists("replaceParam")) {
    function replaceParam($url, $newParam)
    {
        return extractUrlQueries($url, function (&$queriesArray) use ($newParam) {
            return array_replace($queriesArray, $newParam);
        });
    }
}

if (!function_exists("randomUserAgent")) {
    function randomUserAgent()
    {
        $file = File::get("database/data/useragents.json");
        $data = json_decode($file, true);
        return Arr::random($data);
    }
}
