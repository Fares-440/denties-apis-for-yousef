<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ThecaseRelationManager extends RelationManager
{
    protected static string $relationship = 'thecase';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('تفاصيل حول الحجز');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Edit fields for Thecase model
                Forms\Components\TextInput::make('procedure')
                    ->label('الإجراء')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gender')
                    ->label('الجنس')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cost')
                    ->label('التكلفة')
                    ->required(),
                // If you wish to edit schedules, you can use a repeater:
                Forms\Components\Repeater::make('schedules')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('available_date')
                            ->label('تاريخ الحجز')
                            ->required(),
                        Forms\Components\TextInput::make('available_time')
                            ->label('وقت الحجز')
                            ->required(),
                    ])
                    ->columns(2)
                    ->label('مواعيد الحجز'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('procedure')
            ->columns([
                Tables\Columns\TextColumn::make('gender')
                    ->label('الجنس'),
                Tables\Columns\TextColumn::make('procedure')
                    ->label('الإجراء'),
                Tables\Columns\TextColumn::make('cost')
                    ->label('التكلفة')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2)),
                // Display the first schedule's available_date
                Tables\Columns\TextColumn::make('schedules_first_date')
                    ->label('تاريخ الحجز')
                    ->getStateUsing(fn (Model $record) => optional($record->schedules->first())->available_date),
                // Display the first schedule's available_time
                Tables\Columns\TextColumn::make('schedules_first_time')
                    ->label('وقت الحجز')
                    ->getStateUsing(fn (Model $record) => optional($record->schedules->first())->available_time),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
