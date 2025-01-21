<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
            ->successNotification(
                Notification::make()
        ->success()
        ->title('حذف مستخدم')
        ->body('تم حذف بيانات المستخدم بنجاح'),
            )
        ];
    }

    protected function getSavedNotification(): ?Notification
    {

        return Notification::make()
        ->success()
        ->title('تعديل مستخدم')
        ->body('تم تعديل بيانات المستخدم بنجاح');
    }
}
