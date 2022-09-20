<?php

namespace Devaslanphp\FilamentAvatar\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GravatarProvider
{

    /**
     * Get the gravatar url from a user object
     *
     * @param Model $user
     * @return string
     */
    public function get(Model $user): string
    {
        $name = md5($user->{config('filament-avatar.providers.gravatar.name_field')});
        return config('filament-avatar.providers.gravatar.url') . '' . $name;
    }

}
