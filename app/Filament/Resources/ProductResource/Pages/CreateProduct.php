<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Produk berhasil dibuat')
            ->success()
            ->send();
    }
    protected static string $resource = ProductResource::class;
}
