<?php

if (! function_exists('app')) {
    function app($abstruct=null) : Psr\Container\ContainerInterface
    {
        global $app;
        return $app;
    }
}