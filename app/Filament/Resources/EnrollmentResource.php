<?php
namespace App\Filament\Resources;

use App\Enums\PaymentMethod;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Installment;
use App\Models\Student;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        $filteredPaymentMethods = array_filter(
            PaymentMethod::cases(),
            fn($method) => $method !== PaymentMethod::TwoPaymentMethod
        );

        return $form
            ->schema([
                Section::make('Enrollment')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Select::make('student_id')
                                    ->relationship('students', 'name')
                                    ->label('Select Student Name')
                                    ->searchable()
                                    ->disabledOn('edit')
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
                                    ->label('Select Batch Name')
                                    ->searchable()
                                // ->live()
                                    ->preload()
                                    ->reactive()
                                    ->required()
                                    ->options(function (Get $get) {
                                        $studentId = $get('student_id');
                                        if ($studentId) {
                                            $enrolledBatchIds = Enrollment::where('student_id', $studentId)->pluck('batch_id')->toArray();

                                            return Batch::whereNotIn('id', $enrolledBatchIds)->pluck('name', 'id')->toArray();
                                        } else {
                                            return Batch::pluck('name', 'id')->toArray();
                                        }
                                    })
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        $batch = Batch::find($state);
                                        $set('enrollment_payment_amount', $batch->course->price);
                                        $set('total_payment_amount', $batch->course->price);
                                    }),
                            ])->columns(3),

                        Section::make('Payment')
                            ->schema([
                                Toggle::make('has_installment_plan')
                                    ->visible(function (Get $get) {
                                        $batchId = $get('batch_id');
                                        $batch   = Batch::find($batchId);
                                        if (! $batch) {
                                            return false;
                                        }
                                        if (! $batch->course) {
                                            return false;
                                        }

                                        return $batch->course->installable;
                                    })
                                    ->inline()
                                    ->live()
                                    ->reactive()
                                    ->columnSpanFull()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        $batchId = $get('batch_id');
                                        $batch   = Batch::find($batchId);
                                        if ($state) {
                                            $set('enrollment_payment_amount', $batch->course->installment_price);
                                            $set('total_payment_amount', $batch->course->installment_price);
                                        } else {
                                            $set('enrollment_payment_amount', $batch->course->price);
                                            $set('total_payment_amount', $batch->course->price);
                                        }
                                    })
                                    ->label('Installable'),
                                Select::make('installment_id')
                                    ->label('Select Installment Plan')
                                    ->live()
                                    ->reactive()
                                    ->hidden(fn(Get $get): bool => ! $get('has_installment_plan'))
                                    ->options(function (Get $get) {
                                        $batchId = $get('batch_id');
                                        $batch   = Batch::find($batchId);

                                        return $batch?->course?->installments?->pluck('name', 'id') ?? [];
                                    })
                                    ->afterStateUpdated(function (Set $set, $state) {

                                        $installment = Installment::find($state);

                                        if ($installment) {
                                            $set('enrollment_payment_amount', $installment->installment_price);
                                            $set('down_payment', $installment->downpayment);
                                            $set('total_payment_amount', $installment->installment_price);
                                        }
                                    }),

                                Placeholder::make('down_payment')
                                    ->reactive()
                                    ->content(function (Get $get) {
                                        $batchId        = $get('batch_id');
                                        $installment_id = $get('installment_id');
                                        $installment    = Installment::find($installment_id);

                                        $batch = Batch::find($batchId);
                                        if (! $batch) {
                                            // Handle the case where the batch is not found
                                            return ' ';
                                        }
                                        if (! $get('installment_id')) {
                                            return $batch->course->price . ' MMK';
                                        }

                                        return $installment->down_payment . ' MMK';
                                    }),
                                TextInput::make('enrollment_payment_amount')
                                    ->suffix('MMK')
                                    ->readOnly(),

                                TextInput::make('discount_percentage')
                                    ->debounce(100)
                                    ->label('Discount Percentage')
                                    ->suffix('%')
                                    ->numeric()
                                    ->reactive()
                                    ->maxValue(50)
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, $state, Set $set) {
                                        $installmentAmount = $get('enrollment_payment_amount'); // Adjust this if it points to the right field
                                        $set('discounted_payment_amount', number_format($installmentAmount));
                                        $set('additional_discount_amount', null);

                                        if ($state !== null) {
                                            $discountedAmount = $installmentAmount - ($installmentAmount * $state / 100);

                                            $total_discount_amount = $installmentAmount - $discountedAmount;
                                            $set('total_discount_amount', $total_discount_amount);

                                            $set('discounted_payment_amount', $discountedAmount);
                                            $set('total_payment_amount', $discountedAmount);
                                        }
                                    }),

                                TextInput::make('discounted_payment_amount')
                                    ->suffix('MMK')
                                    ->readOnly(),

                                TextInput::make('additional_discount_amount')
                                    ->debounce(500)
                                    ->label('Additional Discount Amount')
                                    ->live()
                                    ->numeric()
                                    ->minValue(0)
                                    ->reactive()
                                    ->suffix('MMK')
                                    ->afterStateUpdated(function (Get $get, $state, Set $set) {
                                        $amount         = $get('discounted_payment_amount');
                                        $payment_amount = $get('enrollment_payment_amount');

                                        $discountePercentage = $get('discount_percentage');
                                        // Ensure $amount and $state are numeric
                                        $amount = (int) $amount;
                                        $state  = (int) $state;
                                        $set('total_payment_amount', $amount);

                                        if ($state) {
                                            if ($discountePercentage) {
                                                $total_payment_amount  = $amount - $state;
                                                $total_discount_amount = $payment_amount - $total_payment_amount;
                                                $set('total_discount_amount', $total_discount_amount);
                                                $set('total_payment_amount', $total_payment_amount);
                                            } else {
                                                $total_payment_amount  = $payment_amount - $state;
                                                $total_discount_amount = $payment_amount - $total_payment_amount;
                                                $set('total_discount_amount', $total_discount_amount);
                                                $set('total_payment_amount', $total_payment_amount);
                                            }
                                        }
                                    }),
                                TextInput::make('total_payment_amount')
                                    ->suffix('MMK')
                                    ->readOnly(),
                                TextInput::make('total_discount_amount')
                                    ->label('Total Discount Amount')
                                    ->suffix('MMK')
                                    ->default(0)
                                    ->readOnly(),
                            ])->columns(2),

                    ])->columns(2),

                Section::make()
                    ->relationship('payment')
                    ->schema([
                        Repeater::make('Transaction')
                            ->disableItemDeletion()
                            ->relationship('transactions')
                            ->disableItemCreation()
                            ->schema([
                                Select::make('payment_method')
                                    ->options(PaymentMethod::class)
                                    ->label('Payment Method')
                                    ->reactive() // Make it reactive to listen for changes
                                    ->afterStateUpdated(fn(callable $set) => $set('transaction_proof', null)),

                                Section::make('Two Payment Methods')
                                    ->description('Prevent abuse by limiting the number of requests per period')
                                    ->visible(fn(callable $get) => $get('payment_method') === PaymentMethod::TwoPaymentMethod->value)
                                    ->schema([
                                        Select::make('payment_method_1')
                                            ->options(PaymentMethod::optionsIncludingCash())
                                            ->required()
                                            ->label('Payment Method 1')
                                            ->reactive() // Make it reactive to listen for changes
                                            ->afterStateUpdated(fn(callable $set) => $set('transaction_proof', null)),
                                        TextInput::make('first_payment_method_amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->required()
                                            ->reactive()
                                            ->debounce(500)
                                            ->afterStateUpdated(function (Get $get, $state, Set $set) {
                                                $batchId = $get('../../../batch_id');
                                                if ($get('../../../installment_id')) {
                                                    $price = Installment::find($get('../../../installment_id'))->down_payment;

                                                    $downpayment = $price - $get('../../../total_discount_amount');

                                                } else {
                                                    $price       = Batch::find($get('../../../batch_id'))->course->price;
                                                    $downpayment = $price - $get('../../../total_discount_amount');

                                                }

                                                if (! $batchId) {
                                                    // Handle the case where the batch is not found
                                                    $set('second_payment_method_amount', 1000);

                                                    return;
                                                }
                                                $batch = Batch::find($batchId);
                                                if (! $batch) {
                                                    // Handle the case where the batch is not found
                                                    $set('second_payment_method_amount', 111);

                                                    return;
                                                }

                                                $coursePrice = $batch->course->price;

                                                if (! $get('../../../has_installment_plan')) {
                                                    $set('second_payment_method_amount', $coursePrice - $state);
                                                } else {

                                                    $set('second_payment_method_amount', $downpayment - $state);
                                                }
                                                // $set('second_payment_method_amount', $initialPaymentAmount);
                                            }),
                                        FileUpload::make('first_transaction_proof')
                                            ->label('Transaction Screenshot')
                                            ->image()
                                            ->imageEditor()
                                            ->previewable()
                                            ->downloadable()
                                            ->deletable(false)
                                            ->columnSpanFull()
                                            ->directory('images/transactions')
                                            ->required(fn(callable $get) => $get('payment_method_1') !== PaymentMethod::Cash->value) // Make the field required for non-Cash methods
                                            ->visible(fn(callable $get) => $get('payment_method_1') !== PaymentMethod::Cash->value),
                                        Select::make('payment_method_2')
                                            ->required()
                                            ->options(PaymentMethod::filteredCases())
                                            ->label('Payment Method 2')
                                            ->reactive() // Make it reactive to listen for changes
                                            ->afterStateUpdated(fn(callable $set) => $set('transaction_ss', null)),
                                        TextInput::make('second_payment_method_amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->readOnly()
                                            ->required(),
                                        FileUpload::make('second_transaction_proof')
                                            ->label('Transaction Screenshot')
                                            ->image()
                                            ->imageEditor()
                                            ->previewable()
                                            ->downloadable()
                                            ->deletable(false)
                                            ->columnSpanFull()
                                            ->directory('images/transactions')
                                            ->required(fn(callable $get) => $get('payment_method_2') !== PaymentMethod::Cash->value) // Make the field required for non-Cash methods
                                            ->visible(fn(callable $get) => $get('payment_method_2') !== PaymentMethod::Cash->value),
                                    ])->columns(2),

                                FileUpload::make('transaction_proof')
                                    ->label('Transaction Screenshot')
                                    ->image()
                                    ->imageEditor()
                                    ->previewable()
                                    ->downloadable()
                                    ->deletable(false)
                                    ->directory('images/transactions')
                                    ->required(fn(callable $get) => $get('payment_method') !== PaymentMethod::Cash->value) // Make the field required for non-Cash methods
                                    ->visible(
                                        fn(callable $get) => ! in_array($get('payment_method'), [
                                            PaymentMethod::Cash->value,
                                            PaymentMethod::TwoPaymentMethod->value,
                                        ])
                                    ),

                            ]),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('enrollmentID')
                    ->label('Enrollment ID')
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('student.register_no')
                    ->label('Register No.')
                    ->sortable()
                    ->alignRight(),
                TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batch.course.name')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batch.batch')
                    ->label('Batch')
                    ->prefix('Batch - ')
                    ->searchable()
                    ->sortable()
                    ->alignRight(),
                IconColumn::make('has_installment_plan')
                    ->label('Installment')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('enrollment_payment_amount')
                    ->label('Enrollment Payment Amount')
                    ->money('MMK')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignRight(),
                TextColumn::make('discount_percentage')
                    ->label('Discount Percentage')
                    ->suffix(' %')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignRight(),
                TextColumn::make('additional_discount_amount')
                    ->label('Additional Discount')
                    ->money('MMK')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('discounted_payment_amount')
                    ->label('Additional Discount')
                    ->money('MMK')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_payment_amount')
                    ->label('Payable Amount')
                    ->money('MMK')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')->recordUrl(null)
            ->filters([
                SelectFilter::make('course')
                    ->relationship('batch', 'name'),
                Filter::make('Installment Plan')
                    ->query(fn(Builder $query): Builder => $query->where('has_installment_plan', true)),
                Filter::make('Downpayment Plan')
                    ->query(fn(Builder $query): Builder => $query->where('has_installment_plan', false)),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->color('info'),
                    Tables\Actions\DeleteAction::make()
                        ->modalHeading(fn($record) => 'Do you want to DELETE enrollment?')
                        ->modalDescription(fn($record) => 'This action will also permanently delete Payment and Transaction related to this enrollment. Are you sure you want to proceed?'),
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
            'index'  => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'view'   => Pages\ViewEnrollment::route('/{record}'),
            'edit'   => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }
}