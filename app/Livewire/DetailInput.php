<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class DetailInput extends Component
{
    public $index;
    public $type = '';
    public $value = '';
    public $text = '';
    public $url = '';
    public $content = '';
    public $items = [];
    public $newItem = '';
    
    // Campos específicos para yes_or_no
    public $title = '';
    public $optionYes = [];
    public $optionNo = [];
    public $newOptionYes = '';
    public $newOptionNo = '';

    protected $listeners = ['resetType' => 'resetType'];

    public function mount($index, $detail = null)
    {
        $this->index = $index;
        if ($detail) {
            $this->type = $detail['type'] ?? '';
            $this->value = $detail['value'] ?? '';
            $this->text = $detail['text'] ?? '';
            $this->url = $detail['url'] ?? '';
            $this->content = $detail['content'] ?? '';
            $this->items = $detail['items'] ?? [];
            $this->newItem = $detail['newItem'] ?? '';
            
            // Carrega dados específicos do yes_or_no
            if ($this->type === 'yes_or_no') {
                $this->title = $detail['title'] ?? '';
                $this->optionYes = $detail['option_yes'] ?? [];
                $this->optionNo = $detail['option_no'] ?? [];
            }
        }
    }

    public function changeType($value)
    {
        $this->type = $value;
        $this->handleTypeChange();
    }

    protected function handleTypeChange()
    {
        \Log::info('Type changed to: ' . $this->type);
        
        $this->resetFields();
        
        // Atualiza o valor do campo baseado no tipo
        switch ($this->type) {
            case 'list':
            case 'ordered_list':
                $this->items = [];
                $this->newItem = '';
                break;
                
            case 'title':
            case 'title_left':
                $this->text = '';
                break;
                
            case 'description':
                $this->content = '';
                break;
                
            case 'notification_button':
            case 'link_button':
                $this->text = '';
                $this->url = '';
                break;
                
            case 'yes_or_no':
                $this->title = '';
                $this->optionYes = [];
                $this->optionNo = [];
                $this->newOptionYes = '';
                $this->newOptionNo = '';
                break;
                
            case 'divider':
                $this->value = null;
                break;
                
            default:
                $this->value = '';
        }

        // Notifica o componente pai sobre a mudança
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => $this->getDetailData()
        ]);
    }

    public function updatedType($value)
    {
        $this->handleTypeChange();
    }

    public function resetType()
    {
        $this->resetFields();
    }

    protected function resetFields()
    {
        $this->value = '';
        $this->text = '';
        $this->url = '';
        $this->content = '';
        $this->items = [];
        $this->newItem = '';
        $this->title = '';
        $this->optionYes = [];
        $this->optionNo = [];
        $this->newOptionYes = '';
        $this->newOptionNo = '';
    }

    public function addListItem()
    {
        if (!empty($this->newItem)) {
            $this->items[] = $this->newItem;
            $this->newItem = '';
            $this->dispatch('detail-updated', [
                'index' => $this->index,
                'detail' => $this->getDetailData()
            ]);
        }
    }

    public function removeListItem($itemIndex)
    {
        unset($this->items[$itemIndex]);
        $this->items = array_values($this->items);
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => $this->getDetailData()
        ]);
    }

    public function addOptionYes()
    {
        if (!empty($this->newOptionYes)) {
            $this->optionYes[] = [
                'type' => 'text',
                'value' => $this->newOptionYes
            ];
            $this->newOptionYes = '';
            $this->dispatch('detail-updated', [
                'index' => $this->index,
                'detail' => $this->getDetailData()
            ]);
        }
    }

    public function addOptionNo()
    {
        if (!empty($this->newOptionNo)) {
            $this->optionNo[] = [
                'type' => 'text',
                'value' => $this->newOptionNo
            ];
            $this->newOptionNo = '';
            $this->dispatch('detail-updated', [
                'index' => $this->index,
                'detail' => $this->getDetailData()
            ]);
        }
    }

    public function removeOptionYes($index)
    {
        unset($this->optionYes[$index]);
        $this->optionYes = array_values($this->optionYes);
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => $this->getDetailData()
        ]);
    }

    public function removeOptionNo($index)
    {
        unset($this->optionNo[$index]);
        $this->optionNo = array_values($this->optionNo);
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => $this->getDetailData()
        ]);
    }

    public function updated($property)
    {
        if ($property !== 'type' && $property !== 'newItem') {
            $this->dispatch('detail-updated', [
                'index' => $this->index,
                'detail' => $this->getDetailData()
            ]);
        }
    }

    public function getDetailData()
    {
        if ($this->type === 'yes_or_no') {
            return [
                'type' => $this->type,
                'title' => $this->title,
                'option_yes' => $this->optionYes,
                'option_no' => $this->optionNo
            ];
        }

        $data = [
            'type' => $this->type,
            'value' => $this->value,
            'text' => $this->text,
            'url' => $this->url,
            'content' => $this->content,
            'items' => $this->items,
            'newItem' => $this->newItem
        ];

        // Remove campos vazios para manter o array limpo
        return array_filter($data, function($value) {
            return $value !== '' && $value !== null && $value !== [];
        });
    }

    public function render()
    {
        return view('livewire.detail-input');
    }
} 