<?php

namespace Almanapp\EventInsight\Bin;

use function define;
use function defined;
use function dirname;

$root = dirname(__DIR__);
require_once $root . '/vendor/autoload.php';
require_once $root . '/config.php';
// Set environment variables with data from config.
putenv('propel_hostname=' . MYSQL_HOSTNAME); // %env.propel_database
putenv('propel_database=' . MYSQL_DATABASE); // %env.propel_database
putenv('propel_user=' . MYSQL_USERNAME); // %env.propel_user
putenv('propel_password=' . MYSQL_PASSWORD); // %env.propel_password

//array_splice($_SERVER['argv'], 1, 1); // remove local from string

include $root . '/vendor/propel/propel/bin/propel.php';