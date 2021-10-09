<?php

// Make sure to have included this project with composer or cloned from git.
// For more see README.md


// You can use abbreviations at the start of file, so you can use the shorter
// form of the class
use Glamus\Utils\FileUtils;

// require or include the install.php.

// Change this path to where you cloned or installed the project to.
$pathToGlamusUtilsRoot = dirname(dirname (__DIR__));
require_once $pathToGlamusUtilsRoot . "/glamus_utils_test/install.php";

// Short usage when having used abbreviation at start.
$fileUtils = new FileUtils();
echo $fileUtils->echoPhrase("It's really working with use");

echo "\n<br/>";
echo "\n<br/>";

// Longer instantiation, if you do not want to use "use" at start.
$stringUtils = new \Glamus\Utils\StringUtils();
echo $stringUtils->echoPhrase("It's really working with use");
