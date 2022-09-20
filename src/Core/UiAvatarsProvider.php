<?php

namespace Devaslanphp\FilamentAvatar\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UiAvatarsProvider
{

    /**
     * HSL H range
     *
     * @var array
     */
    private $hRange;

    /**
     * HSL S range
     *
     * @var array
     */
    private $sRange;

    /**
     * HSL L range
     *
     * @var array
     */
    private $lRange;

    public function __construct()
    {
        $this->hRange = config('filament-avatar.providers.ui-avatar.hRange');
        $this->sRange = config('filament-avatar.providers.ui-avatar.sRange');
        $this->lRange = config('filament-avatar.providers.ui-avatar.lRange');
    }

    /**
     * Return the char cord at a specific index
     *
     * @param $str
     * @param $index
     * @return float|int|null
     */
    function utf8CharCodeAt($str, $index)
    {
        $char = mb_substr($str, $index, 1, 'UTF-8');

        if (mb_check_encoding($char, 'UTF-8')) {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        } else {
            return null;
        }
    }

    /**
     * Get the hash of a specific string
     *
     * @param string $str
     * @return float|int
     */
    private function getHashOfString(string $str)
    {
        $hash = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $hash = $this->utf8CharCodeAt($str, $i) + (($hash << 5) - $hash);
        }
        $hash = abs($hash);
        return $hash;
    }

    /**
     * Normalize the has string
     *
     * @param $hash
     * @param $min
     * @param $max
     * @return float
     */
    private function normalizeHash($hash, $min, $max)
    {
        return floor(($hash % ($max - $min)) + $min);
    }

    /**
     * Generate the Hex string from a name (string)
     *
     * @param $name
     * @return string
     */
    private function generateHex($name)
    {
        $hash = $this->getHashOfString($name);
        $h = $this->normalizeHash($hash, $this->hRange[0], $this->hRange[1]);
        $s = $this->normalizeHash($hash, $this->sRange[0], $this->sRange[1]);
        $l = $this->normalizeHash($hash, $this->lRange[0], $this->lRange[1]);
        return $this->hslToHex($h, $s, $l);
    }

    /**
     * Convert HSL array to Hex string
     *
     * @param $h
     * @param $s
     * @param $l
     * @param $toHex
     * @return string
     */
    function hslToHex($h, $s, $l)
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;

        $r = $l;
        $g = $l;
        $b = $l;
        $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
        if ($v > 0) {
            $m = $l + $l - $v;
            $sv = ($v - $m) / $v;
            $h *= 6.0;
            $sextant = floor($h);
            $fract = $h - $sextant;
            $vsf = $v * $sv * $fract;
            $mid1 = $m + $vsf;
            $mid2 = $v - $vsf;

            switch ($sextant) {
                case 0:
                    $r = $v;
                    $g = $mid1;
                    $b = $m;
                    break;
                case 1:
                    $r = $mid2;
                    $g = $v;
                    $b = $m;
                    break;
                case 2:
                    $r = $m;
                    $g = $v;
                    $b = $mid1;
                    break;
                case 3:
                    $r = $m;
                    $g = $mid2;
                    $b = $v;
                    break;
                case 4:
                    $r = $mid1;
                    $g = $m;
                    $b = $v;
                    break;
                case 5:
                    $r = $v;
                    $g = $m;
                    $b = $mid2;
                    break;
            }
        }
        $r = round($r * 255, 0);
        $g = round($g * 255, 0);
        $b = round($b * 255, 0);

        $r = ($r < 15) ? '0' . dechex($r) : dechex($r);
        $g = ($g < 15) ? '0' . dechex($g) : dechex($g);
        $b = ($b < 15) ? '0' . dechex($b) : dechex($b);
        return "$r$g$b";
    }

    /**
     * Get the ui avatar url from a user object
     *
     * @param Model $user
     * @return string
     */
    public function get(Model $user): string
    {
        $name = Str::of($user->{config('filament-avatar.providers.ui-avatar.name_field')})
            ->trim()
            ->explode(' ')
            ->map(fn(string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        $bgColor = config('filament-avatar.providers.ui-avatar.dynamic_bg_color') ? $this->generateHex($name) : config('filament-avatar.providers.ui-avatar.bg_color');
        return config('filament-avatar.providers.ui-avatar.url') . '?name=' . urlencode($name) . '&color=' . config('filament-avatar.providers.ui-avatar.text_color') . '&background=' . $bgColor;
    }

}
