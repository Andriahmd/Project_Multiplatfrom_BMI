<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecommendationResource\Pages;
use App\Filament\Resources\RecommendationResource\RelationManagers;
use App\Models\Recommendation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class RecommendationResource extends Resource
{
    protected static ?string $model = Recommendation::class;
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?string $navigationGroup = 'Data Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\Textarea::make('recommendation_text')
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('Gambar')
                    ->directory('uploads/recommendations')
                    ->disk('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->maxSize(2048) // Max 2MB
                    ->nullable(),


                Forms\Components\Select::make('type')
                    ->label('Jenis Rekomendasi')
                    ->options([
                        'diet' => 'Rekomendasi Diet',
                        'bulking' => 'Rekomendasi Bulking',
                        'umum' => 'Rekomendasi Umum',
                    ])
                    ->required(),


                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('recommendation_text')->limit(50),
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->height(50)
                    ->width(50),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('created_at'),
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
            'index' => Pages\ListRecommendations::route('/'),
            'create' => Pages\CreateRecommendation::route('/create'),
            'edit' => Pages\EditRecommendation::route('/{record}/edit'),
        ];
    }
}