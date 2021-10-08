<?php

namespace Logger;

use FileUtils\FileUtils;

/**
 * Loggt information zum WebDriverTester.
 *
 * Verbose log loggt alle information in
 *  data\[WahlPfadWieInConfigAngegeben]\logs\log.txt
 * error log loggt error und Probleme in
 *  data\[WahlPfadWieInConfigAngegeben]\logs\errorLog.txt.
 *
 *
 * 1.2.0        2021-07-02  michiels
 *              - added option to log tuned, manipulated Tests.
 *                manipulated means, that every second choice is set to true.
 * 1.1.0        2021-07-01  michiels
 *              - added option to log tuned Tests.
 * 1.0.6        2021-06-14  michiels
 *              - Added option to Logger for Debug purposes.
 *                also outputs every log content.
 * 1.0.5        2021-06-10  michiels
 *              - Log Output beautified.
 * 1.0.4        2021-06-09  michiels
 *              - bug fix: strToLower compared with capitals can never work
 * 1.0.3        2021-06-09  michiels
 *              - fixed code to apply to coding standards.
 * 1.0.2        2021-05-26  michiels
 *              - Ausgabe log fuer Gewichtung hinzugefuegt
 * 1.0.1        2021-05-26  michiels
 *              - Diverse Log Methoden implementiert
 *                browserlog und resultlog
 *                Konstruktoir ueberprueft existenz der Dateien und erzeugt sie
 * 1.0.0        2021-05-18  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @package BpB-Wahlomat
 * @subpackage Browsertest
 * @author Patrick Michiels <michiels@glamus.de>
 */
class Logger {

  /**
   * Relative path to election as defined in config.
   *
   * @var string
   */
  private string $election;

  /**
   * Base path to all logfiles.
   *
   * @var string
   */
  private string $logDirectoryPath;

  /**
   * Complete path to Verboselogfile.
   *
   * Contains detailed information about unit tests and the whole program.
   *
   * @var string
   */
  private string $verboseLogFile;

  /**
   * Complete path to Errorlogfile.
   *
   * Contains information of errors concerning unit tests and the whole program.
   *
   * @var string
   */
  private string $errorLogFile;

  /**
   * Complete path to PartyResultlogfile.
   *
   * Contains information of the expected and actual ratings when emulting
   * running through all theses with votematch.
   *
   * @var string
   */
  private string $partyResultLogFile;

  /**
   * Complete path to Browserlogfile.
   *
   * Contains information of the unit test browser log.
   *
   * @var string
   */
  private string $browserResultLogFile;

  /**
   * Determines if Logger is in Debug mode.
   *
   * If the logger is in the debug mode, every log content is also printed.
   *
   * @var bool
   */
  private bool $isInDebugMode;

  /**
   * Logger constructor.
   *
   * @param string $pElectionPath
   *   der Pfad zur Wahl fuer die ein log Eintrag erstellt werden soll
   *   in wahlen.json: key: path.
   * @param bool $pIsInDebugMode
   *   Option to put Logger in Debug Mode. If set to true every log content
   *    is also printed.
   */
  public function __construct(string $pElectionPath, bool $pIsInDebugMode = FALSE) {
    $this->election = $pElectionPath;
    $this->logDirectoryPath = dirname(__DIR__) . '/data/' .
      $pElectionPath;
    $this->verboseLogFile = $this->logDirectoryPath . "/log.txt";
    $this->errorLogFile = $this->logDirectoryPath . "/errorLog.txt";
    $this->partyResultLogFile = $this->logDirectoryPath . "/resultLog.txt";
    $this->browserResultLogFile = $this->logDirectoryPath . "/browserlog.txt";

    $this->createLogFilesIfNotExisting();

    $this->isInDebugMode = $pIsInDebugMode;
  }

  /**
   * Stellt sicher, dass alle logDateien existieren.
   */
  private function createLogFilesIfNotExisting() {

    require_once __DIR__ . '/FileUtils.php';
    $fileUtils = new FileUtils();
    $fileUtils->createLocalFileIfNotExists($this->verboseLogFile);
    $fileUtils->createLocalFileIfNotExists($this->errorLogFile);
    $fileUtils->createLocalFileIfNotExists($this->partyResultLogFile);
    $fileUtils->createLocalFileIfNotExists($this->browserResultLogFile);
  }

