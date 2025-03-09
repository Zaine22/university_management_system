<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayAttendance extends BaseWidget
{
    protected static ?string $heading = 'Today Attendances';

    protected static ?int $sort = 3;

    protected array|string|int $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->isTeacher();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()->whereDate('date', Carbon::today())
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                TextColumn::make('date')
                    ->date('d-m-Y')
                    ->searchable(),
                TextColumn::make('batch.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('timetable.teacher.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('timetable.starts_at')
                    ->date('H:i')
                    ->label('Start Time'),
                TextColumn::make('timetable.ends_at')
                    ->date('H:i')
                    ->label('End Time'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('view')
                        ->url(fn (Attendance $record): string => AttendanceResource::getUrl('view', ['record' => $record])),
                    Tables\Actions\Action::make('edit')
                        ->url(fn (Attendance $record): string => AttendanceResource::getUrl('edit', ['record' => $record])),
                ])
                    ->iconButton()
                    ->icon('heroicon-m-list-bullet')
                    ->tooltip('Actions')
                    ->size(ActionSize::Small),
            ]);
    }
}