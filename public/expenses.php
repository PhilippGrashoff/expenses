<?php declare(strict_types=1);

use Expenses\Ui\App;
use Expenses\Ui\ExpensesPage;

include dirname(__DIR__) . '/vendor/autoload.php';

$app = new App();
ExpensesPage::addTo($app);
$app->run();