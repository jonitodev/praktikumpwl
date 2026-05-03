<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Filters\SelectFilter;


class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->Label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('title')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                ColorColumn::make('color'),
                ImageColumn::make('image')
                    ->disk('public'),
                IconColumn::make('published')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->Label('Created At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('tags')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tags'),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
                Filter::make('created_at')
                    ->label('Creation Date')
                    ->schema([
                        DatePicker::make('created_at')
                            ->label('Select Date: ')
                    ])
                    ->query(function ($query, $data ){
                        return $query
                            ->when(
                                $data['created_at'],
                                fn ($query, $date) => $query ->whereDate('created_at', $date)
                            );
                    }),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->preload(),
            ])
            ->recordActions([
                ReplicateAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                Action::make('status')
                    ->label('status change')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->schema([
                        Checkbox::make('publishd')
                            ->default(fn($record): bool => $record->published),
                    ])
                    ->action(function ($record, $data) {
                        $record->update(['published' => $data['published']]);
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
