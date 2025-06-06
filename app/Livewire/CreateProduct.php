<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductDetail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateProduct extends Component
{
    use WithFileUploads;

    public $product = [
        'name' => '',
        'product_category_id' => '',
        'image' => ''
    ];
    public $details = [];
    public $image;
    public $tempImageUrl;
    public array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    public string $selectedLanguage = 'en';

    public $draftDetails = [];
    public $publishedDetails = []; // Track published state per language
    public $hasDraftChanges = false;

    public $allowedToSave = false;

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
        $this->initializeDetails();
    }

    public function initializeDetails()
    {
        foreach ($this->languages as $language) {
            $this->details[$language] = [];
            $this->draftDetails[$language] = [];
            $this->publishedDetails[$language] = [];
        }

        Log::info('CreateProduct: mount chamado', [
            'languages' => $this->languages,
            'details' => $this->details,
            'timestamp' => now()->toDateTimeString()
        ]);
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
        $this->product['image'] = null;
    }

    public function addDetail()
    {
        $newDetail = [
            'type' => '',
            'value' => '',
            'text' => '',
            'url' => '',
            'content' => '',
            'items' => [],
            'is_draft' => true,
            'order' => count($this->details[$this->selectedLanguage] ?? [])
        ];

        // Adiciona o detalhe apenas para o idioma selecionado
        if (!isset($this->details[$this->selectedLanguage])) {
            $this->details[$this->selectedLanguage] = [];
        }
        $this->details[$this->selectedLanguage][] = $newDetail;

        $this->checkIfCanSave();

        Log::info('Novo detalhe adicionado', [
            'language' => $this->selectedLanguage,
            'detailsCount' => count($this->details[$this->selectedLanguage])
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
        foreach ($this->languages as $language) {
            unset($this->details[$language][$index]);
            $this->details[$language] = array_values($this->details[$language]);
        }
    }

    public function updateDetail($data)
    {
        $index = $data['index'];
        $detail = $data['detail'];
        $this->details[$this->selectedLanguage][$index] = $detail;
        
        // Dispara evento para atualizar o PageBuilder
        $this->dispatch('page-builder-update', [
            'index' => $index,
            'detail' => $detail
        ]);
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

    public function updatedProduct()
    {
        $this->checkIfCanSave();
    }

    public function checkIfCanSave()
    {
        // Verifica se o produto tem nome e categoria
        $hasBasicInfo = !empty($this->product['name']) && !empty($this->product['product_category_id']);

        // Verifica se há pelo menos um detalhe em cada idioma
        $hasDetailsInAllLanguages = true;
        foreach ($this->languages as $language) {
            if (empty($this->details[$language])) {
                $hasDetailsInAllLanguages = false;
                break;
            }
        }

        $this->allowedToSave = $hasBasicInfo && $hasDetailsInAllLanguages;

        Log::info('Verificação de permissão para salvar', [
            'hasBasicInfo' => $hasBasicInfo,
            'hasDetailsInAllLanguages' => $hasDetailsInAllLanguages,
            'allowedToSave' => $this->allowedToSave,
            'detailsCount' => array_map(function($lang) {
                return count($this->details[$lang]);
            }, $this->languages)
        ]);
    }

    public function saveAsDraft()
    {
        if (!$this->allowedToSave) {
            return;
        }

        // $this->validate();

        Log::info('Salvando como rascunho', [
            'product' => $this->product,
            'details' => $this->details,
            'image' => $this->image,
            'timestamp' => now()->toDateTimeString()
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($this->image) {
                $path = $this->image->store('products', 'public');
                $this->product['image'] = $path;
            }

            // Create product as draft
            $product = Product::create([
                'name' => $this->product['name'],
                'product_category_id' => $this->product['product_category_id'],
                'image' => $this->product['image'],
                'description' => '',
                'price' => '0',
                'stock' => '0',
                'status' => 'draft'
            ]);

            // Create details for each language
            foreach ($this->languages as $language) {
                foreach ($this->details[$language] as $order => $detail) {
                    if (!empty($detail['type'])) {
                        $content = $this->prepareContentForType($detail);
                        $product->details()->create([
                            'type' => $detail['type'],
                            'order' => $order,
                            'language' => $language,
                            'content' => json_encode($content)
                        ]);
                    }
                }
            }

            Log::info('Rascunho salvo com sucesso', [
                'product' => $product,
                'details' => $this->details,
                'timestamp' => now()->toDateTimeString()
            ]);

            DB::commit();
            session()->flash('message', 'Product saved as draft successfully.');
            $this->checkIfCanSave();
            // return redirect()->route('admin.products');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving product: ' . $e->getMessage());
        }
    }

    private function prepareContentForType($detail)
    {
        return match($detail['type']) {
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
                $this->product['image'] = $path;
            }

            // Create product
            $product = Product::create([
                'name' => $this->product['name'],
                'product_category_id' => $this->product['product_category_id'],
                'image' => $this->product['image'],
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
            'detailsBefore' => $this->details[$this->selectedLanguage] ?? []
        ]);

        // Salva os detalhes da língua atual antes de trocar
        if (isset($this->details[$this->selectedLanguage])) {
            $this->draftDetails[$this->selectedLanguage] = $this->details[$this->selectedLanguage];
        }

        // Troca a língua
        $this->selectedLanguage = $language;

        // Inicializa arrays para a nova língua se não existirem
        if (!isset($this->details[$language])) {
            $this->details[$language] = [];
        }

        // Tenta carregar do banco de dados
        if (isset($this->product['id'])) {
            $product = Product::with(['details' => function($query) use ($language) {
                $query->where('language', $language)
                      ->orderBy('order');
            }])->find($this->product['id']);

            if ($product && $product->details->isNotEmpty()) {
                $this->details[$language] = $product->details->map(function($detail) {
                    $content = json_decode($detail->content, true) ?? [];
                    return [
                        'type' => $detail->type,
                        'value' => $content['text'] ?? '',
                        'text' => $content['text'] ?? '',
                        'url' => $content['url'] ?? '',
                        'content' => $content,
                        'items' => $content['items'] ?? [],
                        'isDraft' => false,
                        'translations' => [
                            'en' => ['type' => '', 'value' => '', 'text' => '', 'url' => '', 'content' => '', 'items' => [], 'isDraft' => false],
                            'pt' => ['type' => '', 'value' => '', 'text' => '', 'url' => '', 'content' => '', 'items' => [], 'isDraft' => false],
                            'es' => ['type' => '', 'value' => '', 'text' => '', 'url' => '', 'content' => '', 'items' => [], 'isDraft' => false],
                            'fr' => ['type' => '', 'value' => '', 'text' => '', 'url' => '', 'content' => '', 'items' => [], 'isDraft' => false],
                            'it' => ['type' => '', 'value' => '', 'text' => '', 'url' => '', 'content' => '', 'items' => [], 'isDraft' => false],
                            'de' => ['type' => '', 'value' => '', 'text' => '', 'url' => '', 'content' => '', 'items' => [], 'isDraft' => false]
                        ]
                    ];
                })->toArray();
            }
        }

        Log::info('Troca de língua concluída', [
            'newLanguage' => $language,
            'detailsAfter' => $this->details[$language] ?? []
        ]);
    }

    public function updateSelectedLanguage($value)
    {
        Log::info('Atualizando idioma selecionado', [
            'from' => $this->selectedLanguage,
            'to' => $value
        ]);

        // Salva os detalhes do idioma atual antes de mudar
        if (!empty($this->details[$this->selectedLanguage])) {
            $this->saveDetailsAsDraft();
        }

        $this->selectedLanguage = $value;
        
        // Inicializa o array de detalhes para o novo idioma
        $this->details[$value] = [];

        // Carrega os detalhes do banco para o novo idioma
        $product = Product::where('status', 'draft')
                         ->where('name', $this->product['name'])
                         ->first();

        if ($product) {
            $savedDetails = $product->details()
                                  ->where('language', $value)
                                  ->orderBy('order')
                                  ->get();

            foreach ($savedDetails as $detail) {
                $content = json_decode($detail->content, true);
                $this->details[$value][] = [
                    'type' => $detail->type,
                    'content' => $content,
                    'order' => $detail->order,
                    'items' => $content['items'] ?? [],
                    'options' => $content['options'] ?? [],
                    'text' => $content['text'] ?? '',
                    'image' => $content['image'] ?? '',
                    'url' => $content['url'] ?? '',
                    'title' => $content['title'] ?? '',
                    'subtitle' => $content['subtitle'] ?? '',
                    'description' => $content['description'] ?? '',
                    'button_text' => $content['button_text'] ?? '',
                    'button_url' => $content['button_url'] ?? '',
                    'is_draft' => false
                ];
            }
        }

        Log::info('Detalhes carregados para o novo idioma', [
            'language' => $value,
            'details' => $this->details[$value]
        ]);

        $this->dispatch('language-changed', $value);
    }

    public function saveDetailsAsDraft()
    {
        Log::info('Salvando detalhes como rascunho', [
            'language' => $this->selectedLanguage,
            'details' => $this->details[$this->selectedLanguage] ?? []
        ]);

        // Primeiro, salva o produto se ainda não existir
        if (!isset($this->product['id'])) {
            $product = Product::create([
                'name' => $this->product['name'],
                'product_category_id' => $this->product['product_category_id'],
                'image' => $this->product['image'],
                'description' => '',
                'price' => $this->product['price'] ?? '0',
                'stock' => $this->product['stock'] ?? '0',
                'status' => 'draft'
            ]);
            $this->product['id'] = $product->id;
            $this->product = $product;
        }

        // Deleta os detalhes existentes para o idioma atual
        $deletedCount = ProductDetail::where('product_id', $this->product['id'])
            ->where('language', $this->selectedLanguage)
            ->delete();

        Log::info('Detalhes anteriores deletados', [
            'language' => $this->selectedLanguage,
            'deleted_count' => $deletedCount
        ]);

        // Salva os novos detalhes
        foreach ($this->details[$this->selectedLanguage] as $index => $detail) {
            $content = [];
            
            // Define o conteúdo baseado no tipo de input
            switch ($detail['type']) {
                case 'medium_text':
                case 'small_text':
                case 'text':
                    $content = [
                        'type' => $detail['type'],
                        'value' => $detail['value'] ?? ''
                    ];
                    break;
                case 'title':
                case 'title_left':
                    $content = [
                        'type' => $detail['type'],
                        'text' => $detail['text'] ?? ''
                    ];
                    break;
                case 'divider':
                    $content = [
                        'type' => 'divider'
                    ];
                    break;
                case 'large_image':
                case 'small_image':
                    $content = [
                        'type' => $detail['type'],
                        'url' => $detail['url'] ?? ''
                    ];
                    break;
                case 'yes_no':
                    $content = [
                        'type' => 'yes_no',
                        'value' => $detail['value'] ?? ''
                    ];
                    break;
                case 'ordered_list':
                    $content = [
                        'type' => 'ordered_list',
                        'items' => $detail['items'] ?? []
                    ];
                    break;
                default:
                    $content = [
                        'type' => $detail['type'],
                        'value' => $detail['value'] ?? ''
                    ];
            }

            // Log para debug
            Log::info('Salvando detalhe', [
                'type' => $detail['type'],
                'content' => $content,
                'original_detail' => $detail
            ]);

            ProductDetail::create([
                'product_id' => $this->product['id'],
                'type' => $detail['type'],
                'content' => json_encode($content),
                'language' => $this->selectedLanguage,
                'order' => $index
            ]);
        }

        Log::info('Detalhes salvos com sucesso', [
            'product' => Product::find($this->product['id']),
            'details' => $this->details[$this->selectedLanguage]
        ]);
    }

    public function render()
    {
        return view('livewire.create-product');
    }
} 