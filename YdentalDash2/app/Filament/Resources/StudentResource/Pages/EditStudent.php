<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
            ->successNotification(
                Notification::make()
        ->success()
        ->title('حذف طالب')
        ->body('تم حذف بيانات الطالب بنجاح'),
            )
        ];
    }


    protected function getSavedNotification(): ?Notification
    {

        return Notification::make()
        ->success()
        ->title('تعديل طالب')
        ->body('تم تعديل بيانات الطالب بنجاح');
    }
}
