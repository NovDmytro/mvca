<?php

namespace Index\C;

use Index\M;
use Services\Output;
use Services\Cookies;
use Services\Config;

class IndexController
{
    public function __construct(Output $output, Cookies $cookies, Config $config)
    {
        $this->output = $output;
        $this->cookies = $cookies;
        $this->config = $config;
    }
    
    public function main(): void
    {
        $request = \Services\Request::init();
        echo $this->config->get('defaultTimezone');




        echo $request->GET('q');


        $indexModel = new M\IndexModel();
        $view['index'] = $indexModel->test();
        $view['title'] = '{{Index}} - 123';
        $this->output->load("Index/Index", $view, );

   }
}
