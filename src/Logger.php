<?php

namespace Glamus\Utils;

use Glamus\Utils\FileUtils;
use Glamus\Utils\DateUtils;

/**
 * Logger used to create logfiles for glamus.
 *
 * Verbose log loggt alle information in
 *  ..\logs\log.txt
 * error log loggt error und Probleme in
 *  ..\logs\errorLog.txt.
 *
 * 1.0.0        2021-05-18  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @author Patrick Michiels <michiels@glamus.de>
 */
class Logger {

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
     * Logger constructor.
     *
     * @param string $pLogDirectoryPath
     *   The path where logfiles should created in.
     */
    public function __construct(string $pLogDirectoryPath) {
        $this->logDirectoryPath = $pLogDirectoryPath . "/logs";
        $this->verboseLogFile = $this->logDirectoryPath . "/log.txt";
        $this->errorLogFile = $this->logDirectoryPath . "/errorLog.txt";

        $this->createLogFilesIfNotExisting();
    }

    /**
     * Stellt sicher, dass alle logDateien existieren.
     */
    private function createLogFilesIfNotExisting() {

        require_once __DIR__ . '/FileUtils.php';
        $fileUtils = new FileUtils();
        $fileUtils->createLocalFileIfNotExists($this->verboseLogFile);
        $fileUtils->createLocalFileIfNotExists($this->errorLogFile);
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
        // Get DateUtils to create LogFileTimeStamp.
        require_once __DIR__ . '/DateUtils.php';
        $dateUtils = new DateUtils();

        // Komplette logmessage bauen.
        $fullLogEntry = "[" . $dateUtils->getLogTimeStamp() .
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
    }

}
