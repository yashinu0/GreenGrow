<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class OllamaService
{
    private $httpClient;
    private $logger;
    private $ollamaUrl;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        string $ollamaUrl = 'http://localhost:11434'
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->ollamaUrl = $ollamaUrl;
    }

    public function generateResponse(string $prompt): array
    {
        try {
            $response = $this->httpClient->request('POST', $this->ollamaUrl . '/api/generate', [
                'json' => [
                    'model' => 'mistral',
                    'prompt' => $prompt,
                    'stream' => false
                ]
            ]);

            $content = $response->toArray();
            
            return [
                'text' => $content['response'] ?? 'Désolé, je n\'ai pas pu générer une réponse appropriée.'
            ];
        } catch (\Exception $e) {
            $this->logger->error('Erreur Ollama: ' . $e->getMessage());
            throw $e;
        }
    }
} 