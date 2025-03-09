<?php
namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Student;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 9;

    public static function getModelLabel(): string
    {
        return 'certificate';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student Information')->schema([
                    Select::make('student_id')
                        ->relationship('student', 'name')
                        ->label('Select Student Name')
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required()
                        ->afterStateUpdated(function (Set $set, $state) {
                            $student = Student::find($state);
                            $set('registration_no', $student->register_no);
                            $set('batch_id', null);
                        }),
                    TextInput::make('registration_no')
                        ->label('Student Registration ID')
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('batch_id')
                        ->relationship('batch', 'code')
                        ->label('Select Batch Name')
                        ->searchable()
                        ->live()
                        ->preload()
                        ->required()
                        ->options(function (Get $get) {
                            $studentId        = $get('student_id');
                            $enrolledBatchIds = Enrollment::where('student_id', $studentId)->pluck('batch_id')->toArray();

                            return Batch::whereIn('id', $enrolledBatchIds)->pluck('code', 'id')->toArray();
                        })
                        ->columnSpanFull(),
                ])->columns(2),
                Toggle::make('generate_certificate')
                    ->live(),
                Repeater::make('certificates')
                    ->schema([
                        TextInput::make('title')
                            ->label('Certificate Title')
                            ->required(),
                        FileUpload::make('certificates')
                            ->label('Certificate')
                            ->previewable()
                            ->openable()
                            ->downloadable()
                            ->required()
                            ->directory('images/certificates')
                            ->acceptedFileTypes(['application/pdf', 'image/*']),
                    ])
                    ->live()
                    ->visible(fn(Get $get) => ! $get('generate_certificate'))
                    ->columnSpanFull(),

                Select::make('certificate_template_id')
                    ->relationship('certificateTemplate', 'template_name')
                    ->label('Select Template Name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->visible(fn(Get $get) => $get('generate_certificate'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('certificateID')
                    ->label('Certificate ID')
                    ->alignRight(),
                TextColumn::make('student.name'),
                TextColumn::make('batch.name')
                    ->label('Batch'),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index'  => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit'   => Pages\EditAchievement::route('/{record}/edit'),
            'view'   => Pages\ViewAchievement::route('/{record}'),
        ];
    }
}