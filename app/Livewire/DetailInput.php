<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class DetailInput extends Component
{
    public $index;
    public $detail;
    public $value = '';
    public $text = '';
    public $items = [];
    public $newItem = '';
    public $selectedLanguage;

    protected $listeners = [
        'language-changed' => 'updateLanguage'
    ];

    public function mount($index, $detail = null, $selectedLanguage = 'en')
    {
        $this->index = $index;
        $this->selectedLanguage = $selectedLanguage;

        if ($detail) {
            $this->detail = $detail;
            $this->value = $detail['value'] ?? '';
            $this->text = $detail['text'] ?? '';
            $this->items = $detail['items'] ?? [];
        }
    }

    public function updateLanguage($language)
    {
        $this->selectedLanguage = $language;
    }

    public function updatedValue($value)
    {
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => [
                'id' => $this->detail['id'] ?? null,
                'type' => $this->detail['type'],
                'value' => $value
            ]
        ]);
    }

    public function updatedText($value)
    {
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => [
                'id' => $this->detail['id'] ?? null,
                'type' => $this->detail['type'],
                'text' => $value
            ]
        ]);
    }

    public function addListItem()
    {
        if (!empty($this->newItem)) {
            $this->items[] = $this->newItem;
            $this->newItem = '';
            $this->dispatch('detail-updated', [
                'index' => $this->index,
                'detail' => [
                    'id' => $this->detail['id'] ?? null,
                    'type' => $this->detail['type'],
                    'items' => $this->items
                ]
            ]);
        }
    }

    public function removeListItem($itemIndex)
    {
        unset($this->items[$itemIndex]);
        $this->items = array_values($this->items);
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => [
                'id' => $this->detail['id'] ?? null,
                'type' => $this->detail['type'],
                'items' => $this->items
            ]
        ]);
    }

    public function updateListItem($itemIndex)
    {
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => [
                'id' => $this->detail['id'] ?? null,
                'type' => $this->detail['type'],
                'items' => $this->items
            ]
        ]);
    }

    public function render()
    {
        return view('livewire.detail-input');
    }
} 