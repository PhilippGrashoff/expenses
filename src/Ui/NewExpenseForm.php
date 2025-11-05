<?php declare(strict_types=1);

namespace Expenses\Ui;

use Atk4\Ui\Form;
use Atk4\Ui\Js\JsBlock;
use Atk4\Ui\Js\JsReload;
use Atk4\Ui\Js\JsToast;
use Atk4\Ui\View;
use Expenses\Data\Core;
use Expenses\Data\Expense;
use Expenses\Data\User;

class NewExpenseForm extends View
{
    protected function init(): void
    {
        parent::init();
        $form = Form::addTo($this);
        $form->setEntity(new Expense(Core::get()->getPersistence()));
        if (isset($_GET['user'])) {
            $user = (new User(Core::get()->getPersistence()))->tryLoadBy('crypt_id', $_GET['user']);
            if ($user) {
                $form->entity->set('user_id', $user->getId());
                $this->stickyGet('user');
            }
        }
        $form->onSubmit(function (Form $form) {
            $form->entity->save();
            return new JsBlock([
                new JsToast('Ausgabe erfolgreich erstellt!'),
                new JsReload($this)
            ]);
        });
    }
}