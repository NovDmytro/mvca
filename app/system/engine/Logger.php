<?php

namespace Engine;

use Engine\Debug;

class Logger
{
    private string $logPathFatalError;
    private string $logPathNotice;
    private string $logPathWarning;
    private string $logPathUnknownError;

    public function __construct($logPathFatalError, $logPathNotice, $logPathWarning, $logPathUnknownError)
    {
        $this->logPathFatalError = $logPathFatalError;
        $this->logPathNotice = $logPathNotice;
        $this->logPathWarning = $logPathWarning;
        $this->logPathUnknownError = $logPathUnknownError;
    }
    public function errorHandler(string $errNo, string $errorString, string $errorFile, string $errorLine): void
    {
        $error = 'Unknown';
        if (
            $errNo == E_NOTICE ||
            $errNo == E_USER_NOTICE ||
            $errNo == E_STRICT ||
            $errNo == E_RECOVERABLE_ERROR ||
            $errNo == E_DEPRECATED ||
            $errNo == E_USER_DEPRECATED
        ) {
            $error = 'Notice';
        }
        if (
            $errNo == E_WARNING ||
            $errNo == E_CORE_WARNING ||
            $errNo == E_COMPILE_WARNING ||
            $errNo == E_USER_WARNING
        ) {
            $error = 'Warning';
        }
        if (
            $errNo == E_ERROR ||
            $errNo == E_CORE_ERROR ||
            $errNo == E_COMPILE_ERROR ||
            $errNo == E_USER_ERROR ||
            $errNo == E_PARSE
        ) {
            $error = 'FatalError';
        }

        $errorStringLog = $error . ' -> ' . $errorFile . ' (' . $errorLine . ') | ' . $errorString;
        $errorStringLog = addslashes($errorStringLog);
        if ($error == 'Warning') {
            $this->warningHandler($errorStringLog);
        }
        if ($error == 'Notice') {
            $this->noticeHandler($errorStringLog);
        }
        if ($error == 'FatalError') {
            $this->fatalErrorHandler($errorStringLog);
        }
        if ($error == 'Unknown') {
            $this->unknownErrorHandler($errorStringLog);
        }
    }

    public function checkLogFile(string $fileName): void
    {
        if (!file_exists($fileName)) {
            $fileResource = fopen($fileName, 'w');
            fclose($fileResource);
        }
    }

    public function warningHandler(string $errorString): void
    {
        $debug=Debug::init();
        if($debug->enabled()){
            $debug->addReport($errorString,'PHP','Warning');
        }
        $this->checkLogFile($this->logPathWarning);
        error_log($errorString . "\n", 3, $this->logPathWarning);
    }

    public function noticeHandler(string $errorString): void
    {
        $debug=Debug::init();
        if($debug->enabled()){
            $debug->addReport($errorString,'PHP','Notice');
        }
        $this->checkLogFile($this->logPathNotice);
        error_log($errorString . "\n", 3, $this->logPathNotice);
    }

    public function fatalErrorHandler(string $errorString): void
    {
        $debug=Debug::init();
        if($debug->enabled()){
            $debug->addReport($errorString,'PHP','FatalError');
        }
        $this->checkLogFile($this->logPathFatalError);
        error_log($errorString . "\n", 3, $this->logPathFatalError);
    }

    public function unknownErrorHandler(string $errorString): void
    {
        $debug=Debug::init();
        if($debug->enabled()){
            $debug->addReport($errorString,'PHP','Unknown');
        }
        $this->checkLogFile($this->logPathUnknownError);
        error_log($errorString . "\n", 3, $this->logPathUnknownError);
    }

    public function exceptionHandler($exception): void
    {
        $exceptionMessage = $exception->getMessage();
        $debug=Debug::init();
        if($debug->enabled()){
            $debug->addReport($exceptionMessage,'PHP','Exception');
        }
        $this->checkLogFile($this->logPathFatalError);
        error_log($exceptionMessage . "\n", 3, $this->logPathFatalError);
        die($exceptionMessage);
    }
}
