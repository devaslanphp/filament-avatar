<?php

namespace Devaslanphp\FilamentAvatar\Core;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasAvatarUrl
{

    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: fn() => (new FilamentUserAvatarProvider())->get($this)
        );
    }

}
