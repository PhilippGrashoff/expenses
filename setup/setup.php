<?php declare(strict_types=1);

use Expenses\Setup\DbCreator;

include dirname(__DIR__) . '/vendor/autoload.php';
include dirname(__DIR__) . '/config_local.php';

$dbCreator = new DbCreator(
    DB_STRING,
    DB_USER,
    DB_PASSWORD,
    true
);
$dbCreator->recreateDb();