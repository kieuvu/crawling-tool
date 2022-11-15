<?php

namespace App\Libs\Console;

class BeautyEcho
{
    public static $terminalWidth = 1;
    public static $dash = "";

    public static function info($label, $message)
    {
        echo "「\033[36m$label\033[0m」$message\n";
    }

    public static function dash($length = 50, $char = "_")
    {
        $terminalWidth =  $length;
        $dash = "";

        if (self::$terminalWidth == $terminalWidth) {
            echo self::$dash . "\n";
            return false;
        }

        self::$terminalWidth = $terminalWidth;

        for ($i = 1; $i <= $terminalWidth; $i++) {
            $dash = $dash . $char;
        }

        self::$dash = $dash;

        echo self::$dash . "\n";
    }
}
