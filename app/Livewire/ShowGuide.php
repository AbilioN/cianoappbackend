<?php

namespace App\Livewire;

use App\Models\Guide;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ShowGuide extends Component
{
    public $guide;
    public $details;
    public array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    public string $selectedLanguage = 'en';

    protected $listeners = ['updateSelectedLanguage'];

    public function mount($id)
    {
        $this->guide = Guide::with([
            'pages' => function($query) {
                $query->where('language', $this->selectedLanguage)
                      ->orderBy('order')
                      ->with(['components' => function($query) {
                          $query->orderBy('order');
                      }]);
            }
        ])->findOrFail($id);

        $this->loadDetails();
    }

    public function loadDetails()
    {
        try {
            $this->details = $this->guide->pages->flatMap(function($page) {
                return $page->components->map(function($component) {
                    $content = $component->content;
                    if (is_string($content)) {
                        $content = json_decode($content, true) ?? [];
                    }
                    if (!is_array($content)) {
                        $content = [];
                    }
                    return array_merge(
                        ['type' => $component->type],
                        $content
                    );
                });
            })->toArray();
            
            // Dispara evento para atualizar o PageBuilder
            $this->dispatch('page-builder-update', [
                'details' => $this->details
            ]);
        
        } catch (\Throwable $th) {
            Log::error('Error loading details: ' . $th->getMessage());
            throw $th;
        }
    }

    public function updateSelectedLanguage($language)
    {
        $this->selectedLanguage = $language;
        
        // Recarrega o guia com as páginas do novo idioma
        $this->guide = Guide::with([
            'pages' => function($query) {
                $query->where('language', $this->selectedLanguage)
                      ->orderBy('order')
                      ->with(['components' => function($query) {
                          $query->orderBy('order');
                      }]);
            }
        ])->findOrFail($this->guide->id);

        $this->loadDetails();
    }

    public function render()
    {
        return view('livewire.show-guide');
    }
} 