<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditProduct extends Component
{
    use WithFileUploads;

    public $product;
    public $details;
    public $image;
    public $tempImageUrl;
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
        'product-detail-updated' => 'handleProductDetailUpdated',
        'page-builder-update-detail' => 'handlePageBuilderUpdateDetail'
    ];

    public $draftDetails = [];
    public $hasDraftChanges = false;

    protected $rules = [
        'product.name' => 'required|string|max:255',
        'product.product_category_id' => 'required|exists:product_categories,id',
        'image' => 'nullable|image|max:2048', // max 2MB
        'product.image' => 'nullable|string|max:255',
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
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
                $query->where('language', $this->selectedLanguage);
            },
        ])->findOrFail($id);

        $this->loadDetails();
        $this->draftDetails = $this->details;
    }

    public function updatedImage()
    {
        $this->validateOnly('image');
        $this->tempImageUrl = $this->image->temporaryUrl();
    }

    public function removeImage()
    {
        $this->image = null;
        $this->tempImageUrl = null;
        $this->product->image = null;
    }

    public function loadDetails()
    {
        try {
            // Carrega todos os detalhes para manter a ordem
            $allDetails = $this->product->details->map(function($detail) {
                $content = json_decode($detail->content, true);
                return [
                    'id' => $detail->id,
                    'type' => $detail->type,
                    'order' => $detail->order,
                    'value' => $content['value'] ?? '',
                    'text' => $content['text'] ?? '',
                    'items' => $content['items'] ?? [],
                    'url' => $content['url'] ?? '',
                    'content' => $content['content'] ?? '',
                    'image' => $content['image'] ?? '',
                    'alt' => $content['alt'] ?? ''
                ];
            })->toArray();

            // Se estiver em modo de edição, filtra apenas os detalhes de texto
            if ($this->editing) {
                $this->details = collect($allDetails)
                    ->filter(function($detail) {
                        return in_array($detail['type'], [
                            'text',
                            'large_text',
                            'medium_text',
                            'small_text',
                            'list',
                            'ordered_list',
                            'title',
                            'title_left'
                        ]);
                    })
                    ->values()
                    ->toArray();
            } else {
                // Se não estiver em modo de edição, mantém todos os detalhes
                $this->details = $allDetails;
            }
            
            // Dispara evento para atualizar o PageBuilder com todos os detalhes
            $this->dispatch('page-builder-update', [
                'details' => $allDetails
            ]);
        
        } catch (\Throwable $th) {
            Log::error('Error loading details: ' . $th->getMessage());
            throw $th;
        }
    }

    public function updated($property)
    {
        if (preg_match('/^details\.(\d+)\.type$/', $property, $matches)) {
            $index = $matches[1];
            // Reset the value fields based on the new type
            $this->details[$index] = match($this->details[$index]['type']) {
                'list', 'ordered_list' => [
                    'type' => $this->details[$index]['type'],
                    'items' => [],
                    'newItem' => '',
                ],
                'title', 'title_left' => [
                    'type' => $this->details[$index]['type'],
                    'text' => '',
                ],
                'description' => [
                    'type' => $this->details[$index]['type'],
                    'content' => '',
                ],
                'notification_button', 'link_button' => [
                    'type' => $this->details[$index]['type'],
                    'text' => '',
                    'url' => '',
                ],
                'yes_or_no' => [
                    'type' => $this->details[$index]['type'],
                    'value' => false,
                ],
                'divider' => [
                    'type' => $this->details[$index]['type'],
                ],
                default => [
                    'type' => $this->details[$index]['type'],
                    'value' => '',
                ],
            };
        }
    }

    public function updateSelectedLanguage($language)
    {
        $this->selectedLanguage = $language;
        $this->loadDetails();
    }

    public function addDetail()
    {
        $this->details[] = [
            'type' => '',
            'value' => '',
            'items' => [],
            'newItem' => '',
        ];
    }

    public function addListItem($index)
    {
        if (!empty($this->details[$index]['newItem'])) {
            if (!isset($this->details[$index]['items'])) {
                $this->details[$index]['items'] = [];
            }
            $this->details[$index]['items'][] = $this->details[$index]['newItem'];
            $this->details[$index]['newItem'] = '';
        }
    }

    public function removeListItem($detailIndex, $itemIndex)
    {
        unset($this->details[$detailIndex]['items'][$itemIndex]);
        $this->details[$detailIndex]['items'] = array_values($this->details[$detailIndex]['items']);
    }

    public function updateDetail($data)
    {
        $index = $data['index'];
        $detail = $data['detail'];
        $this->details[$index] = $detail;
    }

    public function removeDetail($data)
    {
        $index = $data['index'];
        
        // Remove from both details and draft details
        if (isset($this->details[$index])) {
            unset($this->details[$index]);
            $this->details = array_values($this->details); // Reindex array
        }
        
        if (isset($this->draftDetails[$index])) {
            unset($this->draftDetails[$index]);
            $this->draftDetails = array_values($this->draftDetails); // Reindex array
        }
    }

    public function handleDetailDraftSaved($data)
    {
        $index = $data['index'];
        $language = $data['language'];
        $detailData = $data['data'];

        if (!isset($this->draftDetails[$index])) {
            $this->draftDetails[$index] = [];
        }

        $this->draftDetails[$index][$language] = $detailData;
        $this->hasDraftChanges = true;
    }

    public function handleDetailPublished($data)
    {
        $index = $data['index'];
        $translations = $data['translations'];

        // Update the main details array with the published translations
        $this->details[$index] = [
            'type' => $translations[$this->selectedLanguage]['type'],
            'value' => $translations[$this->selectedLanguage]['value'],
            'text' => $translations[$this->selectedLanguage]['text'],
            'url' => $translations[$this->selectedLanguage]['url'],
            'content' => $translations[$this->selectedLanguage]['content'],
            'items' => $translations[$this->selectedLanguage]['items'],
        ];

        // Update draft details to match
        $this->draftDetails[$index] = $translations;
        $this->hasDraftChanges = false;
    }

    public function handleDraftValidationError($data)
    {
        session()->flash('error', $data['message']);
    }

    public function handleProductDetailUpdated($data)
    {
        // Recarrega o produto com os detalhes atualizados
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
                $query->where('language', $this->selectedLanguage);
            },
        ])->findOrFail($this->product->id);

        // Atualiza os detalhes sem duplicar
        $allDetails = $this->product->details->map(function($detail) {
            $content = json_decode($detail->content, true);
            return [
                'id' => $detail->id,
                'type' => $detail->type,
                'order' => $detail->order,
                'value' => $content['value'] ?? '',
                'text' => $content['text'] ?? '',
                'items' => $content['items'] ?? [],
                'url' => $content['url'] ?? '',
                'content' => $content['content'] ?? '',
                'image' => $content['image'] ?? '',
                'alt' => $content['alt'] ?? ''
            ];
        })->toArray();

        // Se estiver em modo de edição, filtra apenas os detalhes de texto
        if ($this->editing) {
            $this->details = collect($allDetails)
                ->filter(function($detail) {
                    return in_array($detail['type'], [
                        'text',
                        'large_text',
                        'medium_text',
                        'small_text',
                        'list',
                        'ordered_list',
                        'title',
                        'title_left'
                    ]);
                })
                ->values()
                ->toArray();
        } else {
            // Se não estiver em modo de edição, mantém todos os detalhes
            $this->details = $allDetails;
        }
        
        // Dispara evento para atualizar o PageBuilder com todos os detalhes
        $this->dispatch('page-builder-update', [
            'details' => $allDetails
        ]);
    }

    public function handlePageBuilderUpdateDetail($data)
    {
        // Atualiza apenas o detalhe específico no PageBuilder
        $this->dispatch('page-builder-update', [
            'details' => collect($this->details)->map(function($detail) use ($data) {
                if ($detail['id'] === $data['detail']['id']) {
                    return array_merge($detail, [
                        'value' => $data['detail']['content']['value'] ?? '',
                        'text' => $data['detail']['content']['text'] ?? '',
                        'items' => $data['detail']['content']['items'] ?? []
                    ]);
                }
                return $detail;
            })->toArray()
        ]);
    }

    public function saveAsDraft()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($this->image) {
                // Delete old image if exists
                if ($this->product->image && Storage::disk('public')->exists($this->product->image)) {
                    Storage::disk('public')->delete($this->product->image);
                }
                $path = $this->image->store('products', 'public');
                $this->product->image = $path;
            }

            // Update product status to draft
            $this->product->status = 'draft';
            $this->product->save();

            // Delete all existing details and translations
            $this->product->details()->delete();

            // Create new details with translations from draft
            foreach ($this->draftDetails as $order => $translations) {
                $referenceLang = $this->selectedLanguage;
                $detailData = $translations[$referenceLang];

                // Create the detail with the reference language structure
                $productDetail = $this->product->details()->create([
                    'type' => $detailData['type'],
                    'order' => $order,
                    'content' => json_encode($this->prepareContentForType($detailData))
                ]);

                // Create translations for all languages
                foreach ($this->languages as $language) {
                    $translationData = $translations[$language] ?? $detailData;
                    $productDetail->translations()->create([
                        'language' => $language,
                        'content' => json_encode($this->prepareContentForType($translationData))
                    ]);
                }
            }

            DB::commit();
            session()->flash('message', 'Product saved as draft successfully.');
            return redirect()->route('admin.products');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            session()->flash('error', 'Error saving product as draft: ' . $e->getMessage());
        }
    }

    protected function prepareContentForType($data)
    {
        return match($data['type']) {
            'list', 'ordered_list' => ['items' => $data['items'] ?? []],
            'title', 'title_left' => ['text' => $data['text'] ?? ''],
            'description' => ['content' => $data['content'] ?? ''],
            'notification_button', 'link_button' => [
                'text' => $data['text'] ?? '',
                'url' => $data['url'] ?? '',
            ],
            'yes_or_no' => ['value' => (bool)($data['value'] ?? false)],
            'divider' => [],
            default => ['value' => $data['value'] ?? ''],
        };
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($this->image) {
                // Delete old image if exists
                if ($this->product->image && Storage::disk('public')->exists($this->product->image)) {
                    Storage::disk('public')->delete($this->product->image);
                }
                $path = $this->image->store('products', 'public');
                $this->product->image = $path;
            }

            // Update product status to active
            $this->product->status = 'active';
            $this->product->save();

            // Get all existing details to maintain order
            $existingDetails = $this->product->details()->orderBy('order')->get();
            $existingDetailsMap = $existingDetails->keyBy('id');

            // Update only text details while maintaining order
            foreach ($this->details as $detail) {
                if (isset($detail['id']) && isset($existingDetailsMap[$detail['id']])) {
                    $existingDetail = $existingDetailsMap[$detail['id']];
                    $currentContent = json_decode($existingDetail->content, true);
                    
                    // Atualiza apenas o conteúdo específico do tipo
                    $content = match($detail['type']) {
                        'text', 'large_text', 'medium_text', 'small_text' => array_merge($currentContent, [
                            'type' => $detail['type'],
                            'value' => $detail['value'] ?? ''
                        ]),
                        'list', 'ordered_list' => array_merge($currentContent, [
                            'type' => $detail['type'],
                            'items' => $detail['items'] ?? []
                        ]),
                        'title', 'title_left' => array_merge($currentContent, [
                            'type' => $detail['type'],
                            'text' => $detail['text'] ?? ''
                        ]),
                        default => $currentContent
                    };

                    $existingDetail->update([
                        'content' => json_encode($content)
                    ]);
                }
            }

            DB::commit();
            session()->flash('message', 'Product updated successfully.');
            $this->editing = false;
            $this->loadDetails();
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            session()->flash('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function changeLanguage($language)
    {
        $this->selectedLanguage = $language;
        $this->editing = false; // Fecha o editor
        
        // Recarrega o produto com os detalhes do novo idioma
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
                $query->where('language', $this->selectedLanguage);
            },
        ])->findOrFail($this->product->id);

        $this->loadDetails();
    }

    public function toggleEditing()
    {
        $this->editing = !$this->editing;
    }

    public function updatedSelectedLanguage($value)
    {
        $this->dispatch('language-changed', $value);
    }

    public function render()
    {
        return view('livewire.edit-product');
    }
}

