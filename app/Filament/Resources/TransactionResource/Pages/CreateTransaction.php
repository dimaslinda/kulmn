<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Transaksi berhasil dibuat')
            ->success()
            ->send();
    }
    protected static string $resource = TransactionResource::class;
}
