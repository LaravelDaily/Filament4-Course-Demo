<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ProductStatusEnum;
use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextInputColumn::make('name')
                    ->rules(['required', 'string', 'max:255', 'min:3']),
                TextColumn::make('price')
                    ->money('EUR', 100)
                    ->alignEnd()
                    // ->formatStateUsing(fn (int $state): float => $state / 100) 
                    ->sortable(),
                SelectColumn::make('status')
                    ->searchableOptions()
                    ->options(ProductStatusEnum::class),
                CheckboxColumn::make('is_active'),
                TextColumn::make('category.name')
                    ->label('Category name'),
                    // ->url(fn (Product $record): string => CategoryResource::getUrl('edit', ['record' => $record->category])),
                TextColumn::make('tags.name')
                    ->badge(),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('name', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(ProductStatusEnum::class),
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Filter::make('created_from')
                    ->schema([
                        DatePicker::make('created_from'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    }),
                Filter::make('created_until')
                    ->schema([
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)
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
