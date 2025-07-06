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
                    ->suffix('cm'), // Sesuaikan suffix ke cm karena Anda input cm
                Forms\Components\TextInput::make('weight')
                    ->numeric()
                    ->required()
                    ->suffix('kg'),
                Forms\Components\TextInput::make('age') // <<< TAMBAHKAN AGE
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('gender') // <<< TAMBAHKAN GENDER
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\Select::make('activity_level') // <<< TAMBAHKAN ACTIVITY LEVEL
                    ->options([
                        'Sedentary' => 'Sedentary (tidak aktif)',
                        'Ringan' => 'Ringan',
                        'Sedang' => 'Sedang',
                        'Berat' => 'Berat',
                        'Sangat Berat' => 'Sangat Berat',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('bmi')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('bmr') // <<< TAMBAHKAN BMR
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('tdee') // <<< TAMBAHKAN TDEE (jika disimpan)
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\DateTimePicker::make('recorded_at') // <<< TAMPILKAN recorded_at
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Recorded At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('height')
                    ->suffix('cm')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->suffix('kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->sortable(),
                Tables\Columns\TextColumn::make('activity_level') // <<< TAMPILKAN ACTIVITY LEVEL
                    ->sortable(),
                Tables\Columns\TextColumn::make('bmi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bmr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tdee') // <<< TAMPILKAN TDEE (jika disimpan)
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('recorded_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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