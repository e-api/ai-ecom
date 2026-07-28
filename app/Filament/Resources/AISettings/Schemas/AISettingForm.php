<?php

namespace App\Filament\Resources\AISettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class AISettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- API SETTINGS SECTION ---
                Section::make('API Settings')
                    ->schema([
                        Select::make('openai_model')
                            ->label('OpenAI Model')
                            ->options([
                                'gpt-4.1-mini' => 'GPT-4.1 Mini',
                                'gpt-4.1' => 'GPT-4.1',
                                'gpt-4o' => 'GPT-4o',
                                'gpt-4o-mini' => 'GPT-4o Mini',
                                'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                                'gpt-3.5-turbo-16k' => 'GPT-3.5 Turbo 16k',
                            ])
                            ->searchable()
                            ->required(),
                        TextInput::make('temperature')
                            ->label('Temperature')
                            ->numeric()
                            ->default(0.7)
                            ->required(),
                        TextInput::make('max_tokens')
                            ->label('Max Tokens')
                            ->numeric()
                            ->default(600)
                            ->required(),
                    ])
                    ->columns(3), // ✅ Columns is valid inside a Section

                // --- WRITING SETTINGS SECTION ---
                Section::make('Writing Settings')
                    ->schema([
                        Select::make('writing_tone')
                            ->label('Writing Tone')
                            ->options([
                                'Professional' => 'Professional',
                                'Casual' => 'Casual',
                                'Friendly' => 'Friendly',
                                'Luxurious' => 'Luxurious',
                                'Playful' => 'Playful',
                                'Romantic' => 'Romantic',
                                'Humorous' => 'Humorous',
                                'Inspirational' => 'Inspirational',
                                'Technical' => 'Technical',
                                'Conversational' => 'Conversational',
                                'Authoritative' => 'Authoritative',
                                'Empathetic' => 'Empathetic',
                                'Optimistic' => 'Optimistic',
                                'Pessimistic' => 'Pessimistic',
                                'Formal' => 'Formal',
                                'Informal' => 'Informal',
                                'Direct' => 'Direct',
                                'Indirect' => 'Indirect',
                                'Analytical' => 'Analytical',
                                'Narrative' => 'Narrative',
                                'Descriptive' => 'Descriptive',
                                'Expository' => 'Expository',
                                'Persuasive' => 'Persuasive',
                                'Informative' => 'Informative',
                            ])
                            ->default('Professional')
                            ->required(),
                        TextInput::make('description_length')
                            ->label('Description Length (words)')
                            ->numeric()
                            ->default(120)
                            ->required(),
                        TextInput::make('short_description_length')
                            ->label('Short Description Length (words)')
                            ->numeric()
                            ->default(40)
                            ->required(),
                        TextInput::make('keyword_count')
                            ->label('Keyword Count')
                            ->numeric()
                            ->default(10)
                            ->required(),
                    ])
                    ->columns(4), // ✅ Columns is valid inside a Section

                // --- ADVANCED PROMPT SECTION ---
                Section::make('Advanced Prompt')
                    ->description('This is an advanced feature. You can customize the system prompt to influence the AI\'s behavior. Use this with caution, as it may affect the quality of the generated content.')
                    ->schema([
                        Textarea::make('system_prompt')
                            ->label('System Prompt')
                            ->rows(8)
                            ->placeholder('Enter a custom system prompt to influence the AI\'s behavior.')
                            ->default("You are an expert e-commerce SEO content writer. Generate unique, engaging and SEO-friendly product content. Focus on customer benefits instead of technical specifications. Return only valid JSON without markdown.")
                            ->required(),
                    ]),
            ]);
    }
}