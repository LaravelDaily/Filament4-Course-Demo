<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    // protected string $view = 'filament.resources.products.pages.view-product'; 

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
