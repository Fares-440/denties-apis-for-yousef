<?php

namespace App\Filament\Resources;

use Filament\Infolists\Infolist;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\City;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

use function PHPSTORM_META\map;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'الطلاب';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';
    public static function getModelLabel(): string
    {
        return 'طالب'; // Replace with your desired singular label
    }
    public static function getPluralModelLabel(): string
    {
        return 'الطلاب';
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('تحديد المدينة والجامعة')
                    ->description('ملاحظة هامة : قم باختيار المدينة أولاً ثم الجامعة')
                    ->schema([
                        Forms\Components\Select::make('city_id')
                            ->label('المدينة')
                            ->placeholder('اختر المدينة')
                            ->relationship(name: 'city', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('university_id', null)),
                        Forms\Components\Select::make('university_id')

                            ->options(fn(Get $get): Collection => University::query()
                                ->where('city_id', $get('city_id'))
                                ->pluck('name', 'id'))
                            ->label('الجامعة')
                            ->placeholder('اختر الجامعة')
                            ->live()
                            ->required()
                            ->preload()
                            ->searchable(),
                    ])->columns(2),
                Forms\Components\Section::make('')
                    ->description('بيانات الطالب الشخصية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم الطالب ')

                            ->required()
                            ->maxLength(191),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الألكتروني')

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
                            ->maxLength(20),
                            Forms\Components\TextInput::make('confirmPassword')
                            ->label(' تأكيد كلمة المرور')
                            ->password()
                            ->same('password')
                            ->validationMessages([
                                'same' => 'كلمة المرور لا تتطابق مع كلمة المرور السابقة',
                            ])
                            ->required()
                            ->maxLength(20),
                        Forms\Components\Select::make('level')
                            ->options([
                                'الأول' => 'الأول',
                                'الثاني' => 'الثاني',
                                'الثالث' => 'الثالث',
                                'الرابع' => 'الرابع',
                                'الخامس' => 'الخامس',
                                'امتياز' => 'امتياز',
                                'ماجستير' => 'ماجستير',

                            ])
                            ->native(false)
                            ->placeholder('تحديد المستوى')
                            ->label('المستوى الدراسي ')

                            ->required(),
                            Forms\Components\Textarea::make('description')
                            ->label('وصف حول الطالب')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('phone_number')
                            ->label(' رقم الهاتف ')
                            ->tel()
                            ->hint('+967')
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => '  :attribute تم تسجيله مسبفا.',
                            ])
                            ->required()
                            ->maxLength(9)

                            ->numeric(),
                        Forms\Components\TextInput::make('university_card_number')
                        ->label(' الرقم الأكاديمي ')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => '   :attribute تم تسجيله مسبفا.',
                        ])
                            ->maxLength(12),

                            Forms\Components\FileUpload::make('university_card_image')
                            ->label('صورة البطاقة الأكاديمية')
                            ->disk('public') // Specify the disk
                            ->directory('images') // Specify the directory
                            ->preserveFilenames(),
                            Forms\Components\FileUpload::make('student_image')
                            ->label('صورة الطالب')
                            ->disk('public') // Specify the disk
                            ->directory('images') // Specify the directory
                            ->preserveFilenames()
                    ])->columns(4),


                Forms\Components\Section::make('')
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
                        ->placeholder('تحديد حالة المستخدم')
                            ->label('حالة المستخدم')
                            ->options(
                                [
                                    'محظور' => 'محظور',
                                    'نشط' => 'نشط'

                                ]
                            )
                            ->native(false)
                            ->required(),
                    ])->columns(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('student_image')
                ->label('صورة الظالب')
                ->disk('public')
                ->circular(),

                Tables\Columns\TextColumn::make('name')
                ->label('اسم الطالب')

                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                ->label(' البريد الإلكتروني')
                ->icon('heroicon-m-envelope')

                ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('city.name')
                ->label(' المدينة '),

                Tables\Columns\TextColumn::make('university.name')
                ->label(' الجامعة ')
                ->searchable(),


                Tables\Columns\TextColumn::make('gender')
                ->label(' الجنس ')

                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('level')
                ->label(' المستوى الدراسي ')

                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone_number')
                ->label(' رقم الهاتف ')
                ->icon('heroicon-m-phone')

                    ->numeric(),

                Tables\Columns\TextColumn::make('university_card_number')
                ->label(' الرقم الأكاديمي ')

                    ->searchable(),
                    Tables\Columns\ImageColumn::make('university_card_image')
                    ->label('صورة البطاقة الأكاديمية')
                    ->disk('public')
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->square(),

                Tables\Columns\TextColumn::make('isBlocked')
                    ->label('حالة المستخدم')

                    ->color(fn(string $state): string => match ($state) {

                        'محظور' => 'danger',
                        'نشط' => 'success',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'محظور' => 'heroicon-o-x-mark',
                        'نشط' => 'heroicon-o-check-badge',

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
                // SelectFilter::make('uni')
                // ->relationship(name:'university', titleAttribute:'name')
                // ->searchable()
                // ->preload()
                // ->label('تصفية حسب الجامعة'),
                // SelectFilter::make('isBlocked')
                //     ->options([

                //         'محظور' => 'محظور',
                //         'نشط' => 'نشط',
                //     ])
                //     ->native(false)

                    // ->label('فلترة حسب حالة المستخدم'),
                SelectFilter::make('city')
                    ->relationship(name: 'city', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->label('تصفية حسب المدينة')

            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('completedReport')

                    ->label(' تقرير بالطلاب المحجوبين ')->url(fn()=>route('blockedStudent.tes'))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info'),

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


                ComponentsSection::make('بيانات الطالب')


                    ->schema([

                        TextEntry::make('name')
                            ->label('اسم الطالب '),

                        TextEntry::make('email')
                            ->label(' البريد الألكتروني')
                            ->icon('heroicon-m-envelope'),

                        TextEntry::make('phone_number')
                            ->label(' رقم الهاتف ')
                            ->icon('heroicon-m-phone'),

                        TextEntry::make('level')
                            ->label(' المستوى الدراسي '),



                        TextEntry::make('university_card_number')
                        ->label(' الرقم الأكاديمي '),
                                            ])->columns(5),
                                            ComponentsSection::make('وصف عن الطالب')
                                            ->schema([
                                            TextEntry::make('description')
                                            ->label(''),
                                            ]),
                                            ComponentsSection::make('المدينة والجامعة')
                    ->schema([
                        TextEntry::make('city.name')
                            ->label(' المدينة '),

                        TextEntry::make('university.name')
                            ->label(' الجامعة '),
                    ])->columns(2),

                    ComponentsSection::make('حالة ونوع المستخدم')
                    ->schema([
                        TextEntry::make('isBlocked')
                            ->label('حالة المستخدم'),


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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
