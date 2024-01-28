<?php

enum Color: string
{
    case black = "\033[0;30m";
    case dark_gray = "\033[1;30m";
    case blue = "\033[0;34m";
    case light_blue = "\033[1;34m";
    case green = "\033[0;32m";
    case light_green = "\033[1;32m";
    case cyan = "\033[0;36m";
    case light_cyan = "\033[1;36m";
    case red = "\033[0;31m";
}

function colorize($string, Color $color)
{
    global $colors;
    return "{$color->value}$string\033[0m";
}

function log_state($message, Color $color = Color::green)
{
    echo colorize($message, $color) . "\n";
}

function format_error($message)
{
    return colorize("\n\n$message\n\n", Color::red);
}
