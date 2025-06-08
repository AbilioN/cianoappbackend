<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class GuideComponentInput extends Component
{
    public $component;
    public $pageIndex;
    public $componentIndex;
    public $editing;
    public $value;
    public $text;
    public $items = [];
    public $newItem = '';
    public $selectedLanguage;

    protected $listeners = [
        'language-changed' => 'updateLanguage'
    ];

    public function mount($component, $pageIndex, $componentIndex, $editing = false)
    {
        $this->component = $component;
        $this->pageIndex = $pageIndex;
        $this->componentIndex = $componentIndex;
        $this->editing = $editing;
        $this->loadValue();
    }

    public function loadValue()
    {
        try {
            $content = $this->component['content'];
            
            switch ($this->component['type']) {
                case 'text':
                case 'large_text':
                case 'medium_text':
                case 'small_text':
                    $this->value = $content['value'] ?? '';
                    break;
                case 'title':
                case 'title_left':
                    $this->text = $content['text'] ?? '';
                    break;
                case 'list':
                case 'ordered_list':
                    $this->items = $content['items'] ?? [];
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Error loading component value: ' . $e->getMessage());
        }
    }

    public function updateLanguage($language)
    {
        $this->selectedLanguage = $language;
        $this->loadValue();
    }

    public function updatedValue()
    {
        $this->dispatch('component-updated', [
            'pageIndex' => $this->pageIndex,
            'componentIndex' => $this->componentIndex,
            'component' => [
                'id' => $this->component['id'],
                'type' => $this->component['type'],
                'content' => [
                    'value' => $this->value
                ]
            ]
        ]);
    }

    public function updatedText()
    {
        $this->dispatch('component-updated', [
            'pageIndex' => $this->pageIndex,
            'componentIndex' => $this->componentIndex,
            'component' => [
                'id' => $this->component['id'],
                'type' => $this->component['type'],
                'content' => [
                    'text' => $this->text
                ]
            ]
        ]);
    }

    public function addListItem()
    {
        if (!empty($this->newItem)) {
            $this->items[] = $this->newItem;
            $this->newItem = '';
            $this->saveItems();
        }
    }

    public function removeListItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->saveItems();
        }
    }

    private function saveItems()
    {
        $this->dispatch('component-updated', [
            'pageIndex' => $this->pageIndex,
            'componentIndex' => $this->componentIndex,
            'component' => [
                'id' => $this->component['id'],
                'type' => $this->component['type'],
                'content' => [
                    'items' => $this->items
                ]
            ]
        ]);
    }

    public function render()
    {
        return view('livewire.guide-component-input');
    }
} 