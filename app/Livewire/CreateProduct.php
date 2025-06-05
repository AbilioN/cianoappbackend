<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateProduct extends Component
{
    use WithFileUploads;

    public $product;
    public $details = [];
    public $image;
    public $tempImageUrl;
    public array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    public string $selectedLanguage = 'en';

    public $draftDetails = [];
    public $publishedDetails = []; // Track published state per language
    public $hasDraftChanges = false;

    protected $listeners = [
        'remove-detail' => 'removeDetail',
        'detail-updated' => 'updateDetail',
        'detail-removed' => 'removeDetail',
        'detail-draft-saved' => 'handleDetailDraftSaved',
        'detail-published' => 'handleDetailPublished',
        'draft-validation-error' => 'handleDraftValidationError'
    ];

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

    public function mount()
    {
        Log::info('Iniciando montagem do componente CreateProduct');
        $this->product = new Product();
        $this->selectedLanguage = 'en';
        
        // Initialize details structure for all languages
        foreach ($this->languages as $lang) {
            $this->details[$lang] = [];
            $this->draftDetails[$lang] = [];
            $this->publishedDetails[$lang] = [];
            Log::info("Estrutura inicial criada para língua: {$lang}", [
                'details' => $this->details[$lang],
                'draftDetails' => $this->draftDetails[$lang],
                'publishedDetails' => $this->publishedDetails[$lang]
            ]);
        }
    }

    public function updated($property)
    {
        Log::info('Property updated', [
            'property' => $property,
            'value' => $this->{$property} ?? null
        ]);

        if (preg_match('/^details\.(\d+)\.type$/', $property, $matches)) {
            $index = $matches[1];
            $type = $this->details[$this->selectedLanguage][$index]['type'];
            
            Log::info('Atualizando estrutura do detail após mudança de tipo', [
                'index' => $index,
                'type' => $type,
                'detailBefore' => $this->details[$this->selectedLanguage][$index]
            ]);

            // Reset the value fields based on the new type
            $this->details[$this->selectedLanguage][$index] = match($type) {
                'list', 'ordered_list' => [
                    'type' => $type,
                    'items' => [],
                    'newItem' => '',
                    'is_draft' => true,
                    'order' => $index
                ],
                'title', 'title_left' => [
                    'type' => $type,
                    'text' => '',
                    'is_draft' => true,
                    'order' => $index
                ],
                'description' => [
                    'type' => $type,
                    'content' => '',
                    'is_draft' => true,
                    'order' => $index
                ],
                'notification_button', 'link_button' => [
                    'type' => $type,
                    'text' => '',
                    'url' => '',
                    'is_draft' => true,
                    'order' => $index
                ],
                'yes_or_no' => [
                    'type' => $type,
                    'value' => false,
                    'is_draft' => true,
                    'order' => $index
                ],
                'divider' => [
                    'type' => $type,
                    'is_draft' => true,
                    'order' => $index
                ],
                default => [
                    'type' => $type,
                    'value' => '',
                    'is_draft' => true,
                    'order' => $index
                ],
            };

            Log::info('Estrutura do detail atualizada', [
                'index' => $index,
                'detailAfter' => $this->details[$this->selectedLanguage][$index]
            ]);
        }
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

    public function addDetail()
    {
        Log::info('Adicionando novo detail', [
            'currentLanguage' => $this->selectedLanguage,
            'currentDetailsCount' => count($this->details[$this->selectedLanguage])
        ]);

        $newDetail = [
            'type' => 'text',
            'value' => '',
            'is_draft' => true,
            'order' => count($this->details[$this->selectedLanguage])
        ];

        // Initialize empty arrays for all languages
        foreach ($this->languages as $lang) {
            if (!isset($this->details[$lang])) {
                $this->details[$lang] = [];
            }
            if (!isset($this->draftDetails[$lang])) {
                $this->draftDetails[$lang] = [];
            }
            if (!isset($this->publishedDetails[$lang])) {
                $this->publishedDetails[$lang] = [];
            }
        }

        // Add the detail to the current language
        $this->details[$this->selectedLanguage][] = $newDetail;
        $this->draftDetails[$this->selectedLanguage][] = $newDetail;
        
        Log::info('Novo detail adicionado', [
            'language' => $this->selectedLanguage,
            'detail' => $newDetail,
            'totalDetails' => count($this->details[$this->selectedLanguage]),
            'allDetails' => $this->details
        ]);
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

    public function removeDetail($index)
    {
        Log::info('Iniciando remoção de detail', [
            'index' => $index,
            'currentLanguage' => $this->selectedLanguage,
            'detailsBefore' => $this->details[$this->selectedLanguage]
        ]);

        foreach ($this->languages as $lang) {
            if (isset($this->details[$lang][$index])) {
                Log::info("Removendo detail da língua: {$lang}", [
                    'index' => $index,
                    'detail' => $this->details[$lang][$index]
                ]);

                unset($this->details[$lang][$index]);
                unset($this->draftDetails[$lang][$index]);
                unset($this->publishedDetails[$lang][$index]);

                // Reindex arrays
                $this->details[$lang] = array_values($this->details[$lang]);
                $this->draftDetails[$lang] = array_values($this->draftDetails[$lang]);
                $this->publishedDetails[$lang] = array_values($this->publishedDetails[$lang]);

                Log::info("Detail removido da língua: {$lang}", [
                    'detailsAfter' => $this->details[$lang],
                    'draftDetailsAfter' => $this->draftDetails[$lang],
                    'publishedDetailsAfter' => $this->publishedDetails[$lang]
                ]);
            }
        }
    }

    public function updateDetail($data)
    {
        Log::info('Atualizando detail', [
            'data' => $data
        ]);

        $index = $data['index'];
        $detail = $data['detail'];

        if (isset($this->details[$this->selectedLanguage][$index])) {
            // Atualiza o detail com os novos dados
            $this->details[$this->selectedLanguage][$index] = array_merge(
                $this->details[$this->selectedLanguage][$index],
                $detail
            );
            
            if (isset($this->publishedDetails[$this->selectedLanguage][$index])) {
                $this->publishedDetails[$this->selectedLanguage][$index] = array_merge(
                    $this->publishedDetails[$this->selectedLanguage][$index],
                    $detail
                );
                Log::info('Detail publicado atualizado', [
                    'index' => $index,
                    'detail' => $this->publishedDetails[$this->selectedLanguage][$index]
                ]);
            } else {
                $this->draftDetails[$this->selectedLanguage][$index] = array_merge(
                    $this->draftDetails[$this->selectedLanguage][$index],
                    $detail
                );
                Log::info('Detail em rascunho atualizado', [
                    'index' => $index,
                    'detail' => $this->draftDetails[$this->selectedLanguage][$index]
                ]);
            }
        }
    }

    public function handleDetailDraftSaved($detailData)
    {
        Log::info('Salvando rascunho de detail', [
            'detailData' => $detailData,
            'currentLanguage' => $this->selectedLanguage
        ]);

        $index = $detailData['index'];
        $language = $detailData['language'];
        $data = $detailData['data'];

        if (!isset($this->draftDetails[$language])) {
            $this->draftDetails[$language] = [];
        }

        $this->draftDetails[$language][$index] = $data;
        $this->hasDraftChanges = true;

        Log::info('Rascunho salvo', [
            'language' => $language,
            'index' => $index,
            'data' => $data
        ]);
    }

    public function handleDetailPublished($detailData)
    {
        Log::info('Publicando detail', [
            'detailData' => $detailData,
            'currentLanguage' => $this->selectedLanguage
        ]);

        $index = $detailData['index'];
        if (isset($this->details[$this->selectedLanguage][$index])) {
            $this->details[$this->selectedLanguage][$index] = array_merge(
                $this->details[$this->selectedLanguage][$index],
                $detailData['detail']
            );
            $this->details[$this->selectedLanguage][$index]['is_draft'] = false;
            
            $this->publishedDetails[$this->selectedLanguage][$index] = $this->details[$this->selectedLanguage][$index];
            unset($this->draftDetails[$this->selectedLanguage][$index]);

            Log::info('Detail publicado com sucesso', [
                'index' => $index,
                'detail' => $this->details[$this->selectedLanguage][$index],
                'draftDetails' => $this->draftDetails[$this->selectedLanguage],
                'publishedDetails' => $this->publishedDetails[$this->selectedLanguage]
            ]);

            // Check if all languages are published for this detail
            $allPublished = true;
            foreach ($this->languages as $lang) {
                if (!isset($this->publishedDetails[$lang][$index])) {
                    $allPublished = false;
                    break;
                }
            }

            Log::info('Verificação de publicação em todas as línguas', [
                'index' => $index,
                'allPublished' => $allPublished,
                'publishedStatus' => array_map(function($lang) use ($index) {
                    return isset($this->publishedDetails[$lang][$index]);
                }, $this->languages)
            ]);
        }
    }

    public function handleDraftValidationError($data)
    {
        session()->flash('error', $data['message']);
    }

    public function saveAsDraft()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($this->image) {
                $path = $this->image->store('products', 'public');
                $this->product->image = $path;
            }

            // Create product as draft
            $product = Product::create([
                'name' => $this->product->name,
                'product_category_id' => $this->product->product_category_id,
                'image' => $this->product->image,
                'description' => '',
                'price' => '0',
                'stock' => '0',
                'status' => 'draft'
            ]);

            // Create details with translations from draft
            foreach ($this->draftDetails as $order => $translations) {
                $referenceLang = $this->selectedLanguage;
                $detailData = $translations[$referenceLang];

                // Create the detail with the reference language structure
                $productDetail = $product->details()->create([
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
        // Check if all details are published in all languages
        foreach ($this->details as $index => $detail) {
            foreach ($this->languages as $language) {
                if (!isset($this->publishedDetails[$language][$index])) {
                    session()->flash('error', 'Please publish all detail drafts in all languages before saving the product.');
                    return;
                }
            }
        }

        $this->validate();

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($this->image) {
                $path = $this->image->store('products', 'public');
                $this->product->image = $path;
            }

            // Create product
            $product = Product::create([
                'name' => $this->product->name,
                'product_category_id' => $this->product->product_category_id,
                'image' => $this->product->image,
                'description' => '',
                'price' => '0',
                'stock' => '0',
                'status' => 'active'
            ]);

            // Create details with translations
            foreach ($this->details as $order => $detail) {
                // Prepare content based on type
                $content = match($detail['type']) {
                    'list', 'ordered_list' => ['items' => $detail['items'] ?? []],
                    'title', 'title_left' => ['text' => $detail['text'] ?? ''],
                    'description' => ['content' => $detail['content'] ?? ''],
                    'notification_button', 'link_button' => [
                        'text' => $detail['text'] ?? '',
                        'url' => $detail['url'] ?? '',
                    ],
                    'yes_or_no' => ['value' => (bool)($detail['value'] ?? false)],
                    'divider' => [],
                    default => ['value' => $detail['value'] ?? ''],
                };

                $productDetail = $product->details()->create([
                    'type' => $detail['type'],
                    'order' => $order
                ]);

                // Create translations for all languages
                foreach ($this->languages as $language) {
                    $translationContent = $language === $this->selectedLanguage 
                        ? $content 
                        : match($detail['type']) {
                            'list', 'ordered_list' => ['items' => []],
                            'title', 'title_left' => ['text' => ''],
                            'description' => ['content' => ''],
                            'notification_button', 'link_button' => ['text' => '', 'url' => ''],
                            'yes_or_no' => ['value' => false],
                            'divider' => [],
                            default => ['value' => ''],
                        };

                    $productDetail->translations()->create([
                        'language' => $language,
                        'content' => json_encode($translationContent)
                    ]);
                }
            }

            DB::commit();
            session()->flash('message', 'Product created successfully.');
            return redirect()->route('admin.products');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            session()->flash('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function changeLanguage($language)
    {
        Log::info('Trocando língua', [
            'from' => $this->selectedLanguage,
            'to' => $language,
            'detailsBefore' => $this->details[$this->selectedLanguage]
        ]);

        $this->selectedLanguage = $language;
        
        // Update current view with the correct state for each detail
        foreach ($this->details[$language] as $index => $detail) {
            if (isset($this->publishedDetails[$language][$index])) {
                $this->details[$language][$index] = $this->publishedDetails[$language][$index];
                Log::info("Detail {$index} carregado como publicado", [
                    'detail' => $this->details[$language][$index]
                ]);
            } else {
                $this->details[$language][$index] = $this->draftDetails[$language][$index];
                Log::info("Detail {$index} carregado como rascunho", [
                    'detail' => $this->details[$language][$index]
                ]);
            }
        }

        Log::info('Troca de língua concluída', [
            'newLanguage' => $language,
            'detailsAfter' => $this->details[$language]
        ]);
    }

    public function updatedSelectedLanguage($value)
    {
        $this->dispatch('language-changed', $value);
    }

    public function render()
    {
        return view('livewire.create-product');
    }
} 