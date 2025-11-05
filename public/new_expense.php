<?php declare(strict_types=1);

use Expenses\Ui\App;
use Expenses\Ui\NewExpenseForm;

include dirname(__DIR__) . '/vendor/autoload.php';

$app = new App();
NewExpenseForm::addTo($app);
$app->run();