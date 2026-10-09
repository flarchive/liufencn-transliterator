# URL Transliterator (Flarum 2.x community fork)

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Flarum 2.x](https://img.shields.io/badge/flarum-2.x-orange.svg)

A [Flarum](http://flarum.org) extension. Transliterate non-ASCII characters in discussion URL.

> **This is an unofficial community fork.**
> Upstream: <https://github.com/FriendsOfFlarum/transliterator> — the original extension was
> abandoned in 2020 and only supported `flarum/core: ^0.1.0-beta.16` (Flarum 1.x).
> This fork keeps the MIT license and all original authorship, and adds **Flarum 2.x support**
> on the `flarum-2.x` branch. The `master` branch is an untouched mirror of upstream.

### Screenshots

Before:

![Imgur](https://i.imgur.com/Lng2CHh.jpg)

After:

![Imgur](https://i.imgur.com/RGfQ4Q6.jpg)

Settings:

![Imgur](https://i.imgur.com/HbFWQeC.jpg)

### Installation

```sh
composer require liufencn/transliterator
```

> This fork is published on Packagist under its own name, so Composer can install it directly.
> The upstream `fof/transliterator` package name could not be reused — it already exists on
> Packagist and is owned by FriendsOfFlarum.

If you prefer to track the branch directly, add this to your root `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/LiuFenCN/transliterator"
        }
    ],
    "require": {
        "liufencn/transliterator": "dev-flarum-2.x"
    }
}
```

### Updating

```sh
composer update liufencn/transliterator
```

### What changed for Flarum 2.x

| Area                     | 1.x (upstream)                           | 2.x (this branch)                                |
| ------------------------ | ---------------------------------------- | ------------------------------------------------ |
| `flarum/core` constraint | `^0.1.0-beta.16`                         | `^2.0`                                           |
| PHP                      | —                                        | `>=8.1`                                          |
| Admin JS entry           | `flarum/app` (removed in 2.0)            | `flarum/admin/app`                               |
| Mithril                  | global `m`                               | global `m` from core (not bundled twice)          |
| Build chain              | webpack 4 + `flarum-webpack-config ^0.1` | webpack 5 + `flarum-webpack-config ^3.0`         |
| Settings lookup          | `resolve('flarum.settings')` helper      | container-resolved `SettingsRepositoryInterface` |
| Legacy `extra.flagrow`   | present                                  | removed                                          |

Additional fixes carried in this fork:

- **Fixed a fatal bug**: when the `behat` transliterator was explicitly selected, upstream called
  a non-existent property (`self::$transliterator(...)`), which raised a fatal error. It now
  correctly calls `self::behat()`.
- **ext-intl guard**: the `PHP` and `PHP #2` transliterators require `ext-intl`. If the extension
  is not loaded, they are hidden from the admin settings and fall back to `behat` instead of
  throwing.

### Links

- Upstream repository: <https://github.com/FriendsOfFlarum/transliterator>
- This fork: <https://github.com/LiuFenCN/transliterator>

Originally an extension by [FriendsOfFlarum](https://github.com/FriendsOfFlarum);
Flarum 2.x maintenance by LiuFenCN.
