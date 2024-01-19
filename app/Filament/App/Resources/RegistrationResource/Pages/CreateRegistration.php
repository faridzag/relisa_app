<?php

namespace App\Filament\App\Resources\RegistrationResource\Pages;

use App\Filament\App\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistration extends CreateRecord
{
    protected static string $resource = RegistrationResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
