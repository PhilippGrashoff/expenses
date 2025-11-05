<?php declare(strict_types=1);

use Expenses\Ui\App;
use Expenses\Ui\UserPage;

include dirname(__DIR__) . '/vendor/autoload.php';

$app = new App();
UserPage::addTo($app);
$app->run();