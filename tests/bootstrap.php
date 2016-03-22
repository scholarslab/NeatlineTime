<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

if (!($omekaDir = getenv('OMEKA_DIR'))) {
    $omekaDir = dirname(dirname(dirname(dirname(__FILE__))));
}

require_once $omekaDir . '/application/tests/bootstrap.php';
echo $omekaDir;
require_once $omekaDir . '/plugins/NeatlineTime/tests/NeatlineTime_Test_AppTestCase.php';
echo 'got here';
