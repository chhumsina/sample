<?php
# These two lines should be changed...
//require __DIR__.'/bootstrap/autoload.php';
//$app = require_once __DIR__.'/bootstrap/start.php';
//
# ... into these two lines.
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/start.php';
$app->run();
?>