<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TranslateProducts extends Command
{
    protected $signature = 'products:translate';
    protected $description = 'Traduz os produtos para diferentes idiomas usando ChatGPT';

    private $chatGptEndpoint = 'https://api.openai.com/v1/chat/completions';
    private $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = config('services.openai.api_key');
    }

    public function handle()
    {
        $this->info('Iniciando tradução dos produtos...');

        // Idiomas de destino
        $targetLanguages = [
            'en' => 'inglês britânico',
            'de' => 'alemão',
            'it' => 'italiano',
            'fr' => 'francês',
            'es' => 'espanhol'
        ];

        // Ler o arquivo original em português
        $originalJson = file_get_contents(resource_path('products/pt.json'));
        $products = json_decode($originalJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Erro ao decodificar o arquivo JSON: ' . json_last_error_msg());
            return 1;
        }

        // Processar cada idioma
        foreach ($targetLanguages as $langCode => $languageName) {
            $this->info("Traduzindo para {$languageName}...");
            
            $translatedProducts = [];
            $progressBar = $this->output->createProgressBar(count($products));
            
            foreach ($products as $product) {
                $translatedProducts[] = $this->translateProduct($product, $languageName);
                $progressBar->advance();
                
                // Pequena pausa para não sobrecarregar a API
                usleep(500000); // 0.5 segundos
            }
            
            $progressBar->finish();
            $this->newLine();

            // Salvar o arquivo traduzido
            $outputFile = resource_path("products/{$langCode}.json");
            file_put_contents(
                $outputFile, 
                json_encode($translatedProducts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
            
            $this->info("Arquivo traduzido para {$languageName} salvo em {$outputFile}");
        }

        $this->info('Tradução concluída!');
        return 0;
    }

    private function translateText($text, $targetLanguage)
    {
        if (empty($text)) return $text;
        
        // Não traduzir URLs ou tags HTML
        if (strpos($text, 'http') === 0 || strpos($text, '<') !== false) {
            return $text;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->chatGptEndpoint, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Você é um tradutor profissional especializado em produtos para aquários. Traduza o texto mantendo a formatação HTML e termos técnicos específicos do setor."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Traduza o seguinte texto para {$targetLanguage}, mantendo a formatação HTML e termos técnicos:\n\n{$text}"
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 1000
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            $this->warn("Erro na tradução: " . $response->body());
            return $text;
        } catch (\Exception $e) {
            $this->warn("Erro ao traduzir: " . $e->getMessage());
            return $text;
        }
    }

    private function translateProduct($product, $targetLanguage)
    {
        $translated = $product;
        
        // Traduzir o nome do produto
        $translated['name'] = $this->translateText($product['name'], $targetLanguage);
        
        // Traduzir os detalhes
        foreach ($translated['details'] as &$detail) {
            if (isset($detail['text'])) {
                $detail['text'] = $this->translateText($detail['text'], $targetLanguage);
            }
            if (isset($detail['value'])) {
                $detail['value'] = $this->translateText($detail['value'], $targetLanguage);
            }
            if (isset($detail['items'])) {
                foreach ($detail['items'] as &$item) {
                    $item = $this->translateText($item, $targetLanguage);
                }
            }
        }
        
        return $translated;
    }
} 