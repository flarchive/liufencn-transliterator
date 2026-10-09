<?php

/*
 * This file is part of fof/transliterator
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\Transliterator;

use Behat\Transliterator\Transliterator as BehatTransliterator;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Container\Container;

class Transliterator
{
    public static $transliterators = [
        'behat' => null,
        'php'   => null,
        'php2'  => null,
    ];

    public static function transliterate(string $str)
    {
        $transliterator = self::setting();

        if ($transliterator != null && array_key_exists($transliterator, self::$transliterators)) {
            $callable = self::$transliterators[$transliterator];

            if ($callable == null) {
                return self::behat($str);
            } else {
                return $callable($str);
            }
        }

        return self::behat($str);
    }

    /**
     * Flarum 2.x: `resolve()` helper is no longer guaranteed to be available,
     * so resolve the settings repository through the container directly.
     */
    private static function setting(): ?string
    {
        $value = Container::getInstance()->make(SettingsRepositoryInterface::class)
            ->get('fof-transliterator.package');

        return $value === null || $value === '' ? null : (string) $value;
    }

    /**
     * Only expose transliterators that can actually run on this server.
     * The `php` / `php2` drivers require ext-intl.
     */
    public static function availableTransliterators(): array
    {
        $keys = array_keys(self::$transliterators);

        if (!function_exists('transliterator_transliterate')) {
            $keys = array_values(array_filter($keys, function ($key) {
                return !in_array($key, ['php', 'php2'], true);
            }));
        }

        return $keys;
    }

    private static function behat(string $str): string
    {
        return BehatTransliterator::transliterate($str);
    }

    private static function php(string $str): string
    {
        if (!function_exists('transliterator_transliterate')) {
            return self::behat($str);
        }

        $str = transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0100-\u7fff] remove;', $str);
        $str = preg_replace('/[-\s]+/', '-', $str);

        return trim($str, '-');
    }

    private static function php2(string $str): string
    {
        if (!function_exists('transliterator_transliterate')) {
            return self::behat($str);
        }

        $str = transliterator_transliterate('Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();', $str);
        $str = preg_replace('/[-\s]+/', '-', $str);

        return trim($str, '-');
    }
}
