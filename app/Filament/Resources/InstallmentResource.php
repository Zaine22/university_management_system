<?php
namespace App\Filament\Resources;

use App\Filament\Resources\InstallmentResource\Pages;
use App\Models\Course;
use App\Models\Installment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InstallmentResource extends Resource
{
    protected static ?string $model = Installment::class;

    protected static ?string $inverseRelationship = 'section';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('course_id')
                    ->label('Course')
                    ->options(Course::getInstallableCourses())
                    ->label('Select Course')
                    ->live()
                    ->afterStateUpdated(fn($state, callable $set) => $set('price', Course::find($state)?->price ?? ''))
                    ->reactive()
                    ->required(),

                TextInput::make('price')
                    ->label('Price')
                    ->placeholder(fn(Get $get) => Course::find($get('course_id'))?->price ?? 'Select a course')
                    ->disabled(),

                TextInput::make('name')
                    ->label('Enter Installment Plan Name')
                    ->required(),
                Section::make('Plan Details')
                    ->schema([
                        TextInput::make('installment_price')
                            ->label('Course Installation Price')
                            ->integer()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('down_payment')
                            ->label('Course Down Payment Price')
                            ->minValue(0)
                            ->integer()
                            ->required(),
                        TextInput::make('months')
                            ->integer()
                            ->minValue(0)
                            ->required()
                            ->label('Total Installment Months'),
                        TextInput::make('monthly_payment_amount')
                            ->integer()
                            ->minValue(0)
                            ->required()
                            ->label('Monthly Payment Amount'),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('course.name')
                    ->label('Course Name'),
                TextColumn::make('name')
                    ->label('Installment Plan Name'),
                TextColumn::make('installment_price')
                    ->label('Course Installation Price'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ])
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
            'index'  => Pages\ListInstallments::route('/'),
            'create' => Pages\CreateInstallment::route('/create'),
            'view'   => Pages\ViewInstallment::route('/{record}'),
            'edit'   => Pages\EditInstallment::route('/{record}/edit'),
        ];
    }
}