<?php

namespace App\Filament\Resources\AttendanceResource\RelationManagers;

use App\Enums\AttendanceStatus;
use App\Filament\Resources\RollcallResource;
use App\Models\Rollcall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RollcallsRelationManager extends RelationManager
{
    protected static string $relationship = 'rollcalls';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('student.register_no')
                    ->label('Register No '),
                TextColumn::make('student.name'),
                SelectColumn::make('status')
                    ->options(AttendanceStatus::class)
                    ->disabled(fn ($record) => $record->rollcallable->submitted === 1),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('view')
                        ->url(fn (Rollcall $record): string => RollcallResource::getUrl('view', ['record' => $record])),
                    Tables\Actions\Action::make('edit')
                        ->url(fn (Rollcall $record): string => RollcallResource::getUrl('edit', ['record' => $record]))
                        ->visible(fn ($record) => $record->rollcallable->submitted === 0)->color('info'),
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
}