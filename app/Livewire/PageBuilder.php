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
        'details-updated' => 'updateDetailsFromEvent',
        'page-builder-update' => 'handleDetailUpdate'
    ];

    // Permite receber detalhes como prop
    public function mount($details = [])
    {
        Log::info('PageBuilder: mount chamado', [
            'details_count' => count($details),
            'timestamp' => now()->toDateTimeString()
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
            'timestamp' => now()->toDateTimeString()
        ]);
        
        if (isset($data['details'])) {
            // Atualização completa dos detalhes
            $this->details = $data['details'];
            Log::info('PageBuilder: todos os detalhes atualizados', [
                'new_details_count' => count($this->details),
                'timestamp' => now()->toDateTimeString()
            ]);
        } else {
            // Atualização de um detalhe específico
            $index = $data['index'];
            $detail = $data['detail'];
            
            if (isset($this->details[$index])) {
                $oldDetail = $this->details[$index];
                
                // Para tipos de imagem, garantir que o valor esteja em ambos os campos
                if (in_array($detail['type'], ['image', 'large_image', 'medium_image', 'small_image'])) {
                    $detail['url'] = $detail['value'];
                }
                
                $this->details[$index] = $detail;
                
                Log::info('PageBuilder: detalhe específico atualizado', [
                    'index' => $index,
                    'old_detail' => $oldDetail,
                    'new_detail' => $detail,
                    'timestamp' => now()->toDateTimeString()
                ]);
            } else {
                Log::warning('PageBuilder: tentativa de atualizar detalhe inexistente', [
                    'index' => $index,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }
        }
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
