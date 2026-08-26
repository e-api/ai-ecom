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

    /*
    |--------------------------------------------------------------------------
    | Generate Product Highlights
    |--------------------------------------------------------------------------
    */
    public function generateProductHighlights(
        string $productDescription
    ): array {
        $settings = AISetting::first();
        $model = $settings?->openai_model ?? 'gpt-4.1-mini';
        $temperature = $settings?->temperature ?? 0.3;
        $maxTokens = 300;
        $systemPrompt = <<<PROMPT
    You are an expert e-commerce product content assistant.
    Your task is to extract the most important product features
    from the given product description.
    Rules:
    - Extract only information that is present in the product description.
    - Do not invent or assume any information.
    - Return 5 to 8 important product features.
    - Keep every feature short and clear.
    - Do not write paragraphs.
    - Do not add numbering.
    - Do not use Markdown.
    - Return ONLY valid JSON.
    Return exactly this JSON structure:
    {
        "key_features": [
            "Feature 1",
            "Feature 2",
            "Feature 3"
        ]
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
                            'content' =>
                                "Extract the key features from this product description:\n\n"
                                . $productDescription,
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
                    'key_features' => [],
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Decode JSON Response
            |--------------------------------------------------------------------------
            */
            $content = $response->json(
                'choices.0.message.content'
            );

            $result = json_decode(
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
                return [
                    'key_features' => [],
                ];
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error(
                'OpenAI Exception',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return [
                'key_features' => [],
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Product FAQs
    |--------------------------------------------------------------------------
    */

    public function generateProductFAQs(
        string $productDescription
    ): array {
        $settings = AISetting::first();

        $model = $settings->openai_model ?? 'gpt-4.1-mini';
        $temperature = $settings->temperature ?? 0.3;
        $maxTokens = 500;

        $systemPrompt = <<<PROMPT
    You are an expert e-commerce product content assistant.

    Your task is to generate useful Frequently Asked Questions from the given product description.

    Rules:
    - Use only information that is present in the product description.
    - Do not invent or assume any information.
    - Generate 5 to 8 useful questions and answers.
    - Keep questions simple and customer-friendly.
    - Keep answers short, clear, and informative.
    - Do not write information that is not available in the product description.
    - Do not use Markdown.
    - Return ONLY valid JSON.

    Return exactly this JSON structure:

    {
        "faqs": [
            {
                "question": "Question 1",
                "answer": "Answer 1"
            },
            {
                "question": "Question 2",
                "answer": "Answer 2"
            }
        ]
    }
    PROMPT;

        try {
            $response = Http::withToken(
                config('services.openai.key')
            )
            ->withOptions([
                'verify' => false,
            ])
            ->post(
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
                            'content' => "Generate FAQs from this product description:\n\n"
                                . $productDescription,
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
                    'faqs' => [],
                ];
            }

            /* 
            |--------------------------------------------------------------------------
            | Decode JSON Response 
            |--------------------------------------------------------------------------
            */
            $content = $response->json(
                'choices.0.message.content'
            );

            $result = json_decode(
                $content,
                true
            );

            if (
                json_last_error() !== JSON_ERROR_NONE
            ) {
                \Log::error(
                    'Invalid AI JSON',
                    [
                        'response' => $content,
                    ]
                );

                return [
                    'faqs' => [],
                ];
            }

            return $result;

        } catch (\Exception $e) {
            \Log::error(
                'OpenAI Exception',
                [
                    'message' => $e->getMessage(),
                ]
            );
            return [
                'faqs' => [],
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Product Specifications
    |--------------------------------------------------------------------------
    */

    public function generateProductSpecifications(
        string $productName,
        string $brand
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Supported Brands
        |--------------------------------------------------------------------------
        */
        $brand = strtolower(trim($brand));

        $officialWebsite = match ($brand) {
            'samsung' => 'samsung.com',
            'apple' => 'apple.com',
            default => null,
        };

        /*
        |--------------------------------------------------------------------------
        | Unsupported Brand
        |--------------------------------------------------------------------------
        */
        if (!$officialWebsite) {
            return [
                'specifications' => [],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | AI Settings
        |--------------------------------------------------------------------------
        */
        $settings = AISetting::first();

        $model = $settings->openai_model ?? 'gpt-4.1-mini';
        $maxTokens = 1000;

        /*
        |--------------------------------------------------------------------------
        | Prompt Engineering
        |--------------------------------------------------------------------------
        */
        $systemPrompt = <<<PROMPT
    You are an expert e-commerce product specification assistant.

    Your task is to find accurate specifications for the given product from the official manufacturer's website.

    Official Manufacturer Website:  
    {$officialWebsite}

    Rules:

    - You MUST use web search.  
    - Search the official manufacturer's website first.  
    - Use information from {$officialWebsite} only.  
    - Do not use third-party websites.  
    - Do not invent or assume any specifications.  
    - Only include specifications that are clearly available.  
    - Return useful and relevant product specifications.  
    - Keep specification names short and clear.  
    - Keep specification values accurate and concise.  
    - If reliable specifications cannot be found on the official website, return an empty specifications array.  
    - Return ONLY valid JSON.  
    - Do not use Markdown.  
    - Do not include any explanation outside the JSON.

    Return exactly this JSON structure:

    {
        "specifications": [
            {
                "name": "Display",
                "value": "6.2-inch Dynamic AMOLED 2X"
            },
            {
                "name": "Processor",
                "value": "Snapdragon 8 Gen 3"
            }
        ]
    }
    PROMPT;

        try {
            /*
            |--------------------------------------------------------------------------
            | OpenAI Responses API
            |--------------------------------------------------------------------------
            */
            $response = Http::withToken(
                config('services.openai.key')
            )
            ->withOptions([
                /*
                |--------------------------------------------------------------------------
                | Local WAMP SSL Certificate Fix
                |--------------------------------------------------------------------------
                */
                'verify' => false,
            ])->timeout(60)->post(
                'https://api.openai.com/v1/responses',
                [
                    'model' => $model,
                    /*
                    |--------------------------------------------------------------------------
                    | Web Search
                    |--------------------------------------------------------------------------
                    */
                    'tools' => [
                        [
                            'type' => 'web_search',
                            'search_context_size' => 'high',
                        ],
                    ],
                    /*
                    |--------------------------------------------------------------------------
                    | Force Tool Usage
                    |--------------------------------------------------------------------------
                    */
                    'tool_choice' => 'required',
                    /*
                    |--------------------------------------------------------------------------
                    | Input
                    |--------------------------------------------------------------------------
                    */
                    'input' => $systemPrompt
                        . "\n\n"
                        . "Product Name: "
                        . $productName
                        . "\n\n"
                        . "Manufacturer: "
                        . $brand
                        . "\n\n"
                        . "Official Website: "
                        . $officialWebsite,
                    'max_output_tokens' => $maxTokens,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | API Error Handling
            |--------------------------------------------------------------------------
            */
            if ($response->failed()) {
                \Log::error(
                    'OpenAI Product Specification API Error',
                    [
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]
                );

                return [
                    'specifications' => [],
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Get Output
            |--------------------------------------------------------------------------
            */
            $output = $response->json('output');
            $content = "";

            if (is_array($output)) {
                foreach ($output as $item) {
                    if (
                        ($item['type'] ?? '') === 'message'
                        &&
                        isset($item['content'])
                        &&
                        is_array($item['content'])
                    ) {
                        foreach ($item['content'] as $contentItem) {
                            if (
                                ($contentItem['type'] ?? '') === 'output_text'
                                && 
                                isset($contentItem['text'])
                            ) {
                                $content .= $contentItem['text'];
                            }
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Empty Response
            |--------------------------------------------------------------------------
            */
            if (empty($content)) {
                \Log::error(
                    'OpenAI Product Specification Empty Response',
                    [
                        'response' => $response->json(),
                    ]
                );

                return [
                    'specifications' => [],
                ];
            }

            /* 
            |--------------------------------------------------------------------------
            | Decode JSON
            |--------------------------------------------------------------------------
            */
            $result = json_decode(
                $content,
                true
            );

            /*
            |--------------------------------------------------------------------------
            | Validate JSON
            |--------------------------------------------------------------------------
            */
            if (
                json_last_error() !== JSON_ERROR_NONE
            ) {
                \Log::error(
                    'Invalid Product Specification JSON',
                    [
                        'response' => $content,
                        'json_error' => json_last_error_msg(),
                    ]
                );

                return [
                    'specifications' => [],
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Validate Specifications
            |--------------------------------------------------------------------------
            */
            if (
                !isset($result['specifications'])
                || !is_array($result['specifications'])
            ) {
                return [
                    'specifications' => [],
                ];
            }

            return [
                'specifications' => $result['specifications'],
            ];

        } catch (\Exception $e) {
            \Log::error(
                'OpenAI Product Specification Exception',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return [
                'specifications' => [],
            ];
        }
    }
}