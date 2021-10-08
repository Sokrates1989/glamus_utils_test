<?php

namespace FileUtils;

use JsonHandler\JsonHandler;

/**
 * Hilsklasse fuer diverse Funktionen rund um Dateien.
 *
 * Hilfsklasse mit leerem Konstruktor, die diverse Funktionen rund um Dateien
 * buendelt
 * z.Bsp.:  extrahiert Dateinamen aus Datei, extrahier Dateiendung aus Datei,
 *          erzeugt Dateien, erzeugt Pfade
 *          ...
 *
 * 5.0.0        2021-08-16  michiels
 *              - implemented banner2 handling.
 * 4.0.1        2021-07-19  michiels
 *              - fixed bug concerning deletion of old server result files.
 * 4.0.0        2021-07-19  michiels
 *              - added option to specify statement test behaviour.
 * 3.0.1        2021-07-19  michiels
 *              - prevent infinite locked autoconfig file bug.
 * 3.0.0        2021-07-16  michiels
 *              - implemented locking autoConfigFile before writing to file.
 * 2.0.0        2021-07-16  michiels
 *              - implemented unique server result file for each test, to
 *                prevent writing errors to wrong result file.
 * 1.2.2        2021-06-15  michiels
 *              - renaming JsonReader to JsonHandler.
 *                config update allows specifying cookieQuestion, iframe and
 *                banner -> adaption writing to autoconfig.
 * 1.2.1        2021-06-10  michiels
 *              - Documentation improvement.
 * 1.2.0        2021-06-09  michiels
 *              - Only run one test with all parties for each server and
 *                now testing all servers, with many browsers.
 * 1.1.1        2021-06-09  michiels
 *              - fixed code to apply to coding standards.
 * 1.1.0        2021-05-26  michiels
 *              - Variablenname optimiert, DEBUG Ausgabe entfernt
 * 1.0.1        2021-05-26  michiels
 *              - Dateierzeugung hinzugefuegt
 *                Erweiterung autoconfig
 * 1.0.0       2021-12-05  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @package BpB-Wahlomat
 * @subpackage Browsertest
 * @author Patrick Michiels <michiels@glamus.de>
 */
class FileUtils {

