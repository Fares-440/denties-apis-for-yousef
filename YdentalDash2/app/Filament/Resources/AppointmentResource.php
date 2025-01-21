<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Filament\Resources\AppointmentResource\RelationManagers\ThecaseRelationManager;
use App\Http\Controllers\ReportController;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use GPBMetadata\Google\Api\Label;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static ?string $navigationLabel = 'الحجوزات';

    public static function getModelLabel(): string
    {
        return 'حجز'; // Replace with your desired singular label
    }
    public static function getPluralModelLabel(): string
    {
        return 'الحجوزات';
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->label('اسم المريض')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->label('اسم الطالب')

                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('thecase_id')
                    ->relationship('thecase', 'id')
                    ->label('رقم الحالة ')

                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('حالة الحجز ')
                    ->options(
                        [
                            'بانتظار التأكيد' => 'بانتظار التأكيد',
                            'مؤكد' => 'مؤكد',
                            'مكتمل' => 'مكتمل',
                            'ملغي' => 'ملغي'

                        ]
                    )
                    ->native(false)

                    ->required(),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('اسم المريض')

                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('اسم الطالب')

                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('thecase.id')
                    ->label('رقم الحالة')

                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('حالة الحجز ')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {

                        'بانتظار التأكيد' => 'warning',
                        'مؤكد' => 'success',
                        'مكتمل' => 'info',
                        'ملغي' => 'danger',
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
                // SelectFilter::make('status')
                //     ->options([
                //         'بانتظار التأكيد' => 'بانتظار التأكيد',
                //         'مؤكد' => 'مؤكد',
                //         'مكتمل' => 'مكتمل',
                //         'ملغي' => 'ملغي',

                //     ])
                //     ->attribute('status'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('تحميل pdf')
                    ->url(fn() => route('download.tes', request()->query())) // Pass current filters
                    ->openUrlInNewTab()
                    // ->label('تقرير بالحجوزات ')
                    // ->url(fn () => route('download.tes', [
                    //     'status' => request()->query('status') // Pass the current filter status to the URL
                    // ]))
                    // $service->generateBlockedUsersPDF();

                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info'),
            ])
            // ->query(Appointment::with('thecase.schedules'))
            ->actions([
                Tables\Actions\ViewAction::make()->label('تفاصيل الحجز'),
                Tables\Actions\Action::make('download_pdf')
                    ->label('pdf')
                    ->url(fn ($record) => route('downloadReport', $record->id)) // Pass the appointment ID
                    ->icon('heroicon-o-document')
                    ->color('info'),
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

                ComponentsSection::make('بيانات الحجز')
                    ->schema([
                        TextEntry::make('patient.name')
                            ->label('اسم المريض '),
                        TextEntry::make('student.name')
                            ->label('  اسم الطالب'),
                        TextEntry::make('thecase.id')
                            ->label('رقم  الحالة '),

                        TextEntry::make('status')
                            ->label('حالة الحجز')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {

                                'بانتظار التأكيد' => 'warning',
                                'مؤكد' => 'success',
                                'مكتمل' => 'info',
                                'ملغي' => 'danger',
                            }),
                    ])->columns(4),




            ]);
    }
    public static function getRelations(): array
    {
        return [
            ThecaseRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            // 'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
