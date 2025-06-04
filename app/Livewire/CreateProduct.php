<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateProduct extends Component
{
    public $product;
    public $details = [];
    public array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    public string $selectedLanguage = 'en';

    protected $listeners = [];

    protected $rules = [
        'product.name' => 'required|string|max:255',
        'product.product_category_id' => 'required|exists:product_categories,id',
        'product.image' => 'nullable|string|max:255',
        'details.*.type' => 'required|string|max:255',
        'details.*.value' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->product = new Product();
        $this->details = [
            [
                'type' => '',
                'value' => ''
            ]
        ];
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
            // Create product
            $product = Product::create([
                'name' => $this->product->name,
                'product_category_id' => $this->product->product_category_id,
                'image' => $this->product->image,
                'description' => '',
                'price' => '0',
                'stock' => '0',
                'status' => 'active'
            ]);

            // Create details with translations
            foreach ($this->details as $order => $detail) {
                $productDetail = $product->details()->create([
                    'type' => $detail['type'],
                    'order' => $order
                ]);

                // Create translations for all languages
                foreach ($this->languages as $language) {
                    $content = $language === $this->selectedLanguage 
                        ? $detail 
                        : ['type' => '', 'value' => ''];

                    $productDetail->translations()->create([
                        'language' => $language,
                        'content' => json_encode($content)
                    ]);
                }
            }

            DB::commit();
            session()->flash('message', 'Product created successfully.');
            return redirect()->route('admin.products');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-product');
    }
} 