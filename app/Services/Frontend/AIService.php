<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Http;

class AIService
{
    /*
    |--------------------------------------------------------------------------
    | Generate Product Description
    |--------------------------------------------------------------------------
    */
    public function generateProductDescription(
        string $productName,
        string $categoryName = ""
    ): string {
        try {
            $response = Http::withoutVerifying()
            ->withToken(
                config('services.openai.key')
            )->post(
                'https://api.openai.com/v1/chat/completions',
                [
                    'model' => env(
                        'OPENAI_MODEL',
                        'gpt-4.1-mini'
                    ),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' =>
                                "You are an expert e-commerce content writer."
                        ],
                        [
                            'role' => 'user',
                            'content' =>
                                "Generate a professional and engaging product description for product '{$productName}' in category '{$categoryName}'. Limit response to 120 words."
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 250,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | API Error Handling
            |--------------------------------------------------------------------------
            */
            if ($response->failed()) {
                \Log::error(
                    'OpenAI API Error',
                    $response->json()
                );

                return 'Unable to generate description. Please check API key, billing, or OpenAI quota.';
            }

            /*
            |--------------------------------------------------------------------------
            | Return AI Description
            |--------------------------------------------------------------------------
            */
            return
                $response->json(
                    'choices.0.message.content'
                ) ??
                'AI could not generate description.';

        } catch (\Exception $e) {
            \Log::error(
                'OpenAI Exception: ' .
                $e->getMessage()
            );

            return
                'Error: ' .
                $e->getMessage();
        }
    }

    public function generateProductContent(
        string $productName,
        string $categoryName = ""
    ): array {
        try {
            $response = Http::withToken(
                config('services.openai.key')
            )->post(
                'https://api.openai.com/v1/chat/completions',
                [
                    'model' => env(
                        'OPENAI_MODEL',
                        'gpt-4.1-mini'
                    ),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' =>
                                "You are an expert e-commerce SEO content writer. Return only valid JSON without markdown."
                        ],
                        [
                            'role' => 'user',
                            'content' =>
                                "Generate the following for product '{$productName}' in category '{$categoryName}'.

                                  1. Product Description (Maximum 120 words)
                                  2. Short Product Description (Maximum 40 words)
                                  3. SEO Meta Title (Maximum 60 characters)
                                  4. SEO Meta Description (Maximum 160 characters)
                                  5. SEO Meta Keywords (Comma separated)

                                  Return ONLY valid JSON in the following format:

                                  {
                                      \"description\": \"\",
                                      \"short_description\": \"\",
                                      \"meta_title\": \"\",
                                      \"meta_description\": \"\",
                                      \"meta_keywords\": \"\"
                                  }"
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 600,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | API Error Handling
            |--------------------------------------------------------------------------
            */
            if ($response->failed()) {
                \Log::error(
                    'OpenAI API Error',
                    $response->json()
                );

                return [
                    'error' =>
                        'Unable to generate description. Please check API key, billing, or OpenAI quota.'
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Decode JSON Response
            |--------------------------------------------------------------------------
            */
            return json_decode(
                $response->json(
                    'choices.0.message.content'
                ),
                true
            );

        } catch (\Exception $e) {
            \Log::error(
                'OpenAI Exception: ' .
                $e->getMessage()
            );

            return [
                'error' =>
                    $e->getMessage()
            ];
        }
    }
}