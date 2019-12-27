<?php


use BagOfIdeas\Helpers\Bootstrap;

require 'vendor/autoload.php';
require 'config.php';

define("ROOT", __DIR__ . '/');
define("STORAGE", __DIR__ . '/storage/');


$bootstrap = new Bootstrap();
$bootstrap->init();
$bootstrap->handleRouting();