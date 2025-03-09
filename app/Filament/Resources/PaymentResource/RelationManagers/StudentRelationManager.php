<?php

namespace App\Filament\Resources\PaymentResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StudentRelationManager extends RelationManager
{
    protected static ?string $title = 'Student';

    protected static string $relationship = 'enrollment';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('student.register_no')->label('Register No'),
                TextColumn::make('student.name')->label('Student Name'),
                ImageColumn::make('student.avatar')->label('Student Avatar'),
                TextColumn::make('student.phone')->label('Student Phone'),
                TextColumn::make('student.admission_date')->label('Admission Date'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}