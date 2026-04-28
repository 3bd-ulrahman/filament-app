<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),

                TextColumn::make('product.name')
                    ->searchable(),

                TextColumn::make('price')
                    ->money()
                    ->summarize(Sum::make()->money(divideBy: 100))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('product.name')
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('Mark as completed')
                        ->requiresConfirmation()
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->hidden(fn (Order $order) => $order->is_completed)
                        ->action(fn (Order $order) => $order->update(['is_completed' => true]))
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    BulkAction::make('Mark as completed')
                        ->requiresConfirmation()
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->action(fn (array $records) => Order::whereIn('id', $records)->update(['is_completed' => true]))
                        ->deselectRecordsAfterCompletion()
                ])
            ]);
    }
}
