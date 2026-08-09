<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Http;
use App\Models\AISetting;

class AIService
{
    /*
    |--------------------------------------------------------------------------
    | Generate Product Description
    |--------------------------------------------------------------------------
    */

    public function generateProductContent(
        string $productName,
        string $categoryName = ""
    ): array {
        $settings = AISetting::first();

        $model = $settings?->openai_model ?? 'gpt-4.1-mini';
        $temperature = $settings?->temperature ?? 0.7;
        $maxTokens = $settings?->max_tokens ?? 600;
        $descriptionLength = $settings?->description_length ?? 120;
        $shortDescriptionLength = $settings?->short_description_length ?? 40;
        $keywordCount = $settings?->keyword_count ?? 10;
        $writingTone = $settings?->writing_tone ?? 'Professional';

        $systemPrompt = $settings?->system_prompt
            ?? 'You are an expert e-commerce SEO content writer. Return only valid JSON without markdown.';

        try {
            $response = Http::withToken(
                config('services.openai.key')
            )->post(
                'https://api.openai.com/v1/chat/completions',
                [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' =>
                                "Generate the following for product '{$productName}' in category '{$categoryName}'.

                                    1. Product Description (Maximum {$descriptionLength} words)
                                    2. Short Product Description (Maximum {$shortDescriptionLength} words)
                                    3. SEO Meta Title (Maximum 60 characters)
                                    4. SEO Meta Description (Maximum 160 characters)
                                    5. Generate exactly {$keywordCount} comma-separated SEO keywords.

                                    Write the complete content in {$writingTone} tone.

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
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
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
                        'Unable to generate AI content.'
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

    /*
    |--------------------------------------------------------------------------
    | Generate Category Description
    |--------------------------------------------------------------------------
    */
    public function generateCategoryContent(
        string $categoryName,
        string $parentCategory = ""
    ): array {
        $settings = AISetting::first();

        $model = $settings?->openai_model ?? 'gpt-4.1-mini';
        $temperature = $settings?->temperature ?? 0.7;
        $maxTokens = $settings?->max_tokens ?? 600;
        $descriptionLength = $settings?->description_length ?? 120;
        $keywordCount = $settings?->keyword_count ?? 10;
        $writingTone = $settings?->writing_tone ?? 'Professional';

        $systemPrompt = $settings?->system_prompt
            ?? 'You are an expert e-commerce SEO content writer. Return only valid JSON without markdown.';

        try {
            $response = Http::withToken(
                config('services.openai.key')
            )->post(
                'https://api.openai.com/v1/chat/completions',
                [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' =>
                                "Generate the following for category '{$categoryName}'.

                                1. Category Description (Maximum {$descriptionLength} words)
                                2. SEO Meta Title (Maximum 60 characters)
                                3. SEO Meta Description (Maximum 160 characters)
                                4. Generate exactly {$keywordCount} comma-separated SEO keywords.

                                Write the complete content in {$writingTone} tone.

                                Return ONLY valid JSON in the following format:
                                {
                                    \"description\": \"\",
                                    \"meta_title\": \"\",
                                    \"meta_description\": \"\",
                                    \"meta_keywords\": \"\"
                                }"
                        ],
                    ],
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
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
                        'Unable to generate AI content.'
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

    /*
    |--------------------------------------------------------------------------
    | Detect AI Search Query
    |--------------------------------------------------------------------------
    */
    public function isNaturalLanguageSearch(
        string $query
    ): bool
    {
        $query = strtolower(trim($query));

        $patterns = [
            'show me',
            'find',
            'looking for',
            'under',
            'above',
            'between',
            'recommend',
            'need',
            'want',
            'with',
            'I',
            'without'
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($query, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Extract Search Filters
    |--------------------------------------------------------------------------
    */
    public function extractSearchFilters(
        string $query
    ): array
    {
        /*
        |--------------------------------------------------------------------------
        | AI Settings
        |--------------------------------------------------------------------------
        */
        $settings = AISetting::first();

        $model = $settings->model ?? 'gpt-4.1-mini';
        $temperature = $settings->temperature ?? 0.2;
        $maxTokens = 300;

        /*
        |--------------------------------------------------------------------------
        | System Prompt
        |--------------------------------------------------------------------------
        */
        $systemPrompt = <<<PROMPT
    You are an AI shopping assistant.

    Your task is to understand the customer's search query and convert it into structured JSON.

    Rules:
    - Detect the product category.
    - Detect the brand if available.
    - Detect minimum and maximum price.
    - If the user writes "mobile", treat it as "Phones".
    - If the user writes "iphone mobile", detect:
    Brand = iPhone
    Category = Phones
    - If the user writes "Samsung mobile", detect:
    Brand = Samsung
    Category = Phones
    - If the user writes only "mobile", detect:
    Category = Phones
    - If no brand is mentioned, keep brand empty.
    - If no price is mentioned, return null.
    - Never explain anything.
    - Never use Markdown.
    - Return ONLY valid JSON.

    Examples

    Query:
    Show me Samsung phones under 80000

    Output:
    {
    "category":"Phones",
    "brand":"Samsung",
    "min_price":null,
    "max_price":80000,
    "keywords":[]
    }

    Query:
    Samsung mobile under 80000

    Output:
    {
        "category":"Phones",
        "brand":"Samsung",
        "min_price":null,
        "max_price":80000,
        "keywords":[]
    }

    Query:
    mobile under 80000

    Output:
    {
        "category":"Phones",
        "brand":"",
        "min_price":null,
        "max_price":80000,
        "keywords":[]
    }

    Query:
    iphone mobile

    Output:
    {
        "category":"Phones",
        "brand":"iPhone",
        "min_price":null,
        "max_price":null,
        "keywords":[]
    }

    Return exactly this JSON structure:
    {
        "category":"",
        "brand":"",
        "min_price":null,
        "max_price":null,
        "keywords":[]
    }
    PROMPT;

        try {
            $response = Http::withToken(
                config('services.openai.key')
            )->post(
                'https://api.openai.com/v1/chat/completions',
                [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $query,
                        ],
                    ],
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
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

                return [];
            }

            /*
            |--------------------------------------------------------------------------
            | Decode JSON Response |
            |--------------------------------------------------------------------------
            */
            $content = $response->json(
                'choices.0.message.content'
            );

            $filters = json_decode(
                $content,
                true
            );

            if (
                json_last_error()
                !== JSON_ERROR_NONE
            ) {
                \Log::error(
                    'Invalid AI JSON',
                    [
                        'response' => $content,
                    ]
                );

                return [];
            }

            return $filters;

        } catch (\Exception $e) {
            \Log::error(
                'OpenAI Exception',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return [];
        }
    }
}