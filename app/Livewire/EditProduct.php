<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class EditProduct extends Component
{
    public $product;
    public $details;
  
    public array $languages = [ 'en', 'pt' , 'es', 'fr', 'it', 'de'];

    public string $selectedLanguage = 'en';

    public function mount($id)
    {
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
            },
            'details.translations'
        ])->findOrFail($id);

        // Load the current language's translations for details
        $this->details = $this->product->details->map(function($detail) {
            $translation = $detail->translations->where('language', $this->selectedLanguage)->first();
            $content = json_decode($translation?->content ?? '{}', true);
            return $content;
        })->toArray();

    }

    public function render()
    {
        return view('livewire.edit-product');
    }
}
