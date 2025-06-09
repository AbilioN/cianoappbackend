<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class GuideController extends Controller
{
    public function getGuides(Request $request = null)
    {
        try {
            $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
            $result = [];

            foreach ($languages as $language) {
                // Carrega os guias do banco de dados para cada idioma
                $guides = Guide::with(['pages' => function($query) use ($language) {
                    $query->where('language', $language)
                          ->orderBy('order')
                          ->with(['components' => function($query) {
                              $query->orderBy('order');
                          }]);
                }])->get()->map(function($guide) {
                    return [
                        'title' => $guide->name,
                        'name' => $guide->name,
                        'category' => $guide->category,
                        'notification' => $guide->notification,
                        'pages' => $guide->pages->map(function($page) {
                            return $page->components->map(function($component) {
                                $content = is_string($component->content) ? json_decode($component->content, true) : $component->content;
                                if (!is_array($content)) {
                                    $content = [];
                                }
                                return array_merge(
                                    ['type' => $component->type],
                                    $content
                                );
                            })->toArray();
                        })->toArray()
                    ];
                });

                $result[$language] = $guides;
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error loading guides from database: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Em caso de erro, tenta carregar do arquivo como fallback
            return response()->json([
                'en' => $this->loadGuideFile('en'),
                'pt' => $this->loadGuideFile('pt'),
                'es' => $this->loadGuideFile('es'),
                'fr' => $this->loadGuideFile('fr'),
                'it' => $this->loadGuideFile('it'),
                'de' => $this->loadGuideFile('de')
            ]);
        }
    }

    /**
     * Método antigo mantido como backup
     */
    private function loadGuideFile($language)
    {
        try {
            $file = resource_path("guides/{$language}.json");
            if (!File::exists($file)) {
                return [];
            }

            $guides = json_decode(File::get($file), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }

            return $guides;
        } catch (\Exception $e) {
            Log::error('Error loading guide file: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Get guides for a specific language
     * 
     * @param string $language Language code (e.g., 'en', 'fr', 'pt', 'de')
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGuidesByLanguage($language)
    {
        
        try {
            Log::info("Requesting guides for language: " . $language);
            
            $guides = $this->loadGuideFile($language);

            if (empty($guides)) {
                Log::warning("No guides found for language: " . $language);
                return response()->json([
                    'success' => false,
                    'message' => "Guides not found for language: {$language}",
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => "Guides retrieved successfully",
                'data' => $guides
            ]);

        } catch (\Exception $e) {
            Log::error("Error in getGuidesByLanguage: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error retrieving guides: " . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    // Métodos similares para outros idiomas...
} 