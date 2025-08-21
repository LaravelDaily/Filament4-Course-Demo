<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('EUR', 100)
                    ->summarize(Sum::make()->money('EUR', 100))
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
                // ActionGroup::make([
                    EditAction::make(),
                    Action::make('Mark as completed')
                        ->requiresConfirmation()
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->hidden(fn (Order $record) => $record->is_completed)
                        ->action(fn (Order $record) => $record->update(['is_completed' => true])), 
                    // Action::make('Change is completed')
                    //     ->icon(Heroicon::OutlinedCheckBadge)
                    //     ->fillForm(function (Order $order) {
                    //         return ['is_completed' => $order->is_completed];
                    //     })
                    //     ->schema([
                    //         Checkbox::make('is_completed'),
                    //     ])
                    //     ->action(function (array $data, Order $order): void {
                    //         $order->update(['is_completed' => $data['is_completed']]);
                    //     }),
                // ]),
            ])
            ->headerActions([
                Action::make('New Order')
                    ->url(fn (): string => OrderResource::getUrl('create')),            
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('Mark as Completed') 
                        ->icon(Heroicon::OutlinedCheckBadge)
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['is_completed' => true]))
                        ->deselectRecordsAfterCompletion(), 
                ]),
            ]);
    }
}
