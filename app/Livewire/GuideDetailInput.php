<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GuideComponent;
use Illuminate\Support\Facades\Log;

class GuideDetailInput extends Component
{
    public $detail;
    public $index;
    public $value = '';
    public $text = '';
    public $items = [];
    public $newItem = '';
    public $selectedLanguage;

    protected $listeners = [
        'language-changed' => 'updateLanguage'
    ];

    public function mount($detail, $index, $selectedLanguage = 'en')
    {
        $this->detail = $detail;
        $this->index = $index;
        $this->selectedLanguage = $selectedLanguage;
        $this->loadValue();
    }

    public function loadValue()
    {
        if (in_array($this->detail['type'], ['list', 'ordered_list'])) {
            $this->items = $this->detail['items'] ?? [];
        } elseif (in_array($this->detail['type'], ['title', 'title_left'])) {
            $this->text = $this->detail['text'] ?? '';
        } else {
            $this->value = $this->detail['value'] ?? '';
        }
    }

    public function updateLanguage($language)
    {
        $this->selectedLanguage = $language;
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
    }

    public function saveDetail()
    {
        try {
            $component = GuideComponent::find($this->detail['id']);
            if (!$component) {
                throw new \Exception('Component not found');
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

            $component->update([
                'content' => json_encode($content)
            ]);

            // Dispara evento para mostrar feedback visual
            $this->dispatch('show-save-feedback', [
                'message' => 'Changes saved successfully!'
            ]);

            // Redireciona para a mesma página para forçar o reload
            return redirect()->route('admin.guides.edit', ['id' => $this->detail['guide_id']]);

        } catch (\Exception $e) {
            Log::error('Error saving detail: ' . $e->getMessage());
            $this->dispatch('show-save-feedback', [
                'message' => 'Error saving changes: ' . $e->getMessage(),
                'type' => 'error'
            ]);
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.guide-detail-input');
    }
} 