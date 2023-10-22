<?php

namespace Service;

class Logger
{

    public $error_handle;

    private $_error_log_path;
    private $_notice_log_path;
    private $_warning_log_path;
    private $_unknown_errors_log_path;

    public function __construct($error_log_path, $notice_log_path, $warning_log_path, $unknown_errors_log_path)
    {
        $this->_error_log_path = $error_log_path;
        $this->_notice_log_path = $notice_log_path;
        $this->_warning_log_path = $warning_log_path;
        $this->_unknown_errors_log_path = $unknown_errors_log_path;
    }

    public function myErrorHandler(string $errno, string $error_string, string $error_file, string $error_line)
    {
        $error = "Unknown";
        if ($errno === E_NOTICE || $errno === E_USER_NOTICE) {
            $error = 'Notice';
        }
        if ($errno === E_WARNING || $errno === E_USER_WARNING) {
            $error = 'Warning';
        }
        if ($errno === E_ERROR || $errno === E_USER_ERROR) {
            $error = 'Fatal Error';
        }

        $error_string_log = " *" . $error . "* " . $error_string . "\n - file: " . $error_file . "\n - on line: " . $error_line . "\n\n";
        $error_string_log = addslashes($error_string_log);

        if ($error === "Warning") {
            $this->warningHandler($error_string_log);
        }

        if ($error === "Notice") {
           // $this->noticeHandler($error_string_log);
        }

        if ($error === "Fatal Error") {
            $this->errorHandler($error_string_log);
        }

        if ($error === "Unknown") {
            $this->noticeHandler($error_string_log);
        }
    }

    public function myExceptionHandler($exception)
    {
        $exception_message = $exception->getMessage();

        $this->checkLogFile($this->_error_log_path);
        error_log($exception_message . "\n", 3, $this->_error_log_path);

        die($exception_message);
    }

    public function noticeHandler(string $error_string)
    {
        $this->checkLogFile($this->_notice_log_path);
        error_log($error_string . "\n", 3, $this->_notice_log_path);
    }

    public function warningHandler(string $error_string)
    {
        $this->checkLogFile($this->_warning_log_path);
        error_log($error_string . "\n", 3, $this->_warning_log_path);
    }

    public function errorHandler(string $error_string)
    {
        $this->checkLogFile($this->_error_log_path);
        error_log($error_string . "\n", 3, $this->_error_log_path);
    }

    public function unknownErrorHandler(string $error_string)
    {
        $this->checkLogFile($this->_unknown_errors_log_path);
        error_log($error_string . "\n", 3, $this->_unknown_errors_log_path);
    }

    public function checkLogFile(string $file_name)
    {
        if (!file_exists($file_name)) {
            $fileResource = fopen($file_name, "w");
            fclose($fileResource);
        }
    }
}
