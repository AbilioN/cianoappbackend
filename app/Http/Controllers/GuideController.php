<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GuideController extends Controller
{
    public function getGuides()
    {
        return response()->json(
            cache()->remember('guides', 60*24, function() {
                return [
                    'en' => $this->loadGuideFile('en'),
                    'pt' => $this->loadGuideFile('pt'),
                    'es' => $this->loadGuideFile('es'),
                    'it' => $this->loadGuideFile('it'),
                    'de' => $this->loadGuideFile('de'),
                    'fr' => $this->loadGuideFile('fr')
                ];
            })
        );
    }

    private function loadGuideFile($language)
    {

        
        $path = "{$language}.json";
        
        if (!Storage::disk('guides')->exists($path)) {
            Log::error("File not found at path: " . Storage::disk('guides')->path($path));
            return [];
        }
        
        $content = Storage::disk('guides')->get($path);
        return json_decode($content, true);
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