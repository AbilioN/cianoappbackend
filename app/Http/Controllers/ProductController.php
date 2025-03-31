<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getProducts()
    {
        return response()->json(
            cache()->remember('products', 60*24, function() {
                return [
                    // 'en' => $this->loadProductFile('en'),
                    // 'pt' => $this->loadProductFile('pt'),
                    // 'es' => $this->loadProductFile('es'),
                    // 'it' => $this->loadProductFile('it'),
                    // 'de' => $this->loadProductFile('de'),
                    // 'fr' => $this->loadProductFile('fr')
                    'en' => $this->loadProductFile('pt'),
                    'pt' => $this->loadProductFile('pt'),
                    'es' => $this->loadProductFile('pt'),
                    'it' => $this->loadProductFile('pt'),
                    'de' => $this->loadProductFile('pt'),
                    'fr' => $this->loadProductFile('pt')
                ];
            })
        );
    }

    public function getProductsByLanguage($language)
    {
        try {
            $products = $this->loadProductFile('pt');

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