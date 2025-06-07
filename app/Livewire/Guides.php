<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Guide;
use Illuminate\Support\Facades\Log;

class Guides extends Component
{
    public $selectedCategory = null;
    public $language = 'en';
    public $search = '';
    public $guides = [];

    public function mount()
    {
        $this->loadGuides();
    }

    public function loadGuides()
    {
        Log::info('Guides::loadGuides - Iniciando carregamento de guias', [
            'selected_category' => $this->selectedCategory,
            'language' => $this->language,
            'search' => $this->search
        ]);

        $query = Guide::with([
            'pages' => function($query) {
                $query->where('language', $this->language)
                      ->orderBy('order')
                      ->with(['components' => function($query) {
                          $query->orderBy('order');
                      }]);
            }
        ]);

        if ($this->selectedCategory) {
            $query->where('category', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $this->guides = $query->get();

        Log::info('Guides::loadGuides - Guias carregados', [
            'guides_count' => count($this->guides)
        ]);
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
        $this->loadGuides();
    }

    public function updatedSearch()
    {
        $this->loadGuides();
    }

    public function clearFilters()
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->loadGuides();
    }

    public function updatedSelectedCategory($value)
    {
        Log::info('Guides::updatedSelectedCategory - Iniciando mudança de categoria', [
            'category' => $value,
            'current_language' => $this->language
        ]);

        $this->selectedCategory = $value;
        $this->loadGuides();

        Log::info('Guides::updatedSelectedCategory - Guias recarregados', [
            'guides_count' => count($this->guides)
        ]);
    }

    public function render()
    {
        return view('livewire.guides', [
            'filteredGuides' => $this->guides
        ]);
    }
} 