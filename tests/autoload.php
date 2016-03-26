<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

set_include_path(get_include_path() . ':src');

function autoload($className)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    include($path . '.php');
}

spl_autoload_register('autoload');

if ($executionLogFile = getenv('EXECUTION_LOG')) {
    PleskX\Api\Client::enableExecutionLog();

    register_shutdown_function(function () use ($executionLogFile) {
        $executionLog = PleskX\Api\Client::getExecutionLog();
        file_put_contents($executionLogFile, json_encode($executionLog));
    });
}
