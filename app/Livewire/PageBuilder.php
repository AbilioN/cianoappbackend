<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class PageBuilder extends Component
{
    public $details = [];

    protected $listeners = [
        'updateDetails' => 'setDetails',
        'language-changed' => 'handleLanguageChange',
        'details-updated' => 'updateDetailsFromEvent'
    ];

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

    public function updateDetailsFromEvent($details)
    {
        Log::info('PageBuilder received updated details:', ['details' => $details]);
        $this->details = $details;
    }

    public function handleLanguageChange($language)
    {
        Log::info('PageBuilder handling language change:', ['language' => $language]);
        // Quando o idioma muda, pedimos ao componente pai para atualizar os detalhes
        $this->dispatch('update-details-requested', language: $language);
    }

    public function render()
    {
        return view('livewire.page-builder', [
            'details' => $this->details
        ]);
    }
}
