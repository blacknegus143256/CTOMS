<?php

namespace App\Filament\Resources\AttributeCategories\Pages;

use App\Filament\Resources\AttributeCategories\AttributeCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttributeCategory extends EditRecord
{
    protected static string $resource = AttributeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
