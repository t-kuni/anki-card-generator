<?php

if (! function_exists('app')) {
    function app() : Psr\Container\ContainerInterface
    {
        global $app;
        return $app;
    }
}