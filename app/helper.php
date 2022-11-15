<?php

use App\Libs\Console\BeautyEcho;

function pinfo($label, $message)
{
    logger()->info($label, [$message]);
    BeautyEcho::Info($label, $message);
}

function pdash()
{
    BeautyEcho::Dash(intval(exec('tput cols')));
}

function extractUrlQueries($url,  $callback = null)
{
    [$urlString, $queryString] = array_pad(explode('?', $url), 2, '');
    parse_str($queryString, $queriesArray);

    try {
        $newQueriesArray = is_callable($callback) ? $callback($queriesArray) : $queriesArray;
    } catch (\Throwable) {
        $newQueriesArray = $queriesArray;
    }

    $newQueryString = http_build_query($newQueriesArray);
    if (strlen($newQueryString) == 0)
        return $urlString;
    return $urlString . '?' . $newQueryString;
}

function withoutUrlQueries($url, $blackLists = [])
{
    return extractUrlQueries($url, function (&$queriesArray) use ($blackLists) {
        array_walk($blackLists, function ($v) use (&$queriesArray) {
            unset($queriesArray[$v]);
        });
        return $queriesArray;
    });
}

function onlyUrlQueries($url, $whiteLists = [])
{
    return extractUrlQueries($url, function (&$queriesArray) use ($whiteLists) {
        return array_intersect_key($queriesArray, array_flip($whiteLists));
    });
}
