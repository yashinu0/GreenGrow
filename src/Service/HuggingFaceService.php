<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class HuggingFaceService
{
    private $httpClient;
    private $apiKey;
    private $logger;
    private $model;

    public function __construct(
        HttpClientInterface $httpClient,
        string $apiKey,
        LoggerInterface $logger,
        string $model = 'mistralai/Mistral-7B-Instruct-v0.1'
    ) {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->logger = $logger;
        $this->model = $model;
    }

    public function generateResponse(string $prompt): array
    {
        try {
            $this->logger->info('Appel Hugging Face avec prompt: ' . $prompt);
            
            $response = $this->httpClient->request('POST', 'https://api-inference.huggingface.co/models/' . $this->model, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'inputs' => '[INST] Tu es un assistant du service client professionnel et empathique. ' . $prompt . ' [/INST]'
                ],
                'timeout' => 60
            ]);

            $statusCode = $response->getStatusCode();
            $content = $response->getContent();
            
            $this->logger->info('Code de statut Hugging Face: ' . $statusCode);
            $this->logger->info('Réponse brute: ' . $content);

            if ($statusCode !== 200) {
                throw new \Exception('Erreur API: ' . $statusCode . ' - ' . $content);
            }

            $data = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erreur de décodage JSON: ' . json_last_error_msg());
            }

            $this->logger->info('Réponse décodée: ' . json_encode($data));

            if (!isset($data[0]['generated_text'])) {
                throw new \Exception('Format de réponse invalide: ' . json_encode($data));
            }

            $generatedText = $data[0]['generated_text'];
            
            // Nettoyer la réponse
            $generatedText = str_replace('[INST]', '', $generatedText);
            $generatedText = str_replace('[/INST]', '', $generatedText);
            $generatedText = trim($generatedText);
            
            // Si la réponse est vide après nettoyage
            if (empty($generatedText)) {
                throw new \Exception('Réponse vide après nettoyage');
            }
            
            return [
                'text' => $generatedText,
                'raw_response' => $data
            ];
            
        } catch (\Exception $e) {
            $this->logger->error('Erreur Hugging Face: ' . $e->getMessage(), [
                'exception' => $e,
                'prompt' => $prompt,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
