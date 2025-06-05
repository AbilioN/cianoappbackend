<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

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

    public function render()
    {
        return view('livewire.products', [
            'filteredProducts' => $this->products
        ]);
    }
}
