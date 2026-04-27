<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Enums\ProductStatusEnum;
use Closure;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: false),

                TextColumn::make('price')->label('Price')
                    ->money('usd', 100)
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                SelectColumn::make('status')
                    ->options(ProductStatusEnum::class),

                TextColumn::make('category.name'),

                TextColumn::make('tags.name')->badge()
                    ->limitList(3)
                    ->tooltip(fn ($record) => $record->tags->pluck('name')->join(', ')),

                TextColumn::make('created_at')
                    ->since(),
            ])
            ->filters([
                                SelectFilter::make('status')->options(ProductStatusEnum::class),
                                SelectFilter::make('category')->relationship('category', 'name'),
                                Filter::make('created_from')
                                    ->schema([
                                        DatePicker::make('created_from')->label('Created From'),
                                    ])
                                    ->query(function (Builder $query, array $data) {
                                        $query->when($data['created_from'], function ($query, $date) {
                                            $query->whereDate('created_at', '>=', $date);
                                        });
                                    }),

                                Filter::make('created_until')
                                    ->schema([
                                        DatePicker::make('created_until')->label('Created Until'),
                                    ])
                                    ->query(function (Builder $query, array $data) {
                                        $query->when($data['created_until'], function ($query, $date) {
                                            $query->whereDate('created_at', '<=', $date);
                                        });
                                    }),
                            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
