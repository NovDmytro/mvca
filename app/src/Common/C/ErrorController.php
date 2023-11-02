<?php

namespace Common\C;

use Services\Config;
use Services\Cookies;
use Services\Output;
use Services\Request;

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
        $request = Request::init();

        $view['config']['lang'] = $this->config->get('defaultLang');
        $view['title'] = '{{Error404}} - 123';



       // header($request->SERVER('SERVER_PROTOCOL') . " 404 Not Found");
        $this->output->load("Common/Error404", $view, $view['config']['lang']);
    }
}
