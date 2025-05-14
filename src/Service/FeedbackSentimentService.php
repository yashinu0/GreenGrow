<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FeedbackSentimentService
{
    private $client;
    private $ollamaUrl;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->ollamaUrl = 'http://localhost:11434/v1/chat/completions';
    }

    public function analyze(string $feedbackText): string
    {
        $prompt = sprintf(
            "Classify the sentiment of this feedback as POSITIVE, NEGATIVE, or NEUTRAL. Respond with only one word (POSITIVE, NEGATIVE, or NEUTRAL). Feedback: %s",
            $feedbackText
        );

        $body = [
            'model' => 'mistral',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3
        ];

        try {
            $response = $this->client->request('POST', $this->ollamaUrl, [
                'json' => $body,
                'timeout' => 10,
            ]);
            $data = $response->toArray();
            $sentiment = strtoupper(trim($data['choices'][0]['message']['content'] ?? 'NEUTRAL'));
            $sentiment = preg_replace('/[^A-Z]/', '', $sentiment);

            if (!in_array($sentiment, ['POSITIVE', 'NEGATIVE', 'NEUTRAL'])) {
                return 'NEUTRAL';
            }
            return $sentiment;
        } catch (\Throwable $e) {
            return 'NEUTRAL';
        }
    }
} 