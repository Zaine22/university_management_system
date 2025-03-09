<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\Widgets\AttendanceOverview;
use App\Models\Employee;
use App\Models\Nrc;
use App\Models\Teacher;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {

        $lastTeacherId = Teacher::latest()->first();
        $lastNumber    = $lastTeacherId ? (int) str_replace('RIT-', '', $lastTeacherId->teacherID) : 499;
        $newTeacherId  = 'RIT-' . ($lastNumber + 1);

        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Teacher Information')
                        ->schema([

                            Section::make([
                                Select::make('employee_id')
                                    ->relationship('employee', 'name')
                                    ->label('employee name')
                                    ->required()
                                    ->live()
                                    ->label('Select Employee ID')
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        $employee = Employee::find($state);
                                        if ($employee) {
                                            $set('name', $employee->name);
                                            $set('mail', $employee->mail);
                                        }
                                    }),

                                TextInput::make('name')
                                    ->label('Teacher Name')
                                    ->required()
                                    ->live()
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('mail')
                                    ->label('Email')
                                    ->email()
                                    ->live()
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('teacherID')
                                    ->label('Teacher ID')
                                    ->default($newTeacherId)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ])->columns(4),

                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->required(),

                            DatePicker::make('dob')
                                ->label('Date Of Birth')
                                ->required(),

                            DatePicker::make('join_date')
                                ->label('Joining Date')
                                ->required()
                                ->default(Carbon::now()),

                            TextInput::make('salary')
                                ->label('Salary')
                                ->numeric()
                                ->minLength(6)
                                ->required(),

                            Fieldset::make('NRC Number')
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
                                        ->searchable()
                                        ->required(),
                                    // ->afterStateUpdated(fn(Set $set, ?string $state) => $set('name_en', Nrc::select('name_en')->where('nrc_code', ++$state)->pluck('name_en'))),
                                    Select::make('nrcs_n')
                                        ->label('Distinct')
                                        ->required()
                                        ->searchable()
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
                                ])
                                ->required(),
                            Select::make('marital_status')
                                ->options([
                                    'Single'  => 'Single',
                                    'Married' => 'Married',
                                ])
                                ->required(),
                            TextInput::make('education')
                                ->label('Education')
                                ->required(),
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
                ])
                    ->columnSpanFull(),
                // ->submitAction(new HtmlString(Blade::render(
                //     <<<'BLADE'
                // <x-filament::button
                //     type="submit"
                //     size="sm"
                // >
                //     Submit
                // </x-filament::button>
                // BLADE
                // ))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                ImageColumn::make('employee.avatar')
                    ->circular()
                    ->alignCenter()
                    ->label('Profile'),
                TextColumn::make('teacherID')
                    ->label('Teacher ID')
                    ->alignRight(),
                TextColumn::make('name')
                    ->label('Teacher Name')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('name', 'like', "%{$search}%");
                    }),
                TextColumn::make('salary')
                    ->label('Salary')
                    ->money('MMK')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignRight(),

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
                TextColumn::make('mail')
                    ->label('E-mail')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('marital_status')
                    ->label('Marital Status')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('join_date')
                    ->label('Joining Date')
                    ->date('Y-M-d')
                    ->toggleable()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('education')
                    ->label('Education')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make('table')
                            ->fromTable()
                            ->except(['No']),
                    ]),
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
            'index'  => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'view'   => Pages\ViewTeacher::route('/{record}'),
            'edit'   => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AttendanceOverview::class,
        ];
    }
}