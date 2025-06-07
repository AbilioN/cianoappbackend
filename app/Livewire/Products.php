<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Products extends Component
{
    public $selectedCategory = null;
    public $language = 'en';
    public $search = '';
    public $categories = [];
    public $products = [];

    public function mount()
    {
        $this->loadCategories();
        $this->loadProducts();
    }

    public function loadCategories()
    {
        $this->categories = Cache::remember('product_categories_' . $this->language, 60*24, function() {
            return ProductCategory::with(['translations' => function($query) {
                $query->where('language', $this->language);
            }])->get();
        });
    }

    public function loadProducts()
    {
        Log::info('Products::loadProducts - Iniciando carregamento de produtos', [
            'selected_category' => $this->selectedCategory,
            'language' => $this->language,
            'search' => $this->search
        ]);

        $query = Product::with([
            'details' => function($query) {
                $query->orderBy('order');
                $query->where('language', $this->language);
            },
            'category.translations' => function($query) {
                $query->where('language', $this->language);
            }
        ]);

        if ($this->selectedCategory) {
            $query->where('product_category_id', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $this->products = $query->get();

        Log::info('Products::loadProducts - Produtos carregados', [
            'products_count' => count($this->products)
        ]);
    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->loadProducts();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function clearFilters()
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->loadProducts();
    }

    public function updatedSelectedCategory($value)
    {
        Log::info('Products::updatedSelectedCategory - Iniciando mudança de categoria', [
            'category_id' => $value,
            'current_language' => $this->language
        ]);

        $this->selectedCategory = $value;
        $this->loadProducts();

        Log::info('Products::updatedSelectedCategory - Produtos recarregados', [
            'products_count' => count($this->products)
        ]);
    }

    public function render()
    {
        return view('livewire.products', [
            'filteredProducts' => $this->products
        ]);
    }
}
