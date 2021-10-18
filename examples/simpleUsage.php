<?php

/**
 * @file
 * This file shows the basic usage of glamus_utils using examples.
 *
 * For information about what functions there are, have a look at the classes
 * themself. ("../src/FileUtils.php", "../src/StringUtils.php", ...)
 */

// You should use abbreviations at the start of file, so you can use the shorter
// form of the class.
use Glamus\Utils\StringUtils;
use Glamus\Utils\FileUtils;

// Option 1: composer (recommended).
$pathToVendor = dirname(dirname(dirname(dirname(__DIR__))));
require_once $pathToVendor . "/vendor/autoload.php";

// Option 2: git Require or include the install.php if cloned via git.
// Change this path to where you cloned or installed the project to.
$pathToGlamusUtilsRoot = dirname(dirname(__DIR__));
require_once $pathToGlamusUtilsRoot . "/glamus_utils_test/install/install.php";


// Short usage when having used abbreviation at start (recommended).
$fileUtils = new FileUtils();
echo $fileUtils->echoPhrase("Test output");

echo "\n<br/>";
echo "\n<br/>";

// Longer instantiation, if you cannot use "use" at start.
$stringUtils = new Glamus\Utils\StringUtils();
echo $stringUtils->echoPhrase("Test output using use");

// For information about what functions there are, have a look at the classes
// * themself. ("../src/FileUtils.php", "../src/StringUtils.php", ...)
