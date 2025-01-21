<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitResource\Pages;
use App\Filament\Resources\VisitResource\RelationManagers;
use App\Filament\Resources\VisitResource\RelationManagers\AppointmentRelationManager;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';
    protected static ?string $navigationlabel = 'الزيارات';
    public static function getModelLabel(): string
    {
        return 'زيارة'; // Replace with your desired singular label
    }
    public static function getPluralModelLabel(): string
    {
        return 'الزيارات';
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
         Section::make('بيانات الزيارة')
         ->schema([

            Forms\Components\Select::make('appointment_id')
            ->relationship('appointment','id')
            ->label('رقم الحجز')

            ->native(false)
                ->required(),
            Forms\Components\DatePicker::make('visit_date')
            ->native(false)
            ->label('تاريخ الزيارة ')

            ->displayFormat('d/m/y')
                ->required(),
            Forms\Components\TextInput::make('procedure')
                ->required()
                ->label('اجراء الزيارة ')

                ->maxLength(50),
                Section::make('تفاصيل حول الزيارة')
                ->schema([
                  Forms\Components\Textarea::make('note')
                  ->label('ملاحظة')
                           ->required()
                           ->columnSpanFull(),

                           Forms\Components\Select::make('status')
                           ->label('حالة الزيارة ')
                           ->options(
                               [
                                   'غير مكتملة' => 'غير مكتملة',
                                     'مكتملة' => 'مكتملة',
                                       'ملغية' => 'ملغية'

                               ])
                           ->native(false)

                               ->required(),
                ])
         ])->columns(3),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment_id')
                ->label('رقم الحجز')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visit_date')
                ->label('تاريخ الزيارة ')

                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedure')
                ->label('اجراء الزيارة ')

                    ->searchable(),
                    Tables\Columns\TextColumn::make('status')
                    ->label('حالة الزيارة ')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {

                        'غير مكتملة' => 'warning',
                        'مكتملة' => 'info',
                        'ملغية' => 'danger',

                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('تفاصيل الزيارة'),
                // Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                ComponentsSection::make('بيانات الزيارة')
                    ->schema([
                        TextEntry::make('appointment_id')
                        ->label('رقم الحجز'),
                           TextEntry::make('visit_date')
                           ->label('تاريخ الزيارة '),
                        TextEntry::make('procedure')
                        ->label('اجراء الزيارة '),
                        TextEntry::make('status')
                        ->label('حالة الزيارة ')
                        ->badge()
                        ->color(fn(string $state): string => match ($state) {

                            'غير مكتملة' => 'warning',
                            'مكتملة' => 'info',
                            'ملغية' => 'danger',

                        }),

                        ComponentsSection::make('ملاحظة حول الزيارة')
                        ->schema([
                            TextEntry::make('note')->label('')

                        ])

                    ])->columns(4),




            ]);
    }

    public static function getRelations(): array
    {
        return [
            AppointmentRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
            'view' => Pages\ViewVisit::route('/{record}'),
            'edit' => Pages\EditVisit::route('/{record}/edit'),
        ];
    }
}
