<?php

/**
 * webroot/index.php
 *
 * Standalone simple excuse generator.
 *
 * PHP version 5
 *
 * @category application
 * @package BOFH
 * @author GregorJ
 * @license https://opensource.org/licenses/MIT MIT
 * @link https://github.com/gregor-j/bofh-style-excuses
 */

//In this case we don't need any autoloading capabilities.
require_once __DIR__ . '/../src/Excuses.php';

//Create a new excuses object using Jeff Ballard's list of excuses.
$excuses = new GregorJ\BOFHStyleExcuses\Excuses();

//HTTP header
header("Content-Type: text/html");

//Minimal HTML output.
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Bastard Operator From Hell style random excuse</title>
    </head>
    <body>
        <h1>Bastard Operator From Hell style random excuse</h1>
        <p>Your excuse of the day: <em><?= $excuses->get(); ?></em></p>
    </body>
</html>
