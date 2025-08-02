<?php

namespace App\Http\Controllers;

use App\Services\SettingServiceInterface;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private SettingServiceInterface $settingService;

    public function __construct(SettingServiceInterface $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Menampilkan halaman pengaturan aplikasi
     */
    public function index()
    {
        $appName     = $this->settingService->getAppName();
        $appLogoUrl  = $this->settingService->getAppLogoUrl();
        $allSettings = $this->settingService->getSettings();

        return view('example.content.admin.setting.index', compact('appName', 'appLogoUrl', 'allSettings'));
    }

    /**
     * Update logo aplikasi
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'app_logo' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        // Simpan file baru ke storage/public/logos
        $path = $request->file('app_logo')->store('logos', 'public');

        // Update di JSON melalui service
        $this->settingService->updateSettings([
            'app_logo' => $path,
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Logo aplikasi berhasil diperbarui.');
    }

    /**
     * Update nama aplikasi
     */
    public function updateAppName(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        $this->settingService->updateSettings([
            'app_name' => $request->app_name,
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Nama aplikasi berhasil diperbarui.');
    }
}
