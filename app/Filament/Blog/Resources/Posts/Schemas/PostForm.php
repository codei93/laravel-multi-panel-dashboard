<?php

namespace App\Filament\Blog\Resources\Posts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('slug')->required(),
                    Select::make('category_id')
                        ->required()
                        ->relationship('category', 'name'),
                    Textarea::make('content'),
                ]),
            ]);
    }
}