  /**
   * JsonHandler used to handle convert json.
   *
   * @var \JsonHandler\JsonHandler
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
   * Writes to the dynamic config file to create unit test from.
   *
   * Creates a valid json string from the passed vars and writes this to the
   *  dynamic config file to create unit test from
   * all passed vars are representations of the json config of the election
   *   to create a unit test for.
   *
   * @param string $pElectionPath
   *   Der Pfad wie in wahlen.json zu Wahl angegeben.
   * @param string $pUniqueServerResultFile
   *   The unique server result file where to write test output to.
   * @param string $pModuleDefinitionPath
   *   The path where the module definition can be found.
   * @param string $pServerID
   *   The Id of the server to check.
   *   Defined in election config by key "id" within array "servers".
   * @param string $pBaseurl
   *   The Url of the server to check.
   *   Defined in election config by key "baseurl" within array "servers".
   * @param string $pServerTitle
   *   The title that should be visible on the servers website.
   * @param string $pPlatform
   *   The platform on which the unit test should be performed (e.g.: WINDOWS)
   * @param string $pPlatformVersion
   *   The version of the platform the unit test should be performed
   *   (e.g.: latest).
   * @param string $pBrowserName
   *   Browsername to make unit test with.
   * @param string $pBrowserVersion
   *   Browserversion to make unit test with.
   * @param int $pPartyID
   *   Id of party.
   * @param string $pPartyName
   *   Name of party.
   * @param bool $pTestStatements
   *   Whether statements of parties should be checked or not.
   * @param bool $pDevelopmentMode
   *   Sets test to development mode. If True: ip address will not be
   *   retrieved and status mail will only be send to developers.
   * @param array $pCookieQuestion
   *   Used to determine if the server to test asks whether to accept cookies
   *   and what button to click and if an iframe is used.
   *   look at example.json "cookieQuestion" to find out more about this.
   * @param array $pIframe
   *   Used to determine if the server to test uses an iframe.
   *   look at example.json "iframe" to find out more about this.
   * @param array $pBanner
   *   Used to determine if the server to test asks some sort of banner question
   *    that interferes with the unit test. It also determines if what button
   *    to click to hide banner and if the server uses an iframe for the banner.
   *   look at example.json "banner" to find out more about this.
   * @param array $pBanner2
   *   Like $pBanner. Used, if the installation shows/uses another banner, that
   *   interferes with the test.
   *
   * @return bool
   *   Ist schreiben zur dynamischen json Datei erfolgreich gewesen?
   */
  public function writeToDynamicJsonConfigFile(
    string $pElectionPath,
    string $pUniqueServerResultFile,
    string $pModuleDefinitionPath,
    string $pServerID,
    string $pBaseurl,
    string $pServerTitle,
    string $pPlatform,
    string $pPlatformVersion,
    string $pBrowserName,
    string $pBrowserVersion,
    int $pPartyID,
    string $pPartyName,
    bool $pTestStatements,
    bool $pDevelopmentMode,
    array $pCookieQuestion = [],
    array $pIframe = [],
    array $pBanner = [],
    array $pBanner2 = []
  ): bool {

    $dynamicJsonConfigFileToWriteTo = dirname(__DIR__) . '/tests/automaticallyGeneratedConfigFiles/doNOTchange/autoConfig.json';

    // Create json from passed vars.
    $jsonStringToWriteToFile = '{
      "electionPath":"' . $pElectionPath . '",
      "serverResultFile":"' . $pUniqueServerResultFile . '",
      "moduleDefinitionPath":"' . $pModuleDefinitionPath . '",
      "serverID":"' . $pServerID . '",
      "baseurl":"' . $pBaseurl . '",
      "serverTitle":"' . $pServerTitle . '",
      "platform":"' . $pPlatform . '",
      "platformVersion":"' . $pPlatformVersion . '",
      "browserName":"' . $pBrowserName . '",
      "browserVersion":"' . $pBrowserVersion . '",
      "partyID":"' . $pPartyID . '",
      "partyName":"' . $pPartyName . '",
      "testStatements":"' . $pTestStatements . '",
      "developmentMode":"' . $pDevelopmentMode . '",';

    if (!empty($pCookieQuestion)) {
      $jsonStringToWriteToFile .= '"cookieQuestion":' . $this->jsonHandler->convertToJson($pCookieQuestion) . ',';
    }

    if (!empty($pIframe)) {
      $jsonStringToWriteToFile .= '"iframe":' . $this->jsonHandler->convertToJson($pIframe) . ',';
    }

    if (!empty($pBanner)) {
      $jsonStringToWriteToFile .= '"banner":' . $this->jsonHandler->convertToJson($pBanner) . ',';
    }

    if (!empty($pBanner2)) {
      $jsonStringToWriteToFile .= '"banner2":' . $this->jsonHandler->convertToJson($pBanner2) . ',';
    }

    // Remove last comma from string.
    $jsonStringToWriteToFile = rtrim($jsonStringToWriteToFile, ",");

    $jsonStringToWriteToFile .= '}';

    // Is file locked?
    // to prevent endless loop -> unlock writing to autoconfig file, after
    // 100 attempts.
    $i = 0;
    $isContentWrittenToFile = FALSE;
    while (!$isContentWrittenToFile) {
      if (!$this->isAutoConfigFileLocked()) {
        // File is not locked!
        // Lock file.
        $this->lockAutoConfigFile();

        // Write content to file.
        file_put_contents(
          $dynamicJsonConfigFileToWriteTo,
          $jsonStringToWriteToFile
        );

        // Indicate, that content has been written to autoconfig file.
        $isContentWrittenToFile = TRUE;
      }
      else {
        // File is locked!
        // wait a little and then try to write to config file again.
        usleep(300000);

        // Increment iteration.
        $i++;

        // To prevent endless loop -> unlock writing to autoconfig file, after
        // 100 attempts.
        if ($i > 100) {
          // Unlock writing to autoconfig file.
          $this->unLockAutoConfigFile();

          // Wait a little and then try to write to config file again.
          usleep(300000);
        }
      }
    }

    return TRUE;
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
   * Test if autoconfig file is locked.
   */
  public function isAutoConfigFileLocked(): bool {

    // Get locked file.
    $lockedFile = $this->getLockedFile();

    $content = file_get_contents($lockedFile);

    if ($content == "locked") {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Lock autoconfig file.
   */
  public function lockAutoConfigFile() {
    // Get locked file.
    $lockedFile = $this->getLockedFile();
    file_put_contents($lockedFile, "locked");
  }

  /**
   * Unlock autoconfig file.
   */
  public function unLockAutoConfigFile() {
    // Get locked file.
    $lockedFile = $this->getLockedFile();
    file_put_contents($lockedFile, "unLocked");
  }

  /**
   * Returns locked file and creates it before, if it does not exist yet.
   *
   * @return string
   *   The lock file.
   */
  private function getLockedFile(): string {
    // Build path where isLocked file should be.
    $lockedFile = dirname(__DIR__) . '/tests/automaticallyGeneratedConfigFiles/doNOTchange/.isLocked';

    // Create file, if it does not exist.
    $this->createLocalFileIfNotExists($lockedFile);

    return $lockedFile;
  }

}
