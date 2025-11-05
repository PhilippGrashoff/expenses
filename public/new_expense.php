<?php declare(strict_types=1);

use Expenses\Ui\NewExpenseForm;

$app = new \Expenses\Ui\App();
NewExpenseForm::addTo($app);
$app->run();