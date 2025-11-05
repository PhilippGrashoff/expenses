<?php declare(strict_types=1);

namespace Expenses\Data;

use Atk4\Data\Model;
use PhilippR\Atk4\ModelTraits\CryptIdTrait;

class User extends Model
{
    use CryptIdTrait;

    public $table = 'user';
    public $caption = 'Benutzer';

    protected function init(): void
    {
        parent::init();

        $this->addField(
            'name',
            [
                'type' => 'string',
                'caption' => 'Datum'
            ]
        );

        $this->addCryptIdFieldAndHooks('crypt_id');

        $this->hasMany(Expense::class, ['model' => [Expense::class]]);
    }

    public function generateCryptId(): string
    {
        $return = '';
        for ($i = 0; $i < 15; $i++) {
            $return .= $this->getRandomChar();
        }
        return $return;
    }
}