<?php declare(strict_types=1);

namespace Expenses\Ui;

use Atk4\Ui\Crud;
use Atk4\Ui\View;
use Expenses\Data\Category;
use Expenses\Data\Core;

class CategoryPage extends View
{

    protected Crud $crud;

    protected function init(): void
    {
        parent::init();
        $this->crud = Crud::addTo($this);
        $this->crud->setModel(new Category(Core::get()->getPersistence()));
    }
}