![YesilCMS Logo](https://i.imgur.com/Vj0GNLV.png)
# YesilCMS &middot; [![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](https://github.com/yesilmen-vm/YesilCMS/pulls) [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/yesilmen-vm/YesilCMS/blob/master/LICENSE)

**YesilCMS** is based on [BlizzCMS](https://github.com/WoW-CMS/BlizzCMS) and specifically adapted for [VMaNGOS Core](https://github.com/vmangos/core) and includes new features and many bug fixes.

## Features

In addition to the existing features of BlizzCMS, some of the added features are as follows;

- Complete VMaNGOS compability.
- New installation script that directs the user based on OS/Environment.
- Tweaks to work on multiple Web Servers including Apache/Nginx/IIS.
- Redis caching for *nix operating systems.
- Functioning [reCAPTCHA](https://www.google.com/recaptcha/admin/create).
- New lightweight dark theme.
- Brand new customizable armory.
  - Base character info
  - 3D Model Viewer (Fast: Uses plain `displayID`, Detailed: Converts old `displayID` to Classic `displayID` using Classic's DBC. You can also create a separate table instead of remote call.)
  - Dynamic Base Stats
  - Primary & Secondary Professions
  - PvP Stats
  - Ability to show enchants on items (by using WoWHead's tooltip instead of ClassicDB)
  - Ability to show all character stats instead of just base-ones
- Unique Timeline Module with responsive design and full flexibility.
- Rest API implementation for future developments.
- Built-in account activation.
- Built-in account recovery.
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
There is only 1 method available yet, all CRUD operations are planned to be done from here in order to ensure infrastructure change afterwards.

#### Get new display ID
Takes `item_id` and returns new `ItemDisplayInfoID` from Classic build  (1.14.3.44403) on [WoW Tools](https://github.com/Marlamin/wow.tools). DBC can be downloaded and used locally as well.

```http
  GET /api/v1/item/newdisplayid/item_id
```

| Parameter | Type      | Description           |
|:----------|:----------|:----------------------|
| `item_id` | `integer` | **Required**. Item ID |

## License

MIT

#### I like ☕, who doesn't right?
[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://www.buymeacoffee.com/yesilcms)