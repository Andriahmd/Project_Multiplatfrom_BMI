<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BmiRecordResource\Pages;
use App\Filament\Resources\BmiRecordResource\RelationManagers;
use App\Models\BmiRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BmiRecordResource extends Resource
{
    protected static ?string $model = BmiRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Data Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('height')
                    ->numeric()
                    ->required()
                    ->suffix('meters'),
                Forms\Components\TextInput::make('weight')
                    ->numeric()
                    ->required()
                    ->suffix('kg'),
                Forms\Components\TextInput::make('bmi')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('height'),
                Tables\Columns\TextColumn::make('weight'),
                Tables\Columns\TextColumn::make('bmi'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBmiRecords::route('/'),
            'create' => Pages\CreateBmiRecord::route('/create'),
            'edit' => Pages\EditBmiRecord::route('/{record}/edit'),
        ];
    }
}