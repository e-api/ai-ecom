<?php

namespace App\Filament\Resources\AISettings;

use App\Filament\Resources\AISettings\Pages\CreateAISetting;
use App\Filament\Resources\AISettings\Pages\EditAISetting;
use App\Filament\Resources\AISettings\Pages\ListAISettings;
use App\Filament\Resources\AISettings\Schemas\AISettingForm;
use App\Filament\Resources\AISettings\Tables\AISettingsTable;
use App\Models\AISetting;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AISettingResource extends Resource
{
    protected static ?string $model = AISetting::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Cog6Tooth; // Remove 'Solid' prefix

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'AI Settings';
    protected static ?string $modelLabel = 'AI Setting';
    protected static ?string $pluralModelLabel = 'AI Settings';

    public static function form(Schema $schema): Schema
    {
        return AISettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AISettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAISettings::route('/'),
            'create' => CreateAISetting::route('/create'),
            'edit' => EditAISetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();
        
        return match(true) {
            $count === 0 => 'danger',
            $count < 1 => 'danger',
            $count < 3 => 'warning',
            $count < 8 => 'success',
            default => 'gray',
        };
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return static::getModel()::count() . ' AI models available';
    }
}
