<?php declare(strict_types=1);

namespace Expenses\Ui;

use Atk4\Ui\Crud;
use Atk4\Ui\View;
use Expenses\Data\Core;
use Expenses\Data\User;

class UserPage extends View
{

    protected function init(): void
    {
        parent::init();
        $crud = Crud::addTo($this);
        $crud->setModel(new User(Core::get()->getPersistence()), ['name', 'crypt_id']);
    }
}