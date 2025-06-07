<?php

namespace App\Console\Commands;

use App\Models\Guide;
use App\Models\GuidePage;
use App\Models\GuideComponent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportGuidesFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guides:import-json 
        {--path=resources/guides} 
        {--category= : Category to import}
        {--guide= : Guide name to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import guides and their translations from JSON files';

    private array $languages = ['en', 'pt', 'es', 'fr', 'it', 'de'];
    private array $translations = [];
    private array $guideMap = []; // Maps guide categories to their IDs

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $targetCategory = $this->option('category');
        $targetGuide = $this->option('guide');

        // Truncate all tables to start fresh
        $this->info("\n=== Truncating Tables ===");
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        GuideComponent::truncate();
        GuidePage::truncate();
        Guide::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->info("Tables truncated successfully!");

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
        }

        if (empty($this->translations)) {
            $this->error("No valid translation files found in {$path}");
            return 1;
        }

        try {
            DB::beginTransaction();

            // PHASE 1: Import all guides first using English as reference
            $this->info("\n=== PHASE 1: Importing Guides ===");
            if (isset($this->translations['en'])) {
                foreach ($this->translations['en'] as $guideData) {
                    // Skip if category filter is set and doesn't match
                    if ($targetCategory && $guideData['category'] !== $targetCategory) {
                        continue;
                    }

                    // Skip if guide filter is set and doesn't match
                    if ($targetGuide && $guideData['name'] !== $targetGuide) {
                        continue;
                    }

                    $this->info("Processing guide: {$guideData['name']} (en) - Category: {$guideData['category']}");
                    
                    // Create guide
                    $guide = Guide::create([
                        'category' => $guideData['category'],
                        'name' => $guideData['name'],
                        'notification' => $guideData['notification'] ?? null
                    ]);

                    // Store guide ID in map using category as key
                    $this->guideMap[$guideData['category']] = $guide->id;
                }
            }

            // PHASE 2: Import pages for each language
            $this->info("\n=== PHASE 2: Importing Pages ===");
            foreach ($this->languages as $language) {
                if (!isset($this->translations[$language])) {
                    continue;
                }

                $this->info("\nProcessing pages for language: {$language}");
                $guides = $this->translations[$language];

                foreach ($guides as $guideData) {
                    // Skip if category filter is set and doesn't match
                    if ($targetCategory && $guideData['category'] !== $targetCategory) {
                        continue;
                    }

                    // Skip if guide filter is set and doesn't match
                    if ($targetGuide && $guideData['name'] !== $targetGuide) {
                        continue;
                    }

                    // Get guide ID from our map using category
                    $guideId = $this->guideMap[$guideData['category']] ?? null;
                    if (!$guideId) {
                        $this->warn("Guide ID not found for category: {$guideData['category']}");
                        continue;
                    }

                    $this->info("Importing pages for guide: {$guideData['name']} (Category: {$guideData['category']})");

                    // Import pages
                    foreach ($guideData['pages'] as $pageIndex => $components) {
                        $page = GuidePage::create([
                            'guide_id' => $guideId,
                            'language' => $language,
                            'order' => $pageIndex
                        ]);

                        // Import components
                        foreach ($components as $componentIndex => $componentData) {
                            GuideComponent::create([
                                'guide_page_id' => $page->id,
                                'order' => $componentIndex,
                                'type' => $componentData['type'],
                                'content' => $this->prepareComponentContent($componentData)
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            $this->info("\nImport completed successfully!");
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\nError during import: {$e->getMessage()}");
            Log::error('Guide import error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    private function prepareComponentContent(array $componentData): array
    {
        return match($componentData['type']) {
            'ordered_list', 'list' => [
                'items' => $componentData['items'] ?? [],
                'align' => $componentData['align'] ?? 'left'
            ],
            'text', 'medium_text', 'small_text' => [
                'value' => $componentData['value'] ?? ''
            ],
            'image', 'medium_image', 'large_image' , 'small_image' => [
                'url' => $componentData['url'] ?? ''
            ],
            'youtube' => [
                'url' => $componentData['url'] ?? ''
            ],
            'notification_button' => [
                'notificationName' => $componentData['notificationName'] ?? ''
            ],
            'divider' => [],
            default => []
        };
    }
}
