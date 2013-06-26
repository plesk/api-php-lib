<?php

set_include_path(get_include_path() . ':../src');

function autoload($className)
{
    $path = str_replace('_', '/', $className);
    include($path . '.php');
}

spl_autoload_register('autoload');

class AllTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new self();

        $suite->addTestSuite('ServerTest');

        return $suite;
    }
}
