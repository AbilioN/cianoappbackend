<?php

namespace App\Livewire;

use Livewire\Component;

class PageBuilder extends Component
{
    public $details = [];

    protected $listeners = ['updateDetails' => 'setDetails'];

    // Permite receber detalhes como prop
    public function mount($details = [])
    {
        $this->details = $details;
    }

    // Permite atualizar os detalhes via método público
    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function render()
    {
        return view('livewire.page-builder', [
            'details' => $this->details
        ]);
    }
}
