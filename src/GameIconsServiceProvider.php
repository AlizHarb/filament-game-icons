<?php

namespace Alizharb\FilamentGameIcons;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Alizharb\FilamentGameIcons\Commands\SyncGameIconsCommand;

class GameIconsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-game-icons';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-game-icons')
            ->hasCommand(SyncGameIconsCommand::class);
    }
}