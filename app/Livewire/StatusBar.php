<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Components\Component as FilamentComponent;

class StatusBar extends Component
{
    public $record;

    public function render()
    {
        $color = $this->record && $this->record->color ? $this->record->color->hex_code : '#000000';

        return view('livewire.status-bar', [
            'message' => 'Hello',
            'color' => $color,
        ]);
    }
}