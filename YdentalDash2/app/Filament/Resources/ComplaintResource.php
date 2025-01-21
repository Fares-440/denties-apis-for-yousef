<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Filament\Resources\ComplaintResource\RelationManagers;
use App\Models\Complaint;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Components\Component;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'البلاغات';
    public static function getModelLabel(): string
    {
        return 'بلاغ'; // Replace with your desired singular label
    }
    public static function getPluralModelLabel(): string
    {
        return 'البلاغات';
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
                    ->label('اسم صاحب البلاغ')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->label('اسم المشتكى عليه')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('complaint_type')
                    ->options([
                        'شكوى' => 'شكوى',
                        'ملاحظة' =>    'ملاحظة'

                    ])
                    ->placeholder('اختر نوع البلاغ')
                    ->label('نوع البلاغ')
                    ->native(false)
                    ->required(),

                Forms\Components\TextInput::make('complaint_title')
                    ->label('عنوان البلاغ')

                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('complaint_desciption')
                    ->label('تفاصيل حول البلاغ')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('complaint_date')
                    ->label('تاريخ صدور البلاغ')
                    ->native(false)
                    ->displayFormat('d/m/y')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('اسم صاحب البلاغ'),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('اسم المشتكى عليه إن وجد'),

                Tables\Columns\TextColumn::make('complaint_type')
                    ->label('نوع البلاغ')

                    ->searchable(isGlobal: true),
                Tables\Columns\TextColumn::make('complaint_title')
                    ->label('عنوان البلاغ'),
                Tables\Columns\TextColumn::make('complaint_desciption')
                    ->label('وصف البلاغ')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('complaint_date')
                    ->label('تاريخ صدور البلاغ')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')

                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('complaint_type')
                    ->options([
                        'شكوى' => 'شكوى',
                        'ملاحظة' =>    'ملاحظة'

                    ])
                    ->native(false)
                    ->label('فلتره حسب نوع البلاغ')
            ])

            ->actions([
                Tables\Actions\ViewAction::make()
                ->label('عرض تفاصيل البلاغ'),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),


            ])
            ->searchable(false);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                ComponentsSection::make('عرض بيانات البلاغ  ')
                    ->schema([
                        ComponentsSection::make('')
                            ->schema([
                                TextEntry::make('patient.name')
                                    ->label('اسم صاحب البلاغ'),

                                TextEntry::make('student.name')
                                    ->label('اسم المشتكى عليه '),

                                TextEntry::make('complaint_type')
                                    ->label('نوع البلاغ '),

                                TextEntry::make('complaint_title')
                                    ->label('عنوان البلاغ'),


                            ])->columns(2),
                        ComponentsSection::make('تفاصيل حول البلاغ')
                            ->schema([

                                TextEntry::make('complaint_desciption')
                                    ->label('وصف البلاغ'),

                                TextEntry::make('complaint_date')
                                    ->label('تاريخ رفع البلاغ'),

                            ])->columns(2),

                    ])->columns(2)

            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'create' => Pages\CreateComplaint::route('/create'),
            'view' => Pages\ViewComplaint::route('/{record}'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }
}
