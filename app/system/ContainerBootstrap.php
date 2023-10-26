<?php
class ContainerBootstrap
{
// Bootstrap container objects: (It is not necessary to specify, it is just helps IDE to work proper)
    static \Services\Config $config;
    static \Services\Database $database;
    static \Services\Logger $logger;
    static \Services\Output $output;
    static \Services\Cookies $cookies;
    static \Services\Util $util;
    static \Services\Crypto $crypto;
}