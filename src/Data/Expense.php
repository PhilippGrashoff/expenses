<?php declare(strict_types=1);

namespace Expenses\Data;

use Atk4\Data\Model;
use Atk4\Ui\Form\Control\Dropdown;

class Expense extends Model
{
    public $table = 'expense';
    public $caption = 'Ausgabe';

    protected function init(): void
    {
        parent::init();

        $this->addField(
            'date',
            [
                'type' => 'date',
                'caption' => 'Datum'
            ]
        );

        $this->addField(
            'amount',
            [
                'type' => 'atk4_money',
                'caption' => 'Betrag'
            ]
        );

        $this->addField(
            'description',
            [
                'type' => 'string',
                'caption' => 'Beschreibung (optional)'
            ]
        );

        $this->hasOne(
            'category_id',
            [
                'model' => [Category::class],
                'caption' => 'Kategorie',
                'ui' => [
                    'form' => [Dropdown::class, 'placeholder' => 'Kategorie wählen']
                ]
            ]
        );

        $this->hasOne(
            'user_id',
            [
                'model' => [User::class],
                'caption' => 'von Benutzer',
                'ui' => [
                    'form' => [Dropdown::class, 'placeholder' => 'Benutzer wählen']
                ]
            ]
        );
    }
}