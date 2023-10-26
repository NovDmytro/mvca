<?php

namespace Common\C;

use Common\M;
use Services\Config;
use Services\Cookies;
use Services\Output;

class ErrorController
{
    public function __construct(Output $output, Cookies $cookies, Config $config)
    {
        $this->output = $output;
        $this->cookies = $cookies;
        $this->config = $config;
    }

    public function error404(): void
    {


        $view['config']['lang'] = $this->config->get('defaultLang');
        $view['title'] = '{{Error404}} - 123';
        $this->output->load("Common/Error404", $view, $view['config']['lang']);
    }
}
