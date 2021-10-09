<?php

namespace Glamus\Utils;

use Glamus\Utils\JsonHandler;

/**
 * Hilsklasse fuer diverse Funktionen rund um Dateien.
 *
 * Hilfsklasse mit leerem Konstruktor, die diverse Funktionen rund um Dateien
 * buendelt
 * z.Bsp.:  extrahiert Dateinamen aus Datei, extrahiert Dateiendung aus Datei,
 *          erzeugt Dateien, erzeugt Pfade
 *          ...
 *
 * 1.0.0       2021-12-05  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @author Patrick Michiels <michiels@glamus.de>
 */
class FileUtils {

  /**
   * JsonHandler used to handle convert json.
   *
   * @var \Glamus\Utils\JsonHandler
   */
  private JsonHandler $jsonHandler;

  /**
   * FileUtils constructor.
   */
  public function __construct() {
    require_once __DIR__ . "/JsonHandler.php";

    $this->jsonHandler = new JsonHandler();
  }

  /**
   * Gibt den Dateinamen einer Datei ohne Endung wider (test.php -> test)
   *
   * @param string $pFullFilename
   *   der komplette Dateiname.
   *
   * @return string
   *   Der Dateiname ohne Endung, falls . vorhanden,
   *   falls nicht gibt es uebergebenen string wider
   */
  public function getFilenameWithoutExtension(string $pFullFilename) {
    // Enthaelt Dateinmae einen Punkt.
    if (strpos($pFullFilename, ".") == FALSE) {
      // Datei enhaelt keinen Punkt.
      return $pFullFilename;
    }
    else {
      // Gibt Dateiname ohne Dateitypendung wider (test.php -> test)
      return substr(
        $pFullFilename,
        0,
        strrpos($pFullFilename, ".")
          );
    }
  }

  /**
   * Gibt die Dateiendung einer Datei wider (test.php -> .php)
   *
   * @param string $pFullFilename
   *   Der komplette Dateiname.
   *
   * @return string
   *   Die Dateiendung der uebergebenen Datei oder urspruenglicher
   *    string, falls kein "." vorhanden.
   */
  public function getFileExtension(string $pFullFilename) {
    // Enthaelt Dateiname einen Punkt.
    if (strpos($pFullFilename, ".") == FALSE) {
      // Datei enhaelt keinen Punkt.
      return $pFullFilename;
    }
    else {
      // Gibt Dateiname ohne Dateitypendung wider (test.php -> test).
      return substr($pFullFilename, strrpos($pFullFilename, "."));
    }
  }

  /**
   * Ueberprueft, ob der angegebene Pfad existiert. Erzeugt ihn, falls nicht.
   *
   * @param string $pPathThatShouldExist
   *   Der Pfad der existieren soll, spaetestens nach Aufruf dieser Methode.
   * @param int $pPermissions
   *   chmod mit dem Datei Pfad erzeugt werden soll. (0766, falls kein chmod
   *   angegeben ist )
   *
   * @return bool
   *   True on success, false on failure.
   */
  public function createLocalPathIfNotExisting(
    string $pPathThatShouldExist,
    int $pPermissions = 0766
  ): bool {
    if (!is_dir($pPathThatShouldExist)) {
      return mkdir($pPathThatShouldExist, $pPermissions, TRUE);
    }
    else {
      return TRUE;
    }
  }

  /**
   * Ueberprueft, ob die angegebene Datei existiert. Erzeugt sie, falls nicht.
   *
   * @param string $pFileThatShouldExist
   *   Die Datei, die existieren soll, spaetestens nach Aufruf dieser Methode.
   * @param int $pPermissions
   *   chmod mit dem Datei erzeugt werden soll. (0766, falls kein chmod
   *   angegeben ist )
   */
  public function createLocalFileIfNotExists(
    string $pFileThatShouldExist,
    int $pPermissions = 0766
  ) {
    if (
        !file_exists($pFileThatShouldExist) ||
        !is_file($pFileThatShouldExist)
    ) {
      // Enthaelt Datei Pfadangabe?
      if (strpos($pFileThatShouldExist, '/') !== FALSE) {
        // Pfad extrahieren.
        $pathThatShouldExist = substr(
          $pFileThatShouldExist,
          0,
          strrpos($pFileThatShouldExist, '/')
        );
        $this->createLocalPathIfNotExisting($pathThatShouldExist);
      }

      // Erzeugt Datei.
      $file_handle = fopen(
        $pFileThatShouldExist,
        "w"
      ) or die('Permission error');
      fclose($file_handle);

      // Rechte setzen.
      chmod($pFileThatShouldExist, $pPermissions);
    }
  }

  /**
   * Tests, if file exists.
   *
   * @param string $pFile
   *   The file to test, if it exists. Must be complete path.
   *
   * @return bool
   *   True if file exists. False, if file does not exist.
   */
  public function doesFileExist(string $pFile): bool {
    return file_exists($pFile);
  }

  /**
   * Delete all files starting with passed Directory/filename.
   *
   * @param string $pPathAndFileNameStart
   *   The path and start of filename of files to delte.
   *   e.g.: "/srv/www/vhosts/.../directory/fileNameStart".
   */
  public function deleteAllFilesStartingWith(string $pPathAndFileNameStart) {
    $allFilesStartingWithPattern = glob($pPathAndFileNameStart . "*");
    array_map('unlink', $allFilesStartingWithPattern);
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
    $phrase = $phrase . " -  \"said FileUtils\"";
    return $phrase;
  }


}
