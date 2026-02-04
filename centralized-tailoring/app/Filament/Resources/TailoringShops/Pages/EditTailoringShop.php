<?php

namespace App\Filament\Resources\TailoringShops\Pages;

use App\Filament\Resources\TailoringShops\TailoringShopResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTailoringShop extends EditRecord
{
    protected static string $resource = TailoringShopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
