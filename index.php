<?php


use BagOfIdeas\Helpers\Bootstrap;

require 'vendor/autoload.php';
require 'config.php';

define("ROOT", __DIR__ . '/');


$bootstrap = new Bootstrap();
$bootstrap->init();
$bootstrap->handleRouting();