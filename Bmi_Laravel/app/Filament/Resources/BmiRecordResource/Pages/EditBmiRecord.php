<?php

namespace App\Filament\Resources\BmiRecordResource\Pages;

use App\Filament\Resources\BmiRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBmiRecord extends EditRecord
{
    protected static string $resource = BmiRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
