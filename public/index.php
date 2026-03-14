<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$minPHPVersion = '8.0';
if(phpversion()< $minPHPVersion){
    die("Your PHP Version must be {$minPHPVersion} or higher to run this App. Your current version is ". phpversion());
}

define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once '../app/core/init.php';
// DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new App;
$app->loadController();
 
