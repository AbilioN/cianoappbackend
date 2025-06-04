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

    // Campos para tradução
    public $translations = [];
    public $currentLanguage = 'en';

    protected $listeners = [
        'resetType' => 'resetType',
        'remove-detail' => 'removeDetail',
        'language-changed' => 'handleLanguageChange'
    ];

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

            // Carrega traduções se existirem
            if (isset($detail['translations'])) {
                $this->translations = $detail['translations'];
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

    public function handleLanguageChange($language)
    {
        $this->currentLanguage = $language;
        
        // Atualiza os campos com as traduções do idioma atual
        if (isset($this->translations[$language])) {
            $translation = $this->translations[$language];
            
            switch ($this->type) {
                case 'yes_or_no':
                    $this->title = $translation['title'] ?? $this->title;
                    $this->optionYes = $translation['option_yes'] ?? $this->optionYes;
                    $this->optionNo = $translation['option_no'] ?? $this->optionNo;
                    break;
                    
                case 'title':
                case 'title_left':
                    $this->text = $translation['text'] ?? $this->text;
                    break;
                    
                case 'description':
                    $this->content = $translation['content'] ?? $this->content;
                    break;
                    
                case 'notification_button':
                case 'link_button':
                    $this->text = $translation['text'] ?? $this->text;
                    $this->url = $translation['url'] ?? $this->url;
                    break;
                    
                case 'list':
                case 'ordered_list':
                    $this->items = $translation['items'] ?? $this->items;
                    break;
                    
                default:
                    $this->value = $translation['value'] ?? $this->value;
            }
        }

        // Notifica o componente pai sobre a mudança
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => $this->getDetailData()
        ]);
    }

    public function getDetailData()
    {
        $data = [
            'type' => $this->type,
            'translations' => [
                $this->currentLanguage => []
            ]
        ];

        // Adiciona os campos específicos do tipo
        switch ($this->type) {
            case 'yes_or_no':
                $data['title'] = $this->title;
                $data['option_yes'] = $this->optionYes;
                $data['option_no'] = $this->optionNo;
                $data['translations'][$this->currentLanguage] = [
                    'title' => $this->title,
                    'option_yes' => $this->optionYes,
                    'option_no' => $this->optionNo
                ];
                break;
                
            case 'title':
            case 'title_left':
                $data['text'] = $this->text;
                $data['translations'][$this->currentLanguage] = [
                    'text' => $this->text
                ];
                break;
                
            case 'description':
                $data['content'] = $this->content;
                $data['translations'][$this->currentLanguage] = [
                    'content' => $this->content
                ];
                break;
                
            case 'notification_button':
            case 'link_button':
                $data['text'] = $this->text;
                $data['url'] = $this->url;
                $data['translations'][$this->currentLanguage] = [
                    'text' => $this->text,
                    'url' => $this->url
                ];
                break;
                
            case 'list':
            case 'ordered_list':
                $data['items'] = $this->items;
                $data['translations'][$this->currentLanguage] = [
                    'items' => $this->items
                ];
                break;
                
            default:
                $data['value'] = $this->value;
                $data['translations'][$this->currentLanguage] = [
                    'value' => $this->value
                ];
        }

        // Mantém as traduções de outros idiomas
        foreach ($this->translations as $lang => $translation) {
            if ($lang !== $this->currentLanguage) {
                $data['translations'][$lang] = $translation;
            }
        }

        return $data;
    }

    public function removeDetail($data)
    {
        if ($data['index'] === $this->index) {
            $this->dispatch('detail-removed', [
                'index' => $this->index
            ]);
        }
    }

    public function render()
    {
        return view('livewire.detail-input');
    }
} 