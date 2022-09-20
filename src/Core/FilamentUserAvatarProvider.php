<?php

namespace Devaslanphp\FilamentAvatar\Core;

use Illuminate\Database\Eloquent\Model;

class FilamentUserAvatarProvider
{

    /**
     * Get the default provider url
     *
     * @param Model $user
     * @return string
     */
    public function get(Model $user): string
    {
        $default_provider = config('filament-avatar.default_provider');
        $provider = config('filament-avatar.providers.' . $default_provider . '.class');
        return (new $provider)->get($user);
    }

}
