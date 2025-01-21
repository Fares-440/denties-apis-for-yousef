<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Arr;

class LatestStudents extends BaseWidget
{
    protected static ?string $heading = 'تم انضمامه مؤخراً ';
    protected static ?int $sort=4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query())
            ->defaultSort('created_at','desc')



            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم الطالب'),
                Tables\Columns\TextColumn::make('email')->label('البريد الالكتروني')
                ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('phone_number')->label('رقم الهاتف')
                ->icon('heroicon-m-phone')


            ]);
    }
}
