<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EditProduct extends Component
{
    public $product;
    public $details;
    public array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    public string $selectedLanguage = 'en';

    protected $rules = [
        'product.name' => 'required|string|max:255',
        'product.product_category_id' => 'required|exists:product_categories,id',
        'product.image' => 'nullable|string|max:255',
        'details.*.type' => 'required|string|max:255',
        'details.*.value' => 'required|string|max:255',
    ];

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
        $this->details = $this->product->details->map(function($detail) {
            $translation = $detail->translations->where('language', $this->selectedLanguage)->first();
            $content = json_decode($translation?->content ?? '{}', true);
            return $content;
        })->toArray();
    }

    public function updatedSelectedLanguage()
    {
        $this->loadDetails();
    }

    public function addDetail()
    {
        $this->details[] = [
            'type' => '',
            'value' => ''
        ];
    }

    public function removeDetail($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Update product
            $this->product->save();

            // Delete existing details
            $this->product->details()->delete();

            // Create new details with translations
            foreach ($this->details as $order => $detail) {
                $productDetail = $this->product->details()->create([
                    'type' => $detail['type'],
                    'order' => $order
                ]);

                // Create translations for all languages
                foreach ($this->languages as $language) {
                    $content = $language === $this->selectedLanguage 
                        ? $detail 
                        : ($this->product->details->first()?->translations->where('language', $language)->first()?->content ?? json_encode(['type' => '', 'value' => '']));

                    $productDetail->translations()->create([
                        'language' => $language,
                        'content' => is_string($content) ? $content : json_encode($content)
                    ]);
                }
            }

            DB::commit();
            session()->flash('message', 'Product updated successfully.');
            return redirect()->route('admin.products');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-product');
    }
}
