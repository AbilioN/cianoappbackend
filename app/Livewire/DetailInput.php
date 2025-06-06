<?php

namespace App\Livewire;

use App\Models\ProductDetail;
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
            $this->saveDetail();
        }
    }

    public function removeListItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->saveDetail();
    }

    public function updatedItems($value, $key)
    {
        // Força a atualização do array de items
        $this->items = array_values($this->items);
        $this->saveDetail();
    }

    public function saveDetail()
    {
        try {
            $detail = ProductDetail::find($this->detail['id']);
            if (!$detail) {
                throw new \Exception('Detail not found');
            }

            $content = match($this->detail['type']) {
                'text', 'large_text', 'medium_text', 'small_text' => [
                    'type' => $this->detail['type'],
                    'value' => $this->value
                ],
                'list', 'ordered_list' => [
                    'type' => $this->detail['type'],
                    'items' => $this->items
                ],
                'title', 'title_left' => [
                    'type' => $this->detail['type'],
                    'text' => $this->text
                ],
                default => []
            };

            $detail->update([
                'content' => json_encode($content)
            ]);

            // Dispara evento para recarregar todo o produto
            $this->dispatch('product-detail-updated', [
                'product_id' => $detail->product_id
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving detail: ' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.detail-input');
    }
} 