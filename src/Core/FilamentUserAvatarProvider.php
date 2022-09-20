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
        $provider = null;
        switch (config('filament-avatar.default_provider')) {
            case 'ui-avatar':
                $provider = new UiAvatarsProvider();
                break;
            case 'gravatar':
                $provider = new GravatarProvider();
                break;
        }
        return $provider?->get($user);
    }

}
