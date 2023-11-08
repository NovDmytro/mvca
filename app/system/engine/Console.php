<?php

namespace Engine;

use Services\Config;

class Console
{
    private Config $config;

    public function __construct(
        Config $config,
    )
    {
        $this->config = $config;
    }

    public function render(): array
    {
        $debug = Debug::init();
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        $memory = $debug->getMemory();
        $view['memory'] = @round(
                $memory / pow(1024, ($i = floor(log($memory, 1024)))),
                2
            ) . ' ' . $unit[$i];
        $view['initTime'] = $debug->getInitTime();
        $view['executionTime'] = $debug->getExecutionTime();
        $view['sources'] = $debug->getSources();
        $view['reports'] = $debug->getReports();
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        return $view;
    }
}