<?php

namespace Core\Errors\C;
use Engine\Config;
use Engine\Output;
use Services\Request;

class ErrorController
{
    private Output $output;
    private Config $config;

    public function __construct(Output $output, Config $config)
    {
        $this->output = $output;
        $this->config = $config;
    }

    public function e404(): void
    {
        $request = Request::init();

        $view['title'] = '{{Error404}}';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');

        header($request->SERVER('SERVER_PROTOCOL') . " 404 Not Found");
        $this->output->load("Errors/Error404", $view, ['language'=>$this->config->get('defaultLanguage')]);
    }
}