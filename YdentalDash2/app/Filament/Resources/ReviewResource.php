<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'التقييمات';

    public static function getModelLabel(): string
    {
        return 'تقييم'; // Replace with your desired singular label
    }
    public static function getPluralModelLabel(): string
    {
        return 'التقييمات';
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
                Forms\Components\Select::make('rating')
                ->options([
                    1 => '1 ',
                    2 => '2 ',
                    3 => '3 ',
                    4 => '4 ',
                    5 => '5 ',

                ])
->native(false)
                    ->required(),
                Forms\Components\TextInput::make('comment')
                ->label('التعليق ')

                    ->required()
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                ->label('اسم المريض ')

                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                ->label('اسم الطالب ')

                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                ->label('التقييم من 5')
                ->formatStateUsing(function ($state) {
                    // Assuming $state is a numerical value out of 5
                    $stars = str_repeat('★', (int) $state); // Filled stars
                    $emptyStars = str_repeat('☆', 5 - (int) $state); // Empty stars
                    return $stars . $emptyStars ;
                })
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                ->label('التعليق ')

                    ->searchable(),
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
                Tables\Actions\ViewAction::make()->label('عرض تفاصيل التقييم'),
                // Tables\Actions\EditAction::make(),
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

                Section::make('عرض بيانات التقييم  ')
                    ->schema([
                        Section::make('')
                            ->schema([
                                TextEntry::make('patient.name')
                                    ->label('اسم المريض '),

                                TextEntry::make('student.name')
                                    ->label('اسم الطالب '),
                                TextEntry::make('rating')
                                ->label('التقييم من 5'),

                                TextEntry::make('comment')
                                ->label('التعليق '),


                            ])->columns(2),
                        Section::make('تفاصيل حول التقييم')
                            ->schema([


                                TextEntry::make('created_at')
                                    ->label('تاريخ ووقت اضافة التقييم'),

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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
