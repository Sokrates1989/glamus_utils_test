<?php

namespace Glamus\Utils;

/**
 * Hilfsklasse zum Lesen von Json.
 *
 * GetJsonData([FILENAME]) liest json Datei und gibt array wieder.
 *
 *
 * 1.0.0        2021-10-18  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @author Patrick Michiels <michiels@glamus.de>
 */
class JsonHandler {

  /**
   * JsonHandler constructor.
   */
  public function __construct() {
  }

  /**
   * Gibt array aus json Datei wieder.
   *
   * @param string $pJsonFile
   *   Path to json.
   *
   * @returns false|mixed false on error, array from json on success.
   */
  public function getJsonData(string $pJsonFile) {

    $jsonFileContent = file_get_contents($pJsonFile);

    $jsonData = json_decode($jsonFileContent, TRUE);
    // JSON-Error.
    if (json_last_error() != JSON_ERROR_NONE) {
      // Echo json_last_error() ;.
      return FALSE;
    }
    else {
      return $jsonData;
    }
  }

  /**
   * Returns json string from array.
   *
   * @param array $pArrayToConvert
   *   The Array to convert to a json string.
   *
   * @returns false|string
   *  false on error, array from json on success.
   */
  public function convertToJson(array $pArrayToConvert) {

    return json_encode($pArrayToConvert);
  }

}
