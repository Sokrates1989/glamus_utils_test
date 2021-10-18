<?php

namespace Glamus\Utils;

/**
 * Hilsklasse fuer diverse Funktionen rund um Zeichenketten.
 *
 * Hilfsklasse mit leerem Konstruktor, die diverse Funktionen rund um
 * Zeichenketten buendelt
 * z.Bsp.:  entfernt runde Klammern aus Zeichenkette.
 *
 *
 * 1.0.1       2021-10-18  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @author Patrick Michiels <michiels@glamus.de>
 */
class StringUtils {

  /**
   * StringUtils constructor.
   */
  public function __construct() {
  }

  /**
   * Entfernt runde Klammern von string, falls vorhanden.
   *
   * @param string $stringToRemoveRoundBracketsFrom
   *   Der string von dem runde
   *   Klammern entfernt werden sollen.
   *
   * @return string
   *   String ohne runde Klammern
   */
  public function removeRoundBrackets(string $stringToRemoveRoundBracketsFrom)
    : string {
    return str_replace(
        ["(", ")"],
        "",
        $stringToRemoveRoundBracketsFrom
      );
  }

  /**
   * Compatibility version of str_contains.
   *
   * Also supports usage on older php version, using a supported method.
   * DO NOT use typehinting for parameters, could cause Error.
   *
   * @param string $haystack
   *   The string to search in.
   * @param string $needle
   *   The substring to search for in the haystack.
   *
   * @return bool
   *   Returns true if needle is in haystack, false otherwise.
   */
  public function str_contains($haystack, $needle) : bool {

    // Make sure no argument is null.
    // because once a value passed was null quickfix.
    if (is_null($haystack) || is_null($needle)) {
      return FALSE;
    }

    if (function_exists('str_contains')) {
      return str_contains($haystack, $needle);
    }
    else {
      return $needle !== '' && mb_strpos($haystack, $needle) !== FALSE;
    }
  }



  /**
   * Friendly welcome
   *
   * @param string $phrase Phrase to return
   *
   * @return string Returns the phrase passed in
   */
  public function echoPhrase($phrase)
  {
    $phrase = $phrase . " -  \"said StringUtils\"";
    return $phrase;
  }

}
