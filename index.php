<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'load_things.php';
Session::init();


$app = new Router();
$app->execute_controller();
?>