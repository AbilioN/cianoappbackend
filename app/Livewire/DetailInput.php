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
    public $selectedLanguage;
    public $draftTranslations = [];
    public $isDraft = false;
    
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
        'language-changed' => 'handleLanguageChange',
        'save-draft' => 'saveAsDraft',
        'publish-draft' => 'publishDraft'
    ];

    public function mount($index, $detail = null, $selectedLanguage = 'en')
    {
        $this->index = $index;
        $this->selectedLanguage = $selectedLanguage;
        $this->isDraft = false;

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

        // Initialize draft translations for all languages
        $this->initializeDraftTranslations();
    }

    protected function initializeDraftTranslations()
    {
        $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
        foreach ($languages as $lang) {
            $this->draftTranslations[$lang] = [
                'type' => $this->type,
                'value' => $this->value,
                'text' => $this->text,
                'url' => $this->url,
                'content' => $this->content,
                'items' => $this->items,
                'isDraft' => false
            ];
        }
    }

    public function changeType($type)
    {
        $this->type = $type;
        $this->dispatch('updateDetail', ['index' => $this->index, 'field' => 'type', 'value' => $type]);
        $this->handleTypeChange();
    }

    public function handleTypeChange()
    {
        Log::info('Type changed to: ' . $this->type);
        
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

        $detailData = $this->getDetailData();
        // Notifica o componente pai sobre a mudança
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => $detailData
        ]);
        // Dispara evento específico para o PageBuilder
        $this->dispatch('page-builder-update', [
            'index' => $this->index,
            'detail' => $detailData
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
        $this->dispatch('detail-removed', [
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
            $detailData = $this->getDetailData();
            
            // Dispara evento para atualização do detalhe
            $this->dispatch('detail-updated', [
                'index' => $this->index,
                'detail' => $detailData
            ]);

            // Dispara evento específico para o PageBuilder
            $this->dispatch('page-builder-update', [
                'index' => $this->index,
                'detail' => $detailData
            ]);

            // Log para debug
            Log::info('DetailInput: valor atualizado', [
                'property' => $property,
                'value' => $this->value,
                'detailData' => $detailData,
                'timestamp' => now()->toDateTimeString()
            ]);
        }
    }

    public function handleLanguageChange($language)
    {
        // Save current state to draft for previous language
        $this->saveCurrentStateToDraft($this->selectedLanguage);

        // Update selected language
        $this->selectedLanguage = $language;

        // Load draft state for new language
        $this->loadDraftState($language);

        // Notify parent component about the language change
        $this->dispatch('detail-updated', [
            'index' => $this->index,
            'detail' => $this->getDetailData()
        ]);
    }

    protected function saveCurrentStateToDraft($language)
    {
        $this->draftTranslations[$language] = [
            'type' => $this->type,
            'value' => $this->value,
            'text' => $this->text,
            'url' => $this->url,
            'content' => $this->content,
            'items' => $this->items,
            'isDraft' => $this->isDraft,
            'title' => $this->title,
            'optionYes' => $this->optionYes,
            'optionNo' => $this->optionNo
        ];
    }

    protected function loadDraftState($language)
    {
        $draft = $this->draftTranslations[$language] ?? null;
        if ($draft) {
            $this->type = $draft['type'];
            $this->value = $draft['value'];
            $this->text = $draft['text'];
            $this->url = $draft['url'];
            $this->content = $draft['content'];
            $this->items = $draft['items'];
            $this->isDraft = $draft['isDraft'];
            $this->title = $draft['title'] ?? '';
            $this->optionYes = $draft['optionYes'] ?? [];
            $this->optionNo = $draft['optionNo'] ?? [];
        }
    }

    public function saveAsDraft()
    {
        $this->saveCurrentStateToDraft($this->selectedLanguage);
        $this->isDraft = true;
        $this->dispatch('detail-draft-saved', [
            'index' => $this->index,
            'language' => $this->selectedLanguage,
            'data' => $this->draftTranslations[$this->selectedLanguage]
        ]);
    }

    public function publishDraft()
    {
        // Validate that all languages have the same structure
        if (!$this->validateDraftStructure()) {
            $this->dispatch('draft-validation-error', [
                'message' => 'All translations must have the same structure (type and number of items)'
            ]);
            return;
        }

        $this->isDraft = false;
        foreach ($this->draftTranslations as $lang => $draft) {
            $this->draftTranslations[$lang]['isDraft'] = false;
        }

        $this->dispatch('detail-published', [
            'index' => $this->index,
            'translations' => $this->draftTranslations
        ]);
    }

    protected function validateDraftStructure()
    {
        $referenceType = null;
        $referenceItemsCount = null;

        foreach ($this->draftTranslations as $lang => $draft) {
            if ($referenceType === null) {
                $referenceType = $draft['type'];
                $referenceItemsCount = is_array($draft['items']) ? count($draft['items']) : 0;
                continue;
            }

            if ($draft['type'] !== $referenceType) {
                return false;
            }

            if (is_array($draft['items']) && count($draft['items']) !== $referenceItemsCount) {
                return false;
            }
        }

        return true;
    }

    public function getDetailData()
    {
        $data = [
            'type' => $this->type,
            'value' => $this->value,
            'text' => $this->text,
            'url' => $this->url,
            'content' => $this->content,
            'items' => $this->items,
            'isDraft' => $this->isDraft,
            'translations' => $this->draftTranslations
        ];

        // Para tipos de imagem, garantir que o valor também esteja no campo url
        if (in_array($this->type, ['image', 'large_image', 'medium_image', 'small_image'])) {
            $data['url'] = $this->value;
        }

        return $data;
    }

    public function removeDetail()
    {
        $this->dispatch('remove-detail', ['index' => $this->index]);
    }

    public function updatedValue($value)
    {
        $this->dispatch('updateDetail', ['index' => $this->index, 'field' => 'value', 'value' => $value]);
    }

    public function render()
    {
        return view('livewire.detail-input');
    }

    public function updateDetail($data)
    {
        $index = $data['index'];
        $field = $data['field'];
        $value = $data['value'];
        // ...
    }
} 