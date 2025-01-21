<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Actions;
use Filament\Forms\Components\Actions as FormsComponentsActions;
use Filament\Infolists\Components\Actions as ComponentsActions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    public function getTabs(): array
    {
        return [
            'الكل' => Tab::make(),
            'بانتظار التأكيد' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'بانتظار التأكيد'))
            ->badge(Appointment::query()->where('status', 'بانتظار التأكيد')->count())
            ->badgeColor('warning'),

            'مؤكد' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'مؤكد'))
            ->badge(Appointment::query()->where('status', 'مؤكد')->count())
            ->badgeColor('success'),
            'مكتمل' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'مكتمل'))
            ->badge(Appointment::query()->where('status', 'مكتمل')->count())
            ->badgeColor('info'),
            'ملغي' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'ملغي'))
            ->badge(Appointment::query()->where('status', 'ملغي')->count())
            ->badgeColor('danger'),
        ];
    }


    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),

        ];
    }
}
