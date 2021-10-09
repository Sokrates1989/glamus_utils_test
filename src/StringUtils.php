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
 * 1.3.0        2021-09-10  michiels
 *              - type hinting of str_contains removed.
 * 1.2.0        2021-08-17  michiels
 *              - Added getDateAsStringYearMonthDay.
 * 1.1.1        2021-06-14  michiels
 *              - bug fix str_contains.
 * 1.1.0        2021-06-14  michiels
 *              - added fallback version of str_contains.
 * 1.0.2        2021-06-09  michiels
 *              - fixed code to apply to coding standards.
 * 1.0.1       2021-26-05  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @package BpB-Wahlomat
 * @subpackage Browsertest
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
   *   Klammern entfernt werden
   *                                                sollen.
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
   * Returns the current date as string in Year_month_day format.
   *
   * @return string
   *   The current date as string in Year_month_day format.
   */
  public function getDateAsStringYearMonthDay(): string {
    return date('Y_m_d');
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
