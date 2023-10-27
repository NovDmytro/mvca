<?php

namespace Services;

class Logger
{

    private string $fatalErrorLogPath;
    private string $noticeLogPath;
    private string $warningLogPath;
    private string $unknownErrorsLogPath;

    public function __construct($fatalErrorLogPath, $noticeLogPath, $warningLogPath, $unknownErrorsLogPath)
    {
        $this->fatalErrorLogPath = $fatalErrorLogPath;
        $this->noticeLogPath = $noticeLogPath;
        $this->warningLogPath = $warningLogPath;
        $this->unknownErrorsLogPath = $unknownErrorsLogPath;
    }

    public function errorHandler(string $errNo, string $errorString, string $errorFile, string $errorLine): void
    {
        $error = 'Unknown';
        if ($errNo === E_NOTICE || $errNo === E_USER_NOTICE) {
            $error = 'Notice';
        }
        if ($errNo === E_WARNING || $errNo === E_USER_WARNING) {
            $error = 'Warning';
        }
        if ($errNo === E_ERROR || $errNo === E_USER_ERROR) {
            $error = 'FatalError';
        }

        $errorStringLog = $error . ' -> ' . $errorFile . ' (' . $errorLine . ') | ' . $errorString;
        $errorStringLog = addslashes($errorStringLog);

        if ($error === 'Warning') {
            $this->warningHandler($errorStringLog);
        }

        if ($error === 'Notice') {
            $this->noticeHandler($errorStringLog);
        }

        if ($error === 'FatalError') {
            $this->fatalErrorHandler($errorStringLog);
        }

        if ($error === 'Unknown') {
            $this->unknownErrorHandler($errorStringLog);
        }
    }

    public function warningHandler(string $errorString): void
    {
        $this->checkLogFile($this->warningLogPath);
        error_log($errorString . "\n", 3, $this->warningLogPath);
    }

    public function checkLogFile(string $fileName): void
    {
        if (!file_exists($fileName)) {
            $fileResource = fopen($fileName, 'w');
            fclose($fileResource);
        }
    }

    public function noticeHandler(string $errorString): void
    {
        $this->checkLogFile($this->noticeLogPath);
        error_log($errorString . "\n", 3, $this->noticeLogPath);
    }

    public function fatalErrorHandler(string $errorString): void
    {
        $this->checkLogFile($this->fatalErrorLogPath);
        error_log($errorString . "\n", 3, $this->fatalErrorLogPath);
    }

    public function unknownErrorHandler(string $errorString): void
    {
        $this->checkLogFile($this->unknownErrorsLogPath);
        error_log($errorString . "\n", 3, $this->unknownErrorsLogPath);
    }

    public function exceptionHandler($exception): void
    {
        $exceptionMessage = $exception->getMessage();

        $this->checkLogFile($this->fatalErrorLogPath);
        error_log($exceptionMessage . "\n", 3, $this->fatalErrorLogPath);

        die($exceptionMessage);
    }
}
