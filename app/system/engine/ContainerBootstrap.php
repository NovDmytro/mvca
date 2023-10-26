<?php
namespace Engine;
class ContainerBootstrap
{
// Bootstrap container objects: (It is not necessary to specify, it is just helps IDE to work proper)
    static \Services\Config $Config;
    static \Services\Database $Database;
    static \Services\Logger $Logger;
    static \Services\Output $Output;
    static \Services\Cookies $Cookies;
    static \Services\Util $Util;
    static \Services\Crypto $Crypto;
}