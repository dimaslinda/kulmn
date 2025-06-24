<?php

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Resources\BranchResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBranch extends CreateRecord
{
    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Cabang berhasil dibuat')
            ->success()
            ->send();
    }
    protected static string $resource = BranchResource::class;
}
