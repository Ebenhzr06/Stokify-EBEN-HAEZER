<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Services\ProductServiceInterface;
use App\Services\ProductService;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Services\CategoryServiceInterface;
use App\Services\CategoryService;
use App\Repositories\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;
use App\Services\SupplierServiceInterface;
use App\Services\SupplierService;
use App\Repositories\ProductAtributRepositoryInterface;
use App\Repositories\ProductAtributRepository;
use App\Services\ProductAtributServiceInterface;
use App\Services\ProductAtributService;
use App\Services\SettingService;
use App\Services\SettingServiceInterface;
use App\Repositories\StockOpnameRepositoryInterface;
use App\Repositories\StockOpnameRepository;
use App\Services\StockOpnameServiceInterface;
use App\Services\StockOpnameService;
use App\Repositories\StockTransactionRepositoryInterface;
use App\Repositories\StockTransactionRepository;
use App\Services\StockTransactionServiceInterface;
use App\Services\StockTransactionService;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\UserServiceInterface;
use App\Services\UserService;
use App\Repositories\UserActivityRepository;
use App\Repositories\UserActivityRepositoryInterface;
use App\Services\UserActivityServiceInterface;
use App\Services\UserActivityService;
use App\Models\UserActivity;
use App\Observers\UserActivityObserver;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(SupplierServiceInterface::class, SupplierService::class);
        $this->app->bind(ProductAtributRepositoryInterface::class, ProductAtributRepository::class);
        $this->app->bind(ProductAtributServiceInterface::class, ProductAtributService::class);
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        $this->app->bind(StockOpnameRepositoryInterface::class, StockOpnameRepository::class);
        $this->app->bind(StockOpnameServiceInterface::class, StockOpnameService::class);
        $this->app->bind(StockTransactionRepositoryInterface::class, StockTransactionRepository::class);
        $this->app->bind(StockTransactionServiceInterface::class, StockTransactionService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserActivityRepositoryInterface::class, UserActivityRepository::class);
        $this->app->bind(UserActivityServiceInterface::class, UserActivityService::class);

    }

    public function boot()
    {
        $settingService = $this->app->make(SettingServiceInterface::class);

        // Share ke semua view
        View::share('appName',    $settingService->getAppName());
        View::share('appLogoUrl', $settingService->getAppLogoUrl());
        UserActivity::observe(UserActivityObserver::class);

        // Bagikan ke semua view
    }
}
