# Bastard Operator From Hell (BOFH) style random excuses

Based on [Jeff Ballard's][ballard] collection of BOFH style excuses of the day, this library selects a random one. In
case you don't want to rely on the web sources, you can provide your own list anywhere `file_get_contents()` can open.


## Installation

`composer require gregorj/bofhstyleexcuses`

That's it. Well, you need [composer][composer] installed first, but I guess that's obvious.


## Library usage

I added some [unit tests](tests/ExcusesTest.php) which demonstrate the usage. A brief example:

```php
<?php
use GregorJ\BOFHStyleExcuses\Excuses;
$excuses = new Excuses();
printf("Your excuse of the day: %s", $excuses->get());
?>
```

This example relies on the fact, that you use [composer's autoloader][autoload] in
[vendor/autoload.php](vendor/autoload.php).


## CLI usage

You can call `bin/excuse` from the commandline and wait patiently for a random excuse from [Jeff Ballard's][ballard]
collection of BOFH style excuses.


## Website standalone usage

You can point apache to the [webroot](webroot/index.php) and it will display a minimal HTML page with a random excuse
from [Jeff Ballard's][ballard] collection of BOFH style excuses.


## PHP unit tests

In case you want to run the unit tests, you can call `vendor/bin/phpunit`.


[ballard]: http://pages.cs.wisc.edu/~ballard/bofh/ "The Bastard Operator From Hell-Style Excuse Server"
[composer]: https://getcomposer.org/download/ "Composer - Dependency Manager for PHP"
[autoload]: https://getcomposer.org/doc/01-basic-usage.md#autoloading "Composer basic usage - Autoloading"
