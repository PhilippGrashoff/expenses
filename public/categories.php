<?php declare(strict_types=1);

use Expenses\Ui\CategoryPage;

$app = new \Expenses\Ui\App();
CategoryPage::addTo($app);
$app->run();