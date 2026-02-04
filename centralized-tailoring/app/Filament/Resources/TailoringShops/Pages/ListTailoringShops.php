<?php

namespace App\Filament\Resources\TailoringShops\Pages;

use App\Filament\Resources\TailoringShops\TailoringShopResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTailoringShops extends ListRecords
{
    protected static string $resource = TailoringShopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
