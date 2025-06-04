<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductCategory;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProducts()
    {
        return response()->json(
            
            cache()->remember('products', 60*24, function() {
                return [
                    'en' => $this->loadProductFile('en'),
                    'pt' => $this->loadProductFile('pt'),
                    'es' => $this->loadProductFile('es'),
                    'it' => $this->loadProductFile('it'),
                    'de' => $this->loadProductFile('de'),
                    'fr' => $this->loadProductFile('fr')
                    // 'en' => $this->loadProductFile('pt'),
                    // 'pt' => $this->loadProductFile('pt'),
                    // 'es' => $this->loadProductFile('pt'),
                    // 'it' => $this->loadProductFile('pt'),
                    // 'de' => $this->loadProductFile('pt'),
                    // 'fr' => $this->loadProductFile('pt')
                ];
            })
        );
    }

    public function getProductByLanguage($language)
    {
        // Get all categories with their translations
        $categories = ProductCategory::with(['translations' => function($query) use ($language) {
            $query->where('language', $language);
        }])->get();


        $result = [];
        foreach ($categories as $category) {
            // Get category translation for the specified language

            $categoryTranslation = $category->translations->first();
            if (!$categoryTranslation) continue;

            // Get all products for this category with their translations and details
            $products = Product::with([
                'details' => function($query) {
                    $query->orderBy('order');
                },
                'details.translations' => function($query) use ($language) {
                    $query->where('language', $language);
                }
            ])
            ->where('product_category_id', $category->id)
            ->get();

            $categoryProducts = [];
            foreach ($products as $product) {
         

                $productData = [
                    'name' => $product->name,
                    // 'description' => $productTranslation->description,
                    'image' => $product->image,
                    'category' => $category->name,
                    // 'price' => $product->price,
                    // 'stock' => $product->stock,
                    // 'status' => $product->status,
                    'details' => []
                ];

                // Process product details
                foreach ($product->details as $detail) {
                    $detailTranslation = $detail->translations->first();
                    if (!$detailTranslation) continue;

                    $detailContent = json_decode($detailTranslation->content, true);
                    if (!$detailContent) continue;
                    $productData['details'][] = $detailContent;

                }

                $categoryProducts[] = $productData;
            }

            // Add category to result if it has products
            if (!empty($categoryProducts)) {
                $result[] = [
                    'category' => $category->slug,
                    'products' => $categoryProducts
                ];
            }
        }

        return response()->json($result);
    }

    public function getProductsByLanguage($language)
    {
        try {

            // if($language == 'it' || $language == 'de' || $language == 'es') {
            //     $language = 'en';
            // }
            $products = $this->getProductByLanguage($language);
            // $products = $this->loadProductFile($language);
            if (empty($products)) {
                return response()->json([
                    'success' => false,
                    'message' => "Products not found for language: {$language}",
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => "Products retrieved successfully",
                'data' => $products
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => "Error retrieving products: " . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function index($lang)
    {
        // Temporariamente retornando sempre o conteúdo PT para todos os idiomas
        $content = Storage::get('resources/products/pt.json');
        return response()->json(json_decode($content));
    }

    public function guides($lang)
    {
        // Temporariamente retornando sempre o conteúdo PT para todos os idiomas
        $content = Storage::get('resources/guides/pt.json');
        return response()->json(json_decode($content));
    }

    private function loadProductFile($language)
    {
        $path = resource_path("products/{$language}.json");
        
        if (!file_exists($path)) {
            return [];
        }
        
        $content = file_get_contents($path);
        return json_decode($content, true);
    }
} 