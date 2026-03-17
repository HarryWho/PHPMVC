<?php

defined("ROOTPATH") or exit("Access Denied!");

spl_autoload_register(function($classname){
    require_once $filename = '../app/models/' . ucfirst($classname) .".php";
});
require_once 'config.php';
require_once 'functions.php';
require_once 'DatabaseException.php';
require_once 'QueryCache.php';
require_once 'QueryProfiler.php';
require_once 'Validator.php';
require_once 'Flash.php';
require_once 'Auth.php';
require_once 'NavbarLoader.php';
require_once 'Database.php';
require_once 'Model.php';
require_once 'Controller.php';
require_once 'App.php';