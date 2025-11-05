<?php declare(strict_types=1);

namespace Expenses\Data;

use Atk4\Data\Model;

class Category extends Model
{
    public $table = 'category';
    public $caption = 'Kategorie';

    protected function init(): void
    {
        parent::init();
        $this->addField(
            'name',
            [
                'type' => 'string',
                'caption' => 'Name der Kategorie'
            ]
        );
        $this->addField(
            'description',
            [
                'type' => 'string',
                'caption' => 'Beschreibung (optional)'
            ]
        );

        $this->hasMany(
            Expense::class,
            [
                'model' => [Expense::class]
            ]
        );
    }
}