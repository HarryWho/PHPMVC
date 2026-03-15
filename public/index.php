<?php
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once '../app/core/init.php';

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if (phpversion() < MIN_VERSION) {
    die("Your PHP Version must be {$minPHPVersion} or higher to run this App. Your current version is ". phpversion());
}

session_start();
// DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new App;
$app->loadController();
 
