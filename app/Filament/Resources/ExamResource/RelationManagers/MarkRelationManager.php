<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use App\Models\GradingRule;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MarkRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

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
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('No')->rowIndex(),
                TextColumn::make('exam.examId'),
                TextColumn::make('exam.batch.name'),
                TextColumn::make('exam.gradingRule.total_marks')->label('Total Marks'),
                TextColumn::make('student.name'),
                TextInputColumn::make('marks')
                    ->afterStateUpdated(function ($state, $record) {
                        $gradingRulesId = $record->exam->grading_rule_id;
                        $gradingRules = GradingRule::where('id', $gradingRulesId)->get()->pluck('grade_rules')->toArray();

                        $grade = self::calculateGrade($state, $gradingRules);
                        $record->update(['grade' => $grade]);
                    })
                    ->rules(['required', 'numeric', 'between:0,100'])
                    ->disabled(fn ($record) => $record->is_present === 0 || $record->exam->submitted === 1),

                ToggleColumn::make('is_present')
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state === false) {
                            $record->update(['marks' => 0, 'grade' => '-']);
                        }
                    })
                    ->disabled(fn ($record) => $record->exam->submitted === 1),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function calculateGrade($marks, $gradingRules)
    {
        foreach ($gradingRules[0] as $rule) {
            if ($marks >= $rule['min'] && $marks <= $rule['max']) {
                return $rule['grade'];
            }
        }
    }
}