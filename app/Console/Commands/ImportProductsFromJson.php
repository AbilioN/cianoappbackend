<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use App\Models\ProductDetail;
use App\Models\ProductDetailTranslation;
// use App\Models\ProductTranslation;
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

    private array $languages = [ 'en', 'pt' , 'es', 'fr', 'it', 'de'];
    private array $translations = [];
    private array $categoryMap = []; // Maps category slugs to their IDs
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
            $this->categoryTranslations[$language] = $data;
        }

        if (empty($this->translations)) {
            $this->error("No valid translation files found in {$path}");
            return 1;
        }

        DB::beginTransaction();
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all tables
        ProductCategoryTranslation::truncate();
        ProductDetailTranslation::truncate();
        // ProductTranslation::truncate();
        // ProductDetail::truncate();
        // Product::truncate();
        // ProductCategory::truncate();

        // Re-enable foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        try {
            // PHASE 1: Import all categories first
            $this->info("\n=== PHASE 1: Importing Categories ===");
            $this->importAllCategories($targetCategory);

            // PHASE 2: Import products using the category map
            $this->info("\n=== PHASE 2: Importing Products ===");
            // $this->importAllProducts($targetCategory, $targetProduct);

            // DB::commit();
            $this->info("\nImport completed successfully!");
            DB::rollBack();

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\nError during import: {$e->getMessage()}");
            return 1;
        }
    }

    private function importAllCategories(?string $targetCategory)
    {
        // Process each language as reference
        foreach ($this->languages as $referenceLanguage) {
            if (!isset($this->translations[$referenceLanguage])) {
                continue;
            }

            $this->info("\nProcessing categories with {$referenceLanguage} as reference language");
            $categories = $this->translations[$referenceLanguage];
            foreach ($categories as $categoryData) {
                $categorySlug = $categoryData['category'];

                // Skip if category filter is set and doesn't match
                if ($targetCategory && $categorySlug !== $targetCategory) {
                    continue;
                }

                $this->info("Processing category: {$categoryData['category']} ({$referenceLanguage})");
                $this->info("Total of products: " . count($categories));
                
                // Create/update category and store its ID in the map
                $category = $this->createOrUpdateCategoryWithTranslations($categoryData, $categorySlug, $referenceLanguage);
                $this->categoryMap[$categorySlug] = $category->id;
            }
        }
    }

    private function importAllProducts(?string $targetCategory, ?string $targetProduct)
    {
        // Process each language as reference
        foreach ($this->languages as $referenceLanguage) {
            if (!isset($this->translations[$referenceLanguage])) {
                continue;
            }

            $this->info("\nProcessing products with {$referenceLanguage} as reference language");
            $categories = $this->translations[$referenceLanguage];

            foreach ($categories as $categoryData) {
                $categorySlug = Str::slug($categoryData['category']);
                
                // Skip if category filter is set and doesn't match
                if ($targetCategory && $categorySlug !== $targetCategory) {
                    continue;
                }

                // Get category ID from our map
                $categoryId = $this->categoryMap[$categorySlug] ?? null;
                if (!$categoryId) {
                    $this->warn("Category ID not found for slug: {$categorySlug}");
                    continue;
                }

                foreach ($categoryData['products'] ?? [] as $productData) {
                    // Skip if product filter is set and doesn't match
                    if ($targetProduct && $productData['name'] !== $targetProduct) {
                        continue;
                    }

                    $this->info("Processing product: {$productData['name']}");
                    $product = $this->importProductWithTranslations($productData, $categoryId, $categorySlug);
                    
                    if ($product) {
                        $this->info("✓ Product imported successfully");
                    }
                }
            }
        }
    }

    private function createOrUpdateCategory(array $data, string $language)
    {
        // $slug = Str::slug($data['category']);
        $slug = $data['category'];
        $category = ProductCategory::where('slug', $slug)->first();

        if (!$category) {
            $category = ProductCategory::create([
                'slug' => $slug,
            ]);
        }
        // Create or update translation
        $category->translations()->updateOrCreate(
            ['language' => $language],
            ['name' => $data['category']]
        );

        return $category;
    }

    private function createOrUpdateCategoryWithTranslations(array $categoryData, string $categorySlug, string $referenceLanguage)
    {
        // Create or update category in current reference language
        $category = ProductCategory::updateOrCreate(
            ['slug' => $categorySlug],
            []
        );

        // Create translation for reference language
        $category->translations()->updateOrCreate(
            ['language' => $referenceLanguage],
            ['name' => $this->categoriesTranslations[$referenceLanguage][$categorySlug]]
        );

        // Import translations for all other languages
        foreach ($this->languages as $language) {
            // Skip if it's the reference language (already processed)
            if ($language === $referenceLanguage) {
                continue;
            }

            // Create translation using the predefined translations
            if (isset($this->categoriesTranslations[$language][$categorySlug])) {
                $category->translations()->updateOrCreate(
                    ['language' => $language],
                    ['name' => $this->categoriesTranslations[$language][$categorySlug]]
                );
            }
        }

        $category->refresh();
        return $category;
    }

    private function findCategoryInTranslations(string $categorySlug, string $language)
    {
        foreach ($this->categoriesTranslations[$language] ?? [] as $category) {
            if (Str::slug($category) === $categorySlug) {
                return $category;
            }
        }

        return null;
    }

    private function importProductWithTranslations(array $data, int $categoryId, string $categorySlug)
    {
        // Create or update product in reference language
        $product = Product::updateOrCreate(
            [
                'name' => $data['name'],
                'product_category_id' => $categoryId,
            ],
            [
                'description' => $data['description'] ?? '',
                'image' => $data['image'] ?? '',
                'price' => $data['price'] ?? '0',
                'stock' => $data['stock'] ?? '0',
                'status' => $data['status'] ?? 'active',
            ]
        );

        // Import translations for all languages
        foreach ($this->languages as $language) {
            if (!isset($this->translations[$language])) {
                continue;
            }

            $this->info("  Processing {$language} translation...");

            // Find product in this language's data
            $translatedProduct = $this->findProductInTranslations($data['name'], $categorySlug, $language);
            
            if ($translatedProduct) {
                // Update product translation
                $product->translations()->updateOrCreate(
                    ['language' => $language],
                    [
                        'name' => $translatedProduct['name'],
                        'description' => $translatedProduct['description'] ?? '',
                    ]
                );

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

    private function importProductDetailWithTranslation(array $data, int $productId, int $order, string $language)
    {
        $detail = ProductDetail::updateOrCreate(
            [
                'product_id' => $productId,
                'type' => $data['type'],
                'order' => $order,
            ],
            [
                'content' => $data['content'],
            ]
        );

        // Create or update translation
        $detail->translations()->updateOrCreate(
            ['language' => $language],
            ['content' => $data['content']]
        );

        return $detail;
    }
}
