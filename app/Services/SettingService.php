<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Exception;

class SettingService implements SettingServiceInterface
{
    protected string $settingsFile = 'settings/settings.json';

    public function getSettings(): array
    {
        return Cache::remember('app_settings', 3600, function () {
            if (!Storage::disk('local')->exists($this->settingsFile)) {
                return $this->initSettings();
            }

            try {
                $settings = json_decode(
                    Storage::disk('local')->get($this->settingsFile),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
                return array_merge($this->defaultSettings(), $settings);
            } catch (Exception $e) {
                logger()->error('Gagal membaca settings: ' . $e->getMessage());
                return $this->defaultSettings();
            }
        });
    }

    public function updateSettings(array $data): void
    {
        $currentSettings = $this->getSettings();
        $updatedSettings = array_merge($currentSettings, $data);

        try {
            Storage::disk('local')->put(
                $this->settingsFile,
                json_encode($updatedSettings, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)
            );
            Cache::forget('app_settings');
        } catch (Exception $e) {
            logger()->error('Gagal menyimpan settings: ' . $e->getMessage());
            throw new Exception('Gagal menyimpan pengaturan');
        }
    }

    public function get(string $key, $default = null)
    {
        $settings = $this->getSettings();
        return $settings[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->updateSettings([$key => $value]);
    }

    protected function defaultSettings(): array
    {
        return [
            'app_name' => config('app.name', 'Aplikasi Laravel'),
            'app_logo' => 'default-logo.png',
            'timezone' => 'Asia/Jakarta',
        ];
    }

    protected function initSettings(): array
    {
        $defaults = $this->defaultSettings();
        Storage::disk('local')->put($this->settingsFile, json_encode($defaults, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
        return $defaults;
    }

    public function getAppName(): string
    {
        return $this->get('app_name', config('app.name', 'Aplikasi Laravel'));
    }

    public function getAppLogoUrl(): string
    {
        $filename = $this->get('app_logo', 'default-logo.png');

        if (Storage::disk('public')->exists($filename)) {
            return asset('storage/' . $filename);
        }

        return asset('images/default-logo.png');
    }
}
