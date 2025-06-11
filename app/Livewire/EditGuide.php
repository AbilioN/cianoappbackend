<?php

namespace App\Livewire;

use App\Models\Guide;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditGuide extends Component
{
    public $guide;
    public $details;
    public array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    public string $selectedLanguage = 'en';
    public bool $editing = false;

    protected $listeners = [
        'remove-detail' => 'removeDetail',
        'detail-updated' => 'updateDetail',
        'detail-removed' => 'removeDetail',
        'detail-draft-saved' => 'handleDetailDraftSaved',
        'detail-published' => 'handleDetailPublished',
        'draft-validation-error' => 'handleDraftValidationError',
        'guide-detail-updated' => 'handleGuideDetailUpdated',
        'page-builder-update-detail' => 'handlePageBuilderUpdateDetail',
        'reset-editing-state' => 'handleResetEditingState',
        'show-save-feedback' => 'handleSaveFeedback',
        'disable-editing' => 'handleDisableEditing'
    ];

    public $draftDetails = [];
    public $hasDraftChanges = false;
    public $editingComponents = [];
    public $feedbackMessage = '';
    public $feedbackType = 'success';
    public $showFeedback = false;

    protected $rules = [
        'guide.name' => 'required|string|max:255',
        'guide.category' => 'required|string|max:255',
        'guide.notification' => 'nullable|string|max:255',
        'details.*.type' => 'required|string|max:255',
        'details.*.value' => 'required_unless:details.*.type,divider|string|max:255',
        'details.*.text' => 'required_if:details.*.type,title,title_left,notification_button,link_button|string|max:255',
        'details.*.url' => 'required_if:details.*.type,notification_button,link_button|url|max:255',
        'details.*.content' => 'required_if:details.*.type,description|string',
        'details.*.items' => 'required_if:details.*.type,list,ordered_list|array',
        'details.*.items.*' => 'required|string|max:255',
    ];

    public function mount($id)
    {
        $this->guide = Guide::with([
            'pages' => function($query) {
                $query->where('language', $this->selectedLanguage)
                      ->orderBy('order')
                      ->with(['components' => function($query) {
                          $query->orderBy('order');
                      }]);
            }
        ])->findOrFail($id);

        $this->loadDetails();
    }

    public function loadDetails()
    {
        try {
            $this->details = $this->guide->pages->flatMap(function($page) {
                return $page->components->map(function($component) {
                    $content = $component->content;
                    if (is_string($content)) {
                        $content = json_decode($content, true) ?? [];
                    }
                    if (!is_array($content)) {
                        $content = [];
                    }
                    return array_merge(
                        [
                            'id' => $component->id,
                            'type' => $component->type,
                            'guide_id' => $this->guide->id,
                            'guide_page_id' => $component->guide_page_id
                        ],
                        $content
                    );
                });
            })->toArray();

            // Dispara evento para atualizar o PageBuilder
            $this->dispatch('page-builder-update', [
                'details' => $this->details
            ]);
        } catch (\Throwable $th) {
            Log::error('Error loading details: ' . $th->getMessage());
            throw $th;
        }
    }

    public function updateSelectedLanguage($language)
    {
        $this->selectedLanguage = $language;
        
        // Recarrega o guia com as páginas do novo idioma
        $this->guide = Guide::with([
            'pages' => function($query) {
                $query->where('language', $this->selectedLanguage)
                      ->orderBy('order')
                      ->with(['components' => function($query) {
                          $query->orderBy('order');
                      }]);
            }
        ])->findOrFail($this->guide->id);

        $this->loadDetails();
    }

    public function toggleEditing($componentId = null)
    {
        if ($componentId === null) {
            // Toggle global editing state
            $this->editing = !$this->editing;
        } else {
            // Toggle component-specific editing state
            $this->editingComponents[$componentId] = !($this->editingComponents[$componentId] ?? false);
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Atualiza o guia
            $this->guide->save();

            // Deleta todas as páginas e componentes existentes
            $this->guide->pages()->delete();

            // Cria novas páginas com componentes
            foreach ($this->details as $order => $detail) {
                $page = $this->guide->pages()->create([
                    'language' => $this->selectedLanguage,
                    'order' => $order
                ]);

                $page->components()->create([
                    'type' => $detail['type'],
                    'order' => 0,
                    'content' => json_encode($detail)
                ]);
            }

            DB::commit();
            session()->flash('message', 'Guide updated successfully.');
            return redirect()->route('admin.guides');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating guide: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-guide');
    }

    public function handleResetEditingState($data)
    {
        $componentId = $data['component_id'];
        if (isset($this->editingComponents[$componentId])) {
            unset($this->editingComponents[$componentId]);
        }
    }

    public function handleSaveFeedback($data)
    {
        $this->feedbackMessage = $data['message'];
        $this->feedbackType = $data['type'] ?? 'success';
        $this->showFeedback = true;

        // Dispatch event to hide feedback after 3 seconds using JavaScript
        $this->dispatch('hide-feedback-after-delay');
    }

    public function hideFeedback()
    {
        $this->showFeedback = false;
    }

    public function handleDisableEditing($data)
    {
        if ($data['guide_id'] === $this->guide->id) {
            $this->editing = false;
        }
    }
} 