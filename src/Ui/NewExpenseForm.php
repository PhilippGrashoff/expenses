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
    protected Form $form;

    protected ?User $user = null;

    protected function init(): void
    {
        parent::init();
        $this->getUserFromRequest();
        $this->addNewExpenseForm();
    }

    protected function addNewExpenseForm(): void
    {
        $this->form = Form::addTo($this);
        $expense = (new Expense(Core::get()->getPersistence()))->createEntity();
        $expense->set('date', new \DateTime());
        if ($this->user) {
            $expense->getField('user_id')->system = true;
            $expense->set('user_id', $this->user->getId());
        }
        $this->form->setEntity($expense);
        $this->form->buttonSave->set('Speichern');

        $this->form->onSubmit(function (Form $form) {
            $form->entity->save();
            return new JsBlock([
                new JsToast('Ausgabe erfolgreich erstellt!'),
                new JsReload($this)
            ]);
        });
    }

    protected function getUserFromRequest(): void
    {
        if (isset($_GET['user'])) {
            $user = (new User(Core::get()->getPersistence()))->tryLoadBy('crypt_id', $_GET['user']);
            if ($user) {
                $this->user = $user;
                $this->stickyGet('user');
            }
        }
    }
}