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

    protected $listeners = [
        'remove-detail' => 'removeDetail',
        'detail-updated' => 'updateDetail'
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

    public function mount($id)
    {
        $this->product = Product::with([
            'category.translations',
            'details' => function($query) {
                $query->orderBy('order');
            },
            'details.translations'
        ])->findOrFail($id);

        $this->loadDetails();
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
            $this->details = $this->product->details->map(function($detail) {
                $translations = $detail->translations->where('language', $this->selectedLanguage);
                $content = $translations->map(function($translation) {
                    return json_decode($translation->content, true);
                })->first() ?? [];

                // Convert content to the appropriate format based on type
                return match($detail->type) {
                    'list', 'ordered_list' => [
                        'type' => $detail->type,
                        'items' => $content['items'] ?? [],
                        'newItem' => '',
                    ],
                    'title', 'title_left' => [
                        'type' => $detail->type,
                        'text' => $content['text'] ?? '',
                    ],
                    'description' => [
                        'type' => $detail->type,
                        'content' => $content['content'] ?? '',
                    ],
                    'notification_button', 'link_button' => [
                        'type' => $detail->type,
                        'text' => $content['text'] ?? '',
                        'url' => $content['url'] ?? '',
                    ],
                    'yes_or_no' => [
                        'type' => $detail->type,
                        'value' => (bool)($content['value'] ?? false),
                    ],
                    'divider' => [
                        'type' => $detail->type,
                    ],
                    default => [
                        'type' => $detail->type,
                        'value' => $content['value'] ?? '',
                    ],
                };
            })->toArray();
            
            $this->dispatch('details-updated', details: $this->details);
        
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
        unset($this->details[$index]);
        $this->details = array_values($this->details);
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

            // Update product
            $this->product->save();

            // Delete all existing details and translations
            $this->product->details()->delete();

            // Create new details with translations
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

                $productDetail = $this->product->details()->create([
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
            session()->flash('message', 'Product updated successfully.');
            return redirect()->route('admin.products');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            session()->flash('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-product');
    }
}
