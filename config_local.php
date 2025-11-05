<?php declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 'On');

define('EOO_DEVELOP_MODE',         2);

define('DB_STRING',                'mysql:host=mariadb-expenses;dbname=expenses');
define('DB_USER',                  'root');
define('DB_PASSWORD',              'root');

define('BASE_URL',                 'http://localhost:11001/public/');