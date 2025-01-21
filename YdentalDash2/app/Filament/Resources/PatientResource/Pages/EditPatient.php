<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
            ->successNotification(
                Notification::make()
        ->success()
        ->title('حذف مريض')
        ->body('تم حذف بيانات المريض بنجاح'),
            )
        ];
    }

    protected function getSavedNotification(): ?Notification
    {

        return Notification::make()
        ->success()
        ->title('تعديل مريض')
        ->body('تم تعديل بيانات المريض بنجاح');
    }
}
