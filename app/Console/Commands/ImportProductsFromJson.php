<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportProductsFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import-json 
        {--path=resources/products} 
        {--category= : Category slug to import}
        {--product= : Product name to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products and their translations from JSON files';

    private array $languages = ['de', 'en', 'es', 'fr', 'it', 'pt'];
    private array $translations = [];
    private array $categoryTranslations = [];

    private array $categoriesTranslations = [
        'en' => [
            'ciano_care' => 'Ciano Care',
            'aquariums' => 'Aquariums',
            'lighting' => 'Lighting',
            'filtration' => 'Filtration',
            'tartariums' => 'Turtle Tanks',
        ],
        'pt' => [
            'ciano_care' => 'Ciano Care',
            'aquariums' => 'Aquários',
            'lighting' => 'Iluminação',
            'filtration' => 'Filtragem',
            'tartariums' => 'Tartarugueiras',
        ],
        'es' => [
            'ciano_care' => 'Ciano Care',
            'aquariums' => 'Acuarios',
            'lighting' => 'Iluminación',
            'filtration' => 'Filtración',
            'tartariums' => 'Tortugueras',
        ],
        'it' => [
            'ciano_care' => 'Ciano Care',
            'aquariums' => 'Acquari',
            'lighting' => 'Illuminazione',
            'filtration' => 'Filtraggio',
            'tartariums' => 'Tartarugherie',
        ],
        'fr' => [
            'ciano_care' => 'Ciano Care',
            'aquariums' => 'Aquariums',
            'lighting' => 'Éclairage',
            'filtration' => 'Filtrage',
            'tartariums' => 'Tortulières',
        ],
        'de' => [
            'ciano_care' => 'Ciano Care',
            'aquariums' => 'Aquarien',
            'lighting' => 'Beleuchtung',
            'filtration' => 'Filterung',
            'tartariums' => 'Tartarughebecken',
        ],
        
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $targetCategory = $this->option('category');
        $targetProduct = $this->option('product');

        // Load all translations first
        foreach ($this->languages as $language) {
            $file = "{$path}/{$language}.json";
            if (!File::exists($file)) {
                $this->warn("File not found: {$file}");
                continue;
            }

            $data = json_decode(File::get($file), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error("Invalid JSON in file: {$file}");
                continue;
            }

            $this->translations[$language] = $data;
            $this->categoryTranslations[$language] = $data; // Data is already the categories array
        }

        if (empty($this->translations)) {
            $this->error("No valid translation files found in {$path}");
            return 1;
        }

        DB::beginTransaction();
        try {
            // Process each language as reference
            foreach ($this->languages as $referenceLanguage) {
                if (!isset($this->translations[$referenceLanguage])) {
                    continue;
                }

                $this->info("\n=== Processing with {$referenceLanguage} as reference language ===");
                $categories = $this->translations[$referenceLanguage]; // Direct array of categories
                foreach ($categories as $categoryData) {
                    $categorySlug = Str::slug($categoryData['category']);
                    // Skip if category filter is set and doesn't match
                    if ($targetCategory && $categorySlug !== $targetCategory) {
                        continue;
                    }
                    $this->info("\nProcessing category: {$categoryData['category']} ({$referenceLanguage})");
                    // Create/update category with all translations, passing the current reference language
                    $category = $this->createOrUpdateCategoryWithTranslations($categoryData, $categorySlug, $referenceLanguage);
                    foreach ($categoryData['products'] ?? [] as $productData) {
                        // Skip if product filter is set and doesn't match
                        if ($targetProduct && $productData['name'] !== $targetProduct) {
                            continue;
                        }
                        $this->info("\nProcessing product: {$productData['name']}");
                        $product = $this->importProductWithTranslations($productData, $category->id, $categorySlug);
                        if ($product) {
                            $this->info("✓ Product imported successfully");
                        }
                    }
                }
            }

            // DB::commit();
            $this->info("\nImport completed successfully!");
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\nError during import: {$e->getMessage()}");
            return 1;
        }
    }

    private function createOrUpdateCategoryWithTranslations(array $categoryData, string $categorySlug, string $referenceLanguage)
    {
        // Create or update category in current reference language
        $category = $this->createOrUpdateCategory($categoryData, $referenceLanguage);
        dd($category);
        // Import translations for all languages
        foreach ($this->languages as $language) {
            if (!isset($this->categoryTranslations[$language])) {
                continue;
            }

            // Skip if it's the reference language (already processed)
            if ($language === $referenceLanguage) {
                continue;
            }

            // Find category in this language's data
            $translatedCategory = $this->findCategoryInTranslations($categorySlug, $language);
            
            if ($translatedCategory) {
                // Update category translation
                DB::table('product_category_translations')->updateOrInsert(
                    [
                        'product_category_id' => $category->id,
                        'language' => $language,
                    ],
                    [
                        'name' => $translatedCategory['category'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        return $category;
    }

    private function findCategoryInTranslations(string $categorySlug, string $language)
    {
        foreach ($this->categoryTranslations[$language] ?? [] as $category) {
            if (Str::slug($category['name']) === $categorySlug) {
                return $category;
            }
        }

        return null;
    }

    private function importProductWithTranslations(array $productData, int $categoryId, string $categorySlug)
    {
        // Create or update product in reference language
        $product = $this->createOrUpdateProduct($productData, $categoryId, array_key_first($this->translations));

        // Import translations for all languages
        foreach ($this->translations as $language => $translationData) {
            $this->info("  Processing {$language} translation...");

            // Find product in this language's data
            $translatedProduct = $this->findProductInTranslations($productData['name'], $categorySlug, $language);
            
            if ($translatedProduct) {
                // Update product translation
                $this->updateProductTranslation($product->id, $translatedProduct, $language);

                // Import product details with translations
                foreach ($translatedProduct['details'] ?? [] as $order => $detailData) {
                    $this->importProductDetailWithTranslation($detailData, $product->id, $order, $language);
                }
            } else {
                $this->warn("  No translation found for {$language}");
            }
        }

        return $product;
    }

    private function findProductInTranslations(string $productName, string $categorySlug, string $language)
    {
        $categories = $this->translations[$language]['categories'] ?? [];
        
        foreach ($categories as $category) {
            if (Str::slug($category['name']) === $categorySlug) {
                foreach ($category['products'] ?? [] as $product) {
                    if ($product['name'] === $productName) {
                        return $product;
                    }
                }
            }
        }

        return null;
    }

    private function createOrUpdateCategory(array $data, string $language)
    {
        $slug = Str::slug($data['name']);
        $category = DB::table('product_categories')
            ->where('slug', $slug)
            ->first();

        if (!$category) {
            $categoryId = DB::table('product_categories')->insertGetId([
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $category = (object)['id' => $categoryId];
        }

        // Create or update translation
        DB::table('product_category_translations')->updateOrInsert(
            [
                'product_category_id' => $category->id,
                'language' => $language,
            ],
            [
                'name' => $data['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return $category;
    }

    private function createOrUpdateProduct(array $data, int $categoryId, string $language)
    {
        $product = DB::table('products')
            ->where('name', $data['name'])
            ->where('product_category_id', $categoryId)
            ->first();

        if (!$product) {
            $productId = DB::table('products')->insertGetId([
                'product_category_id' => $categoryId,
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'image' => $data['image'] ?? '',
                'price' => $data['price'] ?? '0',
                'stock' => $data['stock'] ?? '0',
                'status' => $data['status'] ?? 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $product = (object)['id' => $productId];
        }

        return $product;
    }

    private function updateProductTranslation(int $productId, array $data, string $language)
    {
        DB::table('product_translations')->updateOrInsert(
            [
                'product_id' => $productId,
                'language' => $language,
            ],
            [
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function importProductDetailWithTranslation(array $data, int $productId, int $order, string $language)
    {
        $detail = DB::table('product_details')
            ->where('product_id', $productId)
            ->where('type', $data['type'])
            ->where('order', $order)
            ->first();

        if (!$detail) {
            $detailId = DB::table('product_details')->insertGetId([
                'product_id' => $productId,
                'type' => $data['type'],
                'content' => json_encode($data['content']),
                'order' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $detail = (object)['id' => $detailId];
        }

        // Create or update translation
        DB::table('product_detail_translations')->updateOrInsert(
            [
                'product_detail_id' => $detail->id,
                'language' => $language,
            ],
            [
                'content' => json_encode($data['content']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return $detail;
    }
}
