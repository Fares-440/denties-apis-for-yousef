<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Models\Appointment;
use App\Models\Thecase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use GPBMetadata\Google\Api\Label;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThecaseRelationManager extends RelationManager
{
    protected static string $relationship = 'thecase';
    // protected static ?string $recordTitleAttribute = 'الحالة';
    public static function getTitle( Model $ownerRecord, string $pageClass ): string {
        return __('تفاصيل حول الحجز');
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('schedules_id')
->relationship('schedules','available_date')

            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([

                Tables\Columns\TextColumn::make('gender')
                ->label('الجنس'),
                Tables\Columns\TextColumn::make('procedure')
                ->label('الإجراء'),

                Tables\Columns\TextColumn::make('cost')
                ->formatStateUsing(fn($state)=>'$'.number_format($state,2))
                ->label('التكلفة'),

                Tables\Columns\TextColumn::make('schedules.available_date')
->label('تاريخ الحجز'),

Tables\Columns\TextColumn::make('schedules.available_time')
->label('وقت الحجز')





            ])
            ->filters([
                //
            ])
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
