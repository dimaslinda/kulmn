<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Filament\Notifications\Notification;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function afterCreate(): void
    {
        Notification::make()
            ->success()
            ->title('Layanan dibuat')
            ->body('Layanan telah berhasil dibuat.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
