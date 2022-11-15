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
