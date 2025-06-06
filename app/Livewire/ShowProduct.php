<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ShowProduct extends Component
{
    public $product;
    public $details;
    public array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    public string $selectedLanguage = 'en';

    protected $listeners = ['updateSelectedLanguage'];

    public function mount($id)
    {
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
                $query->where('language', $this->selectedLanguage);
            },
        ])->findOrFail($id);

        $this->loadDetails();
    }

    public function loadDetails()
    {
        try {
            $this->details = $this->product->details->map(function($detail) {
                return json_decode($detail->content, true);
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
        
        // Recarrega o produto com os detalhes do novo idioma
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
                $query->where('language', $this->selectedLanguage);
            },
        ])->findOrFail($this->product->id);

        $this->loadDetails();
    }

    public function render()
    {
        return view('livewire.show-product');
    }
}
