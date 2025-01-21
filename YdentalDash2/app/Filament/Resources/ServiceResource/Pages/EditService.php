<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
            ->successNotification(
                Notification::make()
        ->success()
        ->title('حذف خدمة')
        ->body('تم حذف بيانات الخدمة بنجاح'),
            )
        ];
    }

    protected function getSavedNotification(): ?Notification
    {

        return Notification::make()
        ->success()
        ->title('تعديل خدمة')
        ->body('تم تعديل بيانات الخدمة بنجاح');
    }
}
