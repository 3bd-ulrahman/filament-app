<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Tables\CategoriesTable;
use App\Models\Enums\ProductStatusEnum;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->unique(),

                TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),

                Select::make('status')
                    ->options(array_column(ProductStatusEnum::cases(), 'value', 'value'))
                    ->required(),

                ModalTableSelect::make('category_id')
                    ->relationship('category', 'name')
                    ->tableConfiguration(CategoriesTable::class)
                    ->required(),
            ]);
    }
}
