<?php declare(strict_types=1);

namespace Expenses\Ui;

use Atk4\Ui\Layout\Centered;

class App extends \Atk4\Ui\App
{

    public function __construct(array $defaults = [])
    {
        $this->title = 'Ausgabenerfassung DePhiEmYa';
        parent::__construct($defaults);
        $this->initLayout(new Centered(['image' => false]));
    }
}