<?php

namespace App\Libs\Console;

use App\Libs\String\ReduceString;

class EchoCli
{
    public static $terminalWidth = 1;
    public static $dash = "";

    public static function Info($label, $message)
    {
        echo "「\033[36m$label\033[0m」$message\n";
    }

    public static function Dash()
    {
        $terminalWidth = intval(exec('tput cols'));
        $dash = "";

        if (self::$terminalWidth == $terminalWidth) {
            echo self::$dash . "\n";
            return false;
        }

        self::$terminalWidth = $terminalWidth;

        for ($i = 1; $i <= $terminalWidth; $i++) {
            $dash = $dash . "_";
        }

        self::$dash = $dash;

        echo self::$dash . "\n";
    }
}
