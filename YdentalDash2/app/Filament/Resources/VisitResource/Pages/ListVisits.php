<?php

namespace App\Filament\Resources\VisitResource\Pages;

use App\Filament\Resources\VisitResource;
use App\Models\Visit;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    public function getTabs(): array
    {
        return [
            'الكل' => Tab::make(),
            'غير مكتملة' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'غير مكتملة'))
            ->badge(Visit::query()->where('status', 'غير مكتملة')->count())
            ->badgeColor('warning'),

            'مكتملة' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'مكتملة'))
            ->badge(Visit::query()->where('status', 'مكتملة')->count())
            ->badgeColor('success'),

            'ملغية' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'ملغية'))
            ->badge(Visit::query()->where('status', 'ملغية')->count())
            ->badgeColor('danger'),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
