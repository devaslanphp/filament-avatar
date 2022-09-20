<?php

namespace Devaslanphp\FilamentAvatar;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAvatarProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-avatar')
            ->hasConfigFile(['filament-avatar']);
    }

}
