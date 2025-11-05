<?php declare(strict_types=1);

namespace Expenses\Ui;

use Atk4\Ui\Crud;
use Atk4\Ui\View;
use Expenses\Data\Category;
use Expenses\Data\Core;

class CategoryPage extends View
{

    protected function init(): void
    {
        parent::init();
        $crud = Crud::addTo($this);
        $crud->setModel(new Category(Core::get()->getPersistence()));
    }
}