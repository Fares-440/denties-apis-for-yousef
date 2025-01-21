<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function getCreatedNotification(): ?Notification
    {

        return Notification::make()
        ->success()
        ->title('إضافة طالب')
        ->body('تم إضافة الطالب بنجاح');
    }
}
