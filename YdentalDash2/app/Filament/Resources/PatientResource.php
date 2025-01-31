<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'المرضى';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    public static function getModelLabel(): string
    {
        return 'مريض'; // Replace with your desired singular label
    }

    // Override the getPluralModelLabel method for plural
    public static function getPluralModelLabel(): string
    {
        return 'المرضى'; // Replace with your desired plural label without 's'
    }


    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Section::make('بيانات المريض')
                    ->description('أدخل بيانات المريض في الحقول التاليه ')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم المريض ')

                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('email')
                            ->label(' البريد الألكتروني')

                            ->email()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => '  هذا :attribute تم تسجيله مسبفا.',
                            ])
                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('password')
                            ->label(' كلمة المرور')

                            ->password()
                            ->required()
                            ->maxLength(191),
                            Forms\Components\TextInput::make('confirmPassword')
                            ->label(' تأكيد كلمة المرور')
                            ->password()
                            ->same('password')
                            ->validationMessages([
                                'same' => 'كلمة المرور لا تتطابق مع كلمة المرور السابقة',
                            ])
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('رقم الهاتف ')
                            ->hint('+967')

                            ->tel()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => '   :attribute تم تسجيله مسبفا.',
                            ])
                            ->maxLength(10)
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('id_card')
                            ->label('رقم البطاقة المدنية ')
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => '   :attribute تم تسجيله مسبفا.',
                            ])
                            ->required()
                            ->maxLength(11),

                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('تاريخ الميلاد')
                            ->native(false)
                            ->displayFormat('d/m/y')
                            ->required(),
                            Forms\Components\TextInput::make('address')
                            ->label('عنوان المريض ')

                            ->required()
                            ->maxLength(191),
                            Forms\Components\Section::make('تحديد جنس المستخدم')
                            ->schema([
                                Forms\Components\Radio::make('gender')
                                ->label('الجنس')
                                ->options([
                                    'أنثى' => 'أنثى',
                                    'ذكر' => 'ذكر',

                                ])->inline()
                                ->inlineLabel(false)
                                ->required(),

                                Forms\Components\Radio::make('userType')
                                ->label('نوع المستخدم')
                                ->options([
                                    'طالب' => 'طالب',
                                    'مريض' => 'مريض',

                                ])->inline()
                                ->inlineLabel(false)

                                ->required(),

                                ])->columns(2),



                        Forms\Components\Section::make('تحديد حالة المستخدم')
                            ->schema([
                                Forms\Components\Select::make('isBlocked')
                                    ->label('حالة المستخدم')
                                    ->options(
                                        [
                                            'محظور' => 'محظور',
                                            'نشط' => 'نشط'

                                        ]
                                    )
                                    ->native(false)
                                    ->required(),

                            ])

                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(isGlobal: true)
                    ->label('اسم المريض '),

                Tables\Columns\TextColumn::make('email')
                    ->label(' البريد الألكتروني')
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('id_card')
                    ->label('رقم البطاقة المدنية ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('الجنس'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('تاريخ الميلاد')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('رقم الهاتف ')
                    ->numeric()
                    ->icon('heroicon-m-phone'),


                Tables\Columns\TextColumn::make('isBlocked')
                    ->label('حالة المستخدم')

                    ->color(fn(string $state): string => match ($state) {

                        'محظور' => 'danger',
                        'نشط' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'محظور' => 'heroicon-o-x-mark',
                        'نشط' => 'heroicon-o-check-badge',
                        default => 'heroicon-o-question-mark-circle',
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
                SelectFilter::make('isBlocked')
                ->options([

                    'محظور' => 'محظور',
                    'نشط' => 'نشط',
                ])
                ->native(false)

                ->label('فلترة حسب حالة المستخدم')

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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

                Section::make('بيانات المريض')
                    ->schema([
                        TextEntry::make('name')
                            ->label('اسم المريض '),
                        TextEntry::make('email')
                            ->label(' البريد الألكتروني')
                            ->icon('heroicon-m-envelope'),

                        TextEntry::make('id_card')
                            ->label('رقم البطاقة المدنية '),

                        TextEntry::make('gender')
                            ->label('الجنس'),

                        TextEntry::make('date_of_birth')
                            ->label('تاريخ الميلاد'),
                        TextEntry::make('phone_number')
                            ->label('رقم الهاتف ')
                            ->icon('heroicon-m-phone'),
                            Section::make('عنوان المريض  ')
                            ->schema([
                                TextEntry::make('address')
                                ->label(''),
                            ])

                    ])->columns(2),
                    Section::make('حالة المستخدم')
                    ->schema([
                        TextEntry::make('isBlocked')
                        ->label('حالة ونوع المستخدم'),

                    TextEntry::make('userType')
                    ->label('نوع المستخدم')

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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'view' => Pages\ViewPatient::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
