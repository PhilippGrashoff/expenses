<?php declare(strict_types=1);

use Expenses\Ui\App;
use Expenses\Ui\CategoryPage;

include dirname(__DIR__) . '/vendor/autoload.php';
include dirname(__DIR__) . '/config_local.php';

$app = new App();
CategoryPage::addTo($app);
$app->run();