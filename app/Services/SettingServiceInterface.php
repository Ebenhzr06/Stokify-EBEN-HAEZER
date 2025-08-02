<?php

namespace App\Services;

interface SettingServiceInterface
{
    public function getSettings(): array;
    public function updateSettings(array $data): void;
    public function getAppName(): string;
    public function getAppLogoUrl(): string;
}
