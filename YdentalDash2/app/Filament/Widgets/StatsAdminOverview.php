<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Student;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {

            return [

            Stat::make('الطلاب', Student::query()->count())
                ->description('عدد  الطلاب المنضمين للنظام')

                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->icon('heroicon-m-arrow-trending-up')
                ->color('primary'),
                Stat::make('المرضى',Patient::query()->count())
                ->description('عدد  المرضى المستخدمين للنظام')
                ->icon('heroicon-m-arrow-trending-up')

                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('primary'),
                Stat::make('مستخدمي النظام',User::query()->count())
                ->description('عدد مستخدمي النظام  ')
                ->chart([7, 2, 15, 4, 17, 4, 17])
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('primary'),

            Stat::make('الحجوزات المكتملة',Appointment::where('status', Appointment::STATUS_COMPLETED)->count())
                ->description('عدد الحجوزات المكتملة  ')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-o-calendar-date-range')

                ->color('success'),
                Stat::make('الحجوزات الملغية',Appointment::where('status', Appointment::STATUS_CANCELLED)->count())
                ->description('عدد الحجوزات الملغية  ')
                ->chart([9, 3, 17, 3, 15, 7, 17])
                ->descriptionIcon('heroicon-o-calendar-date-range')

                ->color('danger'),
                Stat::make('الحجوزات قيد الانتظار',Appointment::where('status', Appointment::STATUS_UPCOMING)->count())
                ->description('عدد الحجوزات قيد الانتظار  ')
                ->icon('heroicon-m-arrow-trending-up')

                ->chart([1, 2, 17, 3, 15, 7, 17])
                ->descriptionIcon('heroicon-o-calendar-date-range')

                ->color('warning'),

            ];

    }
}
