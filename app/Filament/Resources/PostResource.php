<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    public static function form(Form $form): Form
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
                    ->multiple()
                    ->preload()
                    ->relationship('authors', 'name'),
                MarkdownEditor::make('content')->required(),
                FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                TagsInput::make('tags')->required(),
                Checkbox::make('published')->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                ImageColumn::make('thumbnail')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('tags')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                CheckboxColumn::make('published')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('Published Posts')->query(
                    function ($query) {
                        return $query->where('published', true);
                    }
                ),
                SelectFilter::make('category_id')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->multiple()
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

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
