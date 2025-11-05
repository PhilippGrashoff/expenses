<?php declare(strict_types=1);

namespace Expenses\Ui;

use Atk4\Ui\Layout\Centered;
use Expenses\Data\Core;

class App extends \Atk4\Ui\App
{

    public function __construct(array $defaults = [])
    {
        $this->title = 'Ausgabenerfassung EmPhiDeYa';
        $this->setCdns();
        parent::__construct($defaults);
        $this->initLayout(new Centered(['image' => false]));
    }

    protected function setCdns(): void
    {
        $this->cdn['atk'] = Core::getBaseUrl() . 'public_atk_ui';
        $this->cdn['jquery'] = Core::getBaseUrl() . 'public_atk_ui/external/jquery/dist';
        $this->cdn['fomantic-ui'] = Core::getBaseUrl() . 'public_atk_ui/external/fomantic-ui/dist';
        $this->cdn['flatpickr'] = Core::getBaseUrl() . 'public_atk_ui/external/flatpickr/dist';
        $this->cdn['highlight.js'] = Core::getBaseUrl() . 'public_atk_ui/external/@highlightjs/cdn-assets';
        $this->cdn['chart.js'] = Core::getBaseUrl() . 'public_atk_ui/external/chart.js/dist';
    }
}