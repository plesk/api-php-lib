<?php

set_include_path(get_include_path() . ':../src');

function autoload($className)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    include($path . '.php');
}

spl_autoload_register('autoload');
