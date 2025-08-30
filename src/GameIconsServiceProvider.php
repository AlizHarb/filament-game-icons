<?php

namespace Alizharb\FilamentGameIcons;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Alizharb\FilamentGameIcons\Commands\SyncGameIconsCommand;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;

class GameIconsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-game-icons';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-game-icons')
            ->hasCommand(SyncGameIconsCommand::class);
    }

    public function bootingPackage(): void
    {
        FilamentAsset::register([
            Css::make('game-icons', __DIR__ . '/../resources/css/game-icons.css'),
        ], self::$name);

        $this->publishes([
            __DIR__ . '/../resources/css/game-icons.css' => resource_path('vendor/filament-game-icons/game-icons.css'),
        ], 'filament-game-icons-styles');

        $this->publishes([
            __DIR__ . '/../resources/css/game-icons.css' => public_path('vendor/filament-game-icons/game-icons.css'),
        ], 'filament-game-icons-public');
    }
}