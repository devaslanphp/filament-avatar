<?php

namespace Devaslanphp\FilamentAvatar\Core;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasAvatarUrl
{

    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                $provider = null;
                switch (config('filament-avatar.default_provider')) {
                    case 'ui-avatar':
                        $provider = new UiAvatarsProvider();
                        break;
                    case 'gravatar':
                        $provider = new GravatarProvider();
                        break;
                }
                return $provider?->get($this);
            }
        );
    }

}
