<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class PageBuilder extends Component
{
    public $details = [];
    public $languages = [];
    public $details_count = 0;
    public $selectedLanguage = 'en';

    protected $listeners = [
        'detailUpdated' => 'handleDetailUpdate',
        'typeChanged' => 'handleTypeChange',
        'language-changed' => 'handleLanguageChange',
        'details-updated' => 'updateDetailsFromEvent',
        'page-builder-update' => 'handleDetailUpdate'
    ];

    // Permite receber detalhes como prop
    public function mount($details = [])
    {
        Log::info('PageBuilder: mount chamado', [
            'details_count' => $this->details_count,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        $this->details = $details;
    }

    // Permite atualizar os detalhes via método público
    public function setDetails($details)
    {
        Log::info('PageBuilder: setDetails chamado', [
            'details_count' => count($details),
            'timestamp' => now()->toDateTimeString()
        ]);
        $this->details = $details;
    }

    public function updateDetailsFromEvent($details)
    {
        Log::info('PageBuilder: updateDetailsFromEvent chamado', [
            'details_count' => count($details),
            'timestamp' => now()->toDateTimeString()
        ]);
        $this->details = $details;
    }

    public function handleDetailUpdate($data)
    {
        Log::info('PageBuilder: handleDetailUpdate chamado', [
            'data' => $data,
            'current_details_count' => count($this->details),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        $index = $data['index'];
        $detail = $data['detail'];

        if (!isset($this->details[$index])) {
            Log::warning('PageBuilder: tentativa de atualizar detalhe inexistente', [
                'index' => $index,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            return;
        }

        // Atualiza o detalhe com os novos valores
        $this->details[$index] = $detail;

        // Log para debug
        Log::info('PageBuilder: detalhe atualizado', [
            'index' => $index,
            'detail' => $this->details[$index],
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function handleTypeChange($data)
    {
        Log::info('Type changed to: ' . $data['type']);
        
        $index = $data['index'];
        $type = $data['type'];

        if (!isset($this->details[$index])) {
            return;
        }

        $this->details[$index]['type'] = $type;
        
        // Limpa os campos específicos do tipo anterior
        $this->details[$index]['value'] = '';
        $this->details[$index]['text'] = '';
        $this->details[$index]['url'] = '';
        $this->details[$index]['items'] = [];
    }

    public function handleLanguageChange($language)
    {
        Log::info('PageBuilder: handleLanguageChange chamado', [
            'language' => $language,
            'current_details_count' => count($this->details),
            'timestamp' => now()->toDateTimeString()
        ]);
        // Quando o idioma muda, pedimos ao componente pai para atualizar os detalhes
        $this->dispatch('update-details-requested', language: $language);
    }

    public function render()
    {
        Log::info('PageBuilder: render chamado', [
            'details_count' => count($this->details),
            'timestamp' => now()->toDateTimeString()
        ]);
        return view('livewire.page-builder', [
            'details' => $this->details
        ]);
    }
}
