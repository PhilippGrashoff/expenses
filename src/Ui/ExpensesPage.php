<?php declare(strict_types=1);

namespace Expenses\Ui;

use Atk4\Ui\Crud;
use Atk4\Ui\Table\Column;
use Atk4\Ui\Table\Column\KeyValue;
use Atk4\Ui\Table\Column\Money;
use Atk4\Ui\View;
use Expenses\Data\Core;
use Expenses\Data\Expense;

class ExpensesPage extends View
{

    protected array $fieldsForCrud = [
        'date' => [Column::class],
        'amount' => [Money::class],
        'description' => [Column::class],
        'category_id' => [KeyValue::class],
        'user_id' => [KeyValue::class],
    ];

    protected function init(): void
    {
        parent::init();
        $crud = Crud::addTo($this);
        $crud->setModel(new Expense(Core::get()->getPersistence()));
    }
}