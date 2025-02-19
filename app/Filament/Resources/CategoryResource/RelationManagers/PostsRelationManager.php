<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->rules(['min:3', 'max:5'])
                    ->required(),
                TextInput::make('slug')->required(),
                ColorPicker::make('color')->required(),
                Select::make('category_id')
                    ->options(Category::all()
                        ->pluck('name', 'id'))
                    ->searchable(),
                Select::make('authors')
                    ->relationship('authors', 'name')
                    ->multiple()
                    ->searchable(),
                MarkdownEditor::make('content')->required(),
                FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                TagsInput::make('tags')->required(),
                Checkbox::make('published')->required(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('filament')
            ->columns([
                // Tables\Columns\TextColumn::make('filament'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                ColorColumn::make('color')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
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
