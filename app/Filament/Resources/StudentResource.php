<?php
namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers\CourseRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\PaymentRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\ResultRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\TransactionsRelationManager;
use App\Filament\Resources\StudentResource\Widgets\AttendenceOverview;
use App\Models\Nrc;
use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        $lastRegisterNo = Student::latest()->first();
        $lastNumber     = $lastRegisterNo ? (int) str_replace('RI-', '', $lastRegisterNo->register_no) : 499;
        $newRegisterNo  = 'RI-' . ($lastNumber + 1);

        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Student Information')
                        ->schema([
                            TextInput::make('name')
                                ->label('Enter Student Name')
                                ->required(),
                            DatePicker::make('dob')->label('Date of Birth')
                                ->required(),
                            TextInput::make('register_no')
                                ->default($newRegisterNo)
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            TextInput::make('phone')
                                ->label('Phone Number'),
                            TextInput::make('mail')
                                ->label('Mail Address')
                                ->email(),
                            TextInput::make('address')
                                ->label('Address'),

                            Select::make('gender')
                                ->label('Gender')
                                ->required()
                                ->options([
                                    'Male'   => 'Male',
                                    'Female' => 'Female',
                                ]),
                            Select::make('nationality')
                                ->options([
                                    'Kachin'  => 'Kachin',
                                    'Kayah'   => 'Kayah',
                                    'Kayin'   => 'Kayin',
                                    'Chin'    => 'Chin',
                                    'Burma'   => 'Burma',
                                    'Mon'     => 'Mon',
                                    'Rakhine' => 'Rakhine',
                                    'Shan'    => 'Shan',
                                ]),
                            Select::make('marital_status')
                                ->options([
                                    'Single'  => 'Single',
                                    'Married' => 'Married',
                                ]),

                            Fieldset::make('NRC Detail')
                            // ->relationship('nrcnos')
                                ->schema([
                                    Select::make('nrcs_id')
                                        ->label('Code')
                                        ->options([
                                            '1'  => 1,
                                            '2'  => 2,
                                            '3'  => 3,
                                            '4'  => 4,
                                            '5'  => 5,
                                            '6'  => 6,
                                            '7'  => 7,
                                            '8'  => 8,
                                            '9'  => 9,
                                            '10' => 10,
                                            '11' => 11,
                                            '12' => 12,
                                            '13' => 13,
                                            '14' => 14,
                                        ])
                                        ->live()
                                        ->required()
                                        ->searchable(),
                                    // ->afterStateUpdated(fn(Set $set, ?string $state) => $set('name_en', Nrc::select('name_en')->where('nrc_code', $state)->pluck('name_en'))),
                                    Select::make('nrcs_n')
                                        ->label('Distinct')
                                        ->required()
                                        ->searchable()
                                        ->reactive()
                                        ->options(function (Get $get) {
                                            return Nrc::where('nrc_code', $get('nrcs_id'))->pluck('name_en', 'id');
                                        }),
                                    Select::make('type')
                                        ->label('Type')
                                        ->required()
                                        ->options([
                                            'N' => 'N',
                                            'P' => 'P',
                                            'A' => 'A',
                                        ]),
                                    TextInput::make('nrc_num')
                                        ->label('Number')
                                        ->required()
                                        ->numeric()
                                        ->length(6)
                                        ->live(onBlur: true),
                                ])->columns(4)->columnSpanFull()->hiddenOn('view'),

                            FileUpload::make('avatar')
                                ->label('Student Profile Photo')
                                ->directory('images/student_avatars')
                                ->image()
                                ->imageEditor()
                                ->previewable()
                                ->downloadable()
                                ->columnSpanFull(),

                            TextInput::make('past_education')
                                ->label('Past Education'),
                            TextInput::make('past_qualification')
                                ->label('Past Qualification'),
                            ToggleButtons::make('graduated')
                                ->label('Graduated Or Undergraduated?')
                                ->live()
                                ->required()
                                ->boolean()
                                ->inline(),

                            FileUpload::make('approval_documents')
                                ->label('Approval Document')
                                ->hidden(fn(Get $get): bool => ! $get('graduated'))
                                ->previewable()
                                ->openable()
                                ->downloadable()
                                ->required()
                                ->multiple()
                                ->hint('Maximum 4 files can be upload')
                                ->maxFiles(4)
                                ->directory('images/approval_documents')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->columnSpanFull(),
                        ])->columns(3),

                    Wizard\Step::make('Family Information')
                        ->schema([
                            Repeater::make('familyMembers')
                                ->relationship()
                                ->schema([
                                    TextInput::make('name')->label('Name')->required(),
                                    TextInput::make('relationship')->label('Relations')->required(),
                                    TextInput::make('phone')->label('Phone Number')->required(),
                                    TextInput::make('address')->label('Address')->required(),
                                    TextInput::make('profession')->label('Profession'),
                                    TextInput::make('income')->numeric()->label('Income'),
                                ])->columns(3),
                        ]),
                ])->columnSpanFull(),
                //     ->submitAction(new HtmlString(Blade::render(
                //         <<<'BLADE'
                //     <x-filament::button
                //         type="submit"
                //         size="sm"
                //         style="cursor: pointer;"
                //     >
                //         Submit
                //     </x-filament::button>
                // BLADE
                //     ))),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),

                // PresentColumn::make('id')->label('Present  |  Leave  | Absent'),

                ImageColumn::make('avatar')
                    ->label('Profile')
                    ->circular()
                    ->alignCenter(),
                TextColumn::make('register_no')
                    ->label('Registeration No')
                    ->alignRight(),
                TextColumn::make('name')
                    ->label('Student Name')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('name', 'like', "%{$search}%");
                    }),

                TextColumn::make('nrc')
                    ->label('NRC')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date('Y-M-d')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nationality')
                    ->label('Nationality')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->alignRight(),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mail')
                    ->label('E-mail')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('marital_status')
                    ->label('Marital Status')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('admission_date')
                    ->label('Admission Date')
                    ->date('Y-M-d')
                    ->alignCenter(),
                TextColumn::make('address')
                    ->label('Address')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('past_education')
                    ->label('Past Education')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('past_qualification')
                    ->label('Past Qualification')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('current_job_position')
                    ->label('Current Job Position')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('graduated')
                    ->label('is graduated')
                    ->sortable()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter(),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->action(function ($record) {
                            if ($record->enrollments->count() > 0) {
                                Notification::make()
                                    ->danger()
                                    ->title('This can not be deleted!')
                                    ->body('You must delete all enrollments related to this student before proceeding.')
                                    ->send();

                                return;
                            }

                            $record->delete();

                            Notification::make()
                                ->success()
                                ->title('Deleted successfully!')
                                ->body('Student deleted successfully.')
                                ->send();
                        }),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->except(['No', 'avatar']),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CourseRelationManager::class,
            PaymentRelationManager::class,
            TransactionsRelationManager::class,
            ResultRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view'   => Pages\ViewStudent::route('/{record}'),
            'edit'   => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AttendenceOverview::class,
            // StudentOverview::class
        ];
    }
}