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

    protected $listeners = [];

    public function mount($id)
    {
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
            },
            'details.translations'
        ])->findOrFail($id);

        $this->loadDetails();
    }

    public function loadDetails()
    {
        try {
            $this->details = $this->product->details->map(function($detail) {
                $translations = $detail->translations->where('language', $this->selectedLanguage);
                
                $content = $translations->map(function($translation) {
                    return json_decode($translation->content, true);
                });
                return $content->first();
            })->toArray();
            
            $this->dispatch('details-updated', details: $this->details);
        
        } catch (\Throwable $th) {
            Log::error('Error loading details: ' . $th->getMessage());
            throw $th;
        }
    }

    public function updateSelectedLanguage($language)
    {
        $this->selectedLanguage = $language;
        $this->loadDetails();
    }

    public function render()
    {
        return view('livewire.show-product');
    }
}
