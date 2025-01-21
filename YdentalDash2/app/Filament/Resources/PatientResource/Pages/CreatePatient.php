<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;


    protected function getCreatedNotification(): ?Notification
    {

        return Notification::make()
        ->success()
        ->title('إضافة مريض')
        ->body('تم إضافة المريض بنجاح');
    }
}
