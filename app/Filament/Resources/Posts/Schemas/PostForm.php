<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Group;


class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //section 1 - post details
                Section::make('Post Detail')
                ->Description('Fill in the details of the post')
                ->icon('heroicon-o-document-text')
                ->schema([
                    //Grouping fields into 2 columns
                    Group::make([
                        TextInput::make('title')
                            ->minLength(5),
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->preload()
                            ->searchable(),
                        ColorPicker::make('color'),
                    ])->columns(2),
                    MarkdownEditor::make('content'),
                ])->columnSpan(2),

                //Grouping fields into 2 columns
                Group::make([
                    
                    //section 2 - image upload
                    Section::make('Image Upload')
                    ->schema([
                        FileUpload::make('image')
                            ->disk('public')
                            ->directory('posts'),
                    ]),
                    
                    //section 3 - metadata
                    Section::make('Meta Information')
                    ->schema([
                        TagsInput::make('tags'),
                        Checkbox::make('published'),
                        dateTimePicker::make('published_at'),
                    ]),
                ])->columnSpan(1),    
            ])->columns(3);
    }
}