  /**
   * Traegt einen neuen log Eintrag ein.
   *
   * Entscheidet selbst in welche logfile geschrieben werden soll
   * wenn ERROR, error, WARNING, oder warning wird zusaetzlich in errorLog
   * geschrieben.
   *
   * @param string $pLoglevel
   *   welcher Typ von LogMessage erstellt werden soll (FATAL_ERROR|VERBOSE
   *    |IGNORED|INFO|OK|
   *   WARNING|ERROR)
   * @param string $pLogMessage
   *   naehere Beschreibung des log-Inhalts.
   */
  public function log(
    string $pLoglevel,
    string $pLogMessage
  ) {
    // Komplette logmessage bauen.
    $fullLogEntry = "[" . date("Y-m-d H:i:s") .
      "] - [" . strtoupper($pLoglevel) . "] - [" . $pLogMessage .
      "]\n";

    // Sicher gehen, dass Log Directory existiert.
    if (!is_dir($this->logDirectoryPath)) {
      mkdir($this->logDirectoryPath, 0744, TRUE);
    }

    // Log Eintrag in logfiles schreiben.
    file_put_contents($this->verboseLogFile, $fullLogEntry, FILE_APPEND);
    if (strtolower($pLoglevel) == "warning" ||
          strtolower($pLoglevel) == "error" ||
          strtolower($pLoglevel) == "fatal_error") {
      file_put_contents($this->errorLogFile, $fullLogEntry, FILE_APPEND);
    }

    // Only output log content for debug purposes.
    if ($this->isInDebugMode) {
      echo $fullLogEntry . "\n<br/>";
    }
  }

  /**
   * Loggt Browser Results der Session des Webdrivers in browserlogDatei.
   *
   * @param array $pBrowserLogContent
   *   Array containing result of Browser Unit Test Log.
   */
  public function logBrowserResults(array $pBrowserLogContent) {
    $newLogIndicatorLogText = "\n[" . date("Y-m-d H:i:s") .
      "] - neuer Logeintrag\n";
    file_put_contents(
      $this->browserResultLogFile,
      $newLogIndicatorLogText,
      FILE_APPEND
    );

    // Only output log content for debug purposes.
    if ($this->isInDebugMode) {
      echo "\n<br/>\n<br/>BrowserLogfile:\n<br/>";
    }
    foreach ($pBrowserLogContent as $key => $logContentSeparated) {
      $logText = "[" . json_encode($key) . "] => [" .
        json_encode($logContentSeparated) . "]\n";
      file_put_contents(
        $this->browserResultLogFile,
        $logText,
        FILE_APPEND
      );

      // Only output log content for debug purposes.
      if ($this->isInDebugMode) {
        echo $logText . "\n<br/>";
      }
    }
  }

  /**
   * Schreibt Ueberschrift in LogDatei partyResultLogFile.
   *
   * @param string $pPartyNameTestIsRunningFor
   *   Der Name der Partei fuer die
   *   ein Test durchlaufen wird.
   * @param bool $pWeightEvery4thElementDouble
   *   Whether every 4th element should be wheighted twice.
   * @param bool $tunedTest
   *   Whether tuning result is being logged.
   * @param bool $pAfterChangingEverysecondAnswerToTrue
   *   Whether tuning result after changing every second answer to true is
   *   being logged.
   */
  public function logPartyResultHeading(
    string $pPartyNameTestIsRunningFor,
    bool $pWeightEvery4thElementDouble = FALSE,
    bool $tunedTest = FALSE,
    bool $pAfterChangingEverysecondAnswerToTrue = FALSE
  ) {
    $newPartyResultLogText = "\n\n[" . date("Y-m-d H:i:s") .
      "] - [neuer Log Eintrag] - ";

    if ($pAfterChangingEverysecondAnswerToTrue) {
      $newPartyResultLogText .= "Manipulierter, ";
    }

    if ($tunedTest) {
      $newPartyResultLogText .= " Getuneder ";
    }
    elseif ($pWeightEvery4thElementDouble) {
      $newPartyResultLogText .= " GEWICHTETER ";
    }

    $newPartyResultLogText .= "Test fuer Partei [" .
      $pPartyNameTestIsRunningFor . "] durchlaufen";
    file_put_contents(
      $this->partyResultLogFile,
      $newPartyResultLogText,
      FILE_APPEND
    );

    // Only output log content for debug purposes.
    if ($this->isInDebugMode) {
      echo "<br/><br/>" . $newPartyResultLogText . "<br/>\n";
    }
  }

  /**
   * Schreibt Resultat fuer Partei in partyResultLogFile.
   *
   * @param string $pPartyName
   *   Der Name der Partei.
   * @param string $pResult
   *   Das Ergebnis fuer diese Partei.
   * @param string $pExpectedRating
   *   The formatteed expected rating.
   */
  public function logPartyResult(
    string $pPartyName,
    string $pResult,
    string $pExpectedRating
  ) {

    $newPartyResultLogText = "\n[" . date("Y-m-d H:i:s") . "] - [" . $pPartyName .
      "]  - result: [" . $pResult . "] - [" . $pExpectedRating . " %] erwartet";
    file_put_contents(
      $this->partyResultLogFile,
      $newPartyResultLogText,
      FILE_APPEND
    );

    // Only output log content for debug purposes.
    if ($this->isInDebugMode) {
      echo $newPartyResultLogText . "<br/>\n";
    }
  }

}
