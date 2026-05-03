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
use App\Models\Category;


class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //section 1 - post details
                Group::make([

                    Section::make('Post Detail')
                    ->Description('Fill in the details of the post')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        //Grouping fields into 2 columns
                        Group::make([
                            TextInput::make('title')
                                //->minLength(5)
                                //->required()
                                //->rules('required')
                                //->maxLength(255)
                                //->rules(["required", "min:3", "max:10"]),
                                //Title minimal 5 karakter
                                ->rules('required | min:5 | max: 10')
                                ->validationMessages([
                                    'max' => 'Maximal 10 karakter'
                                ]),
                            TextInput::make('slug')
                                //Slug unik & minimal 3 karakter
                                ->unique(ignoreRecord: true)
                                ->rules('required | min:3')
                                ->validationMessages([
                                    'unique' => 'Slug must be unique.',
                                    'min' => 'Minimal 3 karakter'

                                ]),
                            Select::make('category_id')
                                ->relationship('category', 'name')
                                //Category wajib dipilih 
                                ->options(Category::all()->pluck('name', 'id'))
                                ->required()
                                //->preload()
                                ->searchable(),
                            ColorPicker::make('color'),
                        ])->columns(2),
                        MarkdownEditor::make('content'),
                    ])->columnSpanFull(),
                ])->columnSpan(2),

                //Grouping fields into 2 columns
                Group::make([
                    
                    //section 2 - image upload
                    Section::make('Image Upload')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->disk('public')
                            ->directory('posts')
                            //Image wajib diupload
                            ->required(),
                    ]),
                    
                    //section 3 - metadata
                    Section::make('Meta Information')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        TagsInput::make('tags'),
                        Checkbox::make('published'),
                        dateTimePicker::make('published_at'),
                    ]),
                ])->columnSpan(1),    
            ])->columns(3);
    }
}
