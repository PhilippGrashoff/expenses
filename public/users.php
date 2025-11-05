<?php declare(strict_types=1);

use Expenses\Ui\UserPage;

$app = new \Expenses\Ui\App();
UserPage::addTo($app);
$app->run();