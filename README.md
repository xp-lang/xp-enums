XP enums for PHP
================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-lang/xp-enums.svg)](http://travis-ci.org/xp-lang/xp-enums)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.6+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_6plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-lang/xp-enums/version.png)](https://packagist.org/packages/xp-lang/xp-enums)

Plugin for the [XP Compiler](https://github.com/xp-framework/compiler/) which adds an `enum` syntax to the PHP language.

Example
-------

```php
// Declaration
enum WeekDay {
  MON, TUE, WED, THU, FRI, SAT, SUN;
}

enum Coin {
  penny(1), nickel(2), dime(10), quarter(25);
}

// Usage
$monday= WeekDay::$MON;
$monday->name();  // "MON"

$dime= Coin::$dime;
$dime->ordinal(); // 10
```

Installation
------------
After installing the XP Compiler into your project, also include this plugin.

```bash
$ composer require xp-framework/compiler
# ...

$ composer require xp-lang/xp-enums
# ...
```

No further action is required.

See also
--------
