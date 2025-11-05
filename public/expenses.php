<?php declare(strict_types=1);

use Expenses\Ui\ExpensesPage;

$app = new \Expenses\Ui\App();
ExpensesPage::addTo($app);
$app->run();