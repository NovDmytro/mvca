<?php
$settings['development']=[
	    "database_url" => "pdo-mysql://root:123@127.0.0.1:3306/mvcTable",
	    "charset" => "UTF-8",
	    "defaultLang" => "uk",

        "crypter_key" => '$secret%#123456',

        "email_host" => "",
        "email_name" => "",
        "email_pass" => "",
        "email_port" => "",
        "email_from" => "",

        "system_default_time_zone" => "Europe/Kyiv",
        "system_debug_console" => false,
        "system_execution_time" => microtime(true),
        "system_cache_version" => 0001,
        "system_allow_forms_without_csrf" => false,

        "template_header" => "src/common/view/Header.php",
        "template_footer" => "src/common/view/Footer.php",

        "log_path_warning" => "system/logs/warning.log",
        "log_path_error" => "system/logs/error.log",
        "log_path_notice" => "system/logs/notice.log",
        "log_path_unknown_error" => "system/logs/unknown_error.log"
    ];
/*$settings['production']=[
        "system_default_time_zone" => "Europe/Kyiv",
        "system_debug_console" => true,
        "system_execution_time" => microtime(true),
        "system_cache_version" => 0001, // Refresh frontend cache
        "system_allow_forms_without_csrf" => false,
    ];
*/