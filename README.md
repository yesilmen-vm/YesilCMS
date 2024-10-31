<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://i.imgur.com/hKpfev4.png">
  <source media="(prefers-color-scheme: light)" srcset="https://i.imgur.com/Vj0GNLV.png">
  <img alt="YesilCMS Logo" src="https://i.imgur.com/Vj0GNLV.png">
</picture>

# YesilCMS &middot; [![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](https://github.com/yesilmen-vm/YesilCMS/pulls) [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/yesilmen-vm/YesilCMS/blob/master/LICENSE)

**YesilCMS** is based on [BlizzCMS](https://github.com/WoW-CMS/BlizzCMS) and specifically adapted for [VMaNGOS Core](https://github.com/vmangos/core) and includes new features and many bug fixes.

You can check out the demo on through here; [YesilCMS Demo](https://yesilcms.page).

## Features

In addition to the existing features of BlizzCMS, some of the added features are as follows;

- **Complete VMaNGOS compability.**
- New installation script that directs the user based on OS/Environment.
- Tweaks to work on multiple Web Servers including Apache/Nginx/IIS.
- **Redis caching** for *nix operating systems.
- Advanced static caching. (optional, have some side-effects for logged in users)
- Functioning [reCAPTCHA](https://www.google.com/recaptcha/admin/create).
- New lightweight dark theme.
- Brand new **built-in database viewer** *(WIP(\*))*.
  - Progressive database search (1.2 to 1.12)
  - Item search with all related data.
  - Spell search with all related data and dev-required data.
  - Object, Creature and Quest page is in WIP state. (*)
- Brand new **customizable armory.**
  - Base character info
  - 3D Model Viewer (Fast: Uses plain `displayID`, Detailed: Converts old `displayID` to Classic `displayID` using Classic's DBC. You can also create a separate table instead of remote call.)
  - Dynamic Base Stats
  - Progressive Armory (1.2 to 1.12 can be selected by user as well)
  - Primary & Secondary Professions
  - PvP Stats
  - Ability to show enchants on items (by using WoWHead's tooltip instead of ClassicDB)
  - Ability to show all character stats instead of just base-ones
- Brand new **PvP Page**
  - All pvp data that player may want to see.
  - Wide filtering option.
    - Ability to filter by All Time and Last Week
    - Ability to filter by Faction
    - Ability to filter by specific name
- Unique **Timeline Module** with responsive design and full flexibility.
  - Ability to add any patch on choice (including custom ones)
  - Ability to order automatically or custom regardless of date
  - Separated Description, General, PvE and PvP sections better for maintainability.
  - Ability to add unique image for each patch.
- Rest API implementation for future developments.
- Built-in **account activation.**
- Built-in **account recovery.**
- Built-in **tooltip, item and spell viewer.**
- Built-in dynamic CSRF protection on each page.
- Tweaked Admin Panel. (SMTP tester, handlers and logs etc.)
- On-the-fly downloadable Realmlist.
- Bug fixes and improvements.

## System Requirements

- Functioning vMaNGOS server (on same/another host)
- OS (**Including Windows**)
- PHP 7.2+ (including 8.1.x - beta)
- Composer
- Web-server (Tested on Nginx, Apache and IIS)
- Database (MySQL/MariaDB)
- Redis for *nix operating systems

### PHP Extensions
- ctype
- curl
- gd
- gmp
- json
- mbstring
- mysqli
- openssl
- redis (only for *nix operating systems)
- soap

## Installation
This is an example installation for **RHEL OS** with;

- **nginx 1.21.6** (customly compiled for Brotli, Pagespeed, PCRE2, Zlib, Headers),
- **PHP 7.4.30 & PHP7.4-FPM**,
- **MariaDB 10.6.7**,
- **Redis v6.0.16**

and assuming you already installed & configured above.

*Note: for FastCGI, you can set CI_ENV by `fastcgi_param CI_ENV ENV_NAME` Available Environment names are `development, testing, environment`*

Clone project from Github to your web server root folder or document root and grant required ownership & permissions:

```bash
  git clone https://github.com/yesilmen-vm/YesilCMS.git [document_root]
  sudo chown -R nginx:nginx [document_root]
  sudo usermod -a -G nginx nginx
  sudo find [document_root] -type f -exec chmod 644 {} \;    
  sudo find [document_root] -type d -exec chmod 755 {} \;
```
You can either update dependencies to their latest versions or install them from scratch. To update;
```bash
  composer update
```
Create required Database & User for CMS:
```mariadb
  CREATE DATABASE [cms_db];
  CREATE USER '[cms_user]'@'[host]' IDENTIFIED BY '[password]';
  GRANT USAGE ON *.* TO '[cms_user]'@'[host]';
  GRANT SELECT, EXECUTE, SHOW VIEW, ALTER, ALTER ROUTINE, CREATE, CREATE ROUTINE, CREATE TEMPORARY TABLES, CREATE VIEW, DELETE, DROP, EVENT, INDEX, INSERT, REFERENCES, TRIGGER, UPDATE, LOCK TABLES  ON [cms_db].* TO '[cms_user]'@'[host]';
  FLUSH PRIVILEGES;
```

Then go to the site and proceed with the installation instructions.

## API Reference
There are 5 only method available yet, all CRUD operations are planned to be done from here in order to ensure infrastructure change afterwards.

#### Get new display ID
Takes `item_id` and returns new `ItemDisplayInfoID` from Classic build  (1.14.3.44403) on [WoW Tools](https://github.com/Marlamin/wow.tools). DBC can be downloaded and used locally as well.

```http
  GET /api/v1/item/newdisplayid/item_id
```

| Parameter | Type      | Description              |
|:----------|:----------|:-------------------------|
| `item_id` | `integer` | **Required**. Item entry |

#### Get Item Info
Takes `item_id` and `patch` and returns information of given item if exists in database within given patch.

```http
  GET /api/v1/item/item_id/patch
```

| Parameter | Type      | Description                         |
|:----------|:----------|:------------------------------------|
| `item_id` | `integer` | **Required**. Item entry            |
| `patch`   | `integer` | **Optional**. Patch version of item |

#### Get Item Tooltip Info
Takes `item_id` and `patch` and returns `id`, `type`, `name`, `icon`, `quality` and `tooltip`. Tooltip parameter will be
html formatted.

```http
  GET /api/v1/tooltip/item/item_id/patch
```

| Parameter | Type      | Description                         |
|:----------|:----------|:------------------------------------|
| `item_id` | `integer` | **Required**. Item entry            |
| `patch`   | `integer` | **Optional**. Patch version of item |

#### Get Spell Tooltip Info
Takes `spell_id` and `patch` and returns `id`, `type`, `name`, `icon`, and `tooltip`. Tooltip parameter will be
html formatted.

```http
  GET /api/v1/tooltip/spell/spell_id/patch
```

| Parameter  | Type      | Description                                                                    |
|:-----------|:----------|:-------------------------------------------------------------------------------|
| `spell_id` | `integer` | **Required**. Spell entry                                                      |
| `patch`    | `integer` | **Optional**. Patch version of spell (build converted to patch automatically.) |

#### Search Database
Takes `query` and `patch` and returns matching Item and Spells in database.

```http
  POST /api/v1/search_db
```

| Parameter | Type      | Description                                                        |
|:----------|:----------|:-------------------------------------------------------------------|
| `query`   | `string`  | **Required**. Search query                                         |
| `patch`   | `integer` | **Optional**. Patch version (by default its 10)                    |
| `token`   | `string`  | **Required when** CSRF is enabled. Do not confuse it with API key. |

*Note: `token` parameter should be renamed to configured `csrf_token_name`.*


## Roadmap

- Minimize the code to remain compatible only with vMaNGOS and other vanilla emulators.
- Add Object, Quest and NPC structure for database.
- Customize static-based cache structure. (Create a different cache structure for the visitor and the logged in user.)
- Migrate existing framework from Codeigniter 3 to Laravel 9.

## License

MIT

#### I like ☕, who doesn't right?
[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://www.buymeacoffee.com/yesilcms)