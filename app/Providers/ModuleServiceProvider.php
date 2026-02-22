<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $modulesPath = app_path('Modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        $modules = array_map('basename', glob($modulesPath . '/*', GLOB_ONLYDIR));

        foreach ($modules as $module) {
            // Register Module Service Providers if they exist
            $provider = "App\\Modules\\{$module}\\Providers\\{$module}ServiceProvider";
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modulesPath = app_path('Modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        $modules = array_map('basename', glob($modulesPath . '/*', GLOB_ONLYDIR));

        foreach ($modules as $module) {
            $moduleLower = strtolower($module);

            // Load Module Routes
            $routeFile = "{$modulesPath}/{$module}/Routes/web.php";
            if (file_exists($routeFile)) {
                Route::middleware('web')
                    ->group($routeFile);
            }

            $apiRouteFile = "{$modulesPath}/{$module}/Routes/api.php";
            if (file_exists($apiRouteFile)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group($apiRouteFile);
            }

            // Load Module Config
            $configPath = "{$modulesPath}/{$module}/Config";
            if (is_dir($configPath)) {
                $configFiles = glob($configPath . '/*.php');
                foreach ($configFiles as $file) {
                    $this->mergeConfigFrom($file, $moduleLower . '.' . basename($file, '.php'));
                }
            }

            // Load Module Views
            $viewsPath = "{$modulesPath}/{$module}/Resources/views";
            if (is_dir($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $moduleLower);
            }

            // Load Module Lang
            $langPath = "{$modulesPath}/{$module}/Resources/lang";
            if (!is_dir($langPath)) {
                $langPath = "{$modulesPath}/{$module}/Lang";
            }
            if (is_dir($langPath)) {
                $this->loadTranslationsFrom($langPath, $moduleLower);
            }

            // Load Module Migrations
            $migrationsPath = "{$modulesPath}/{$module}/Database/Migrations";
            if (is_dir($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }
    }
}
