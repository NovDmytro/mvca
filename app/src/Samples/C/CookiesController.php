<?php

namespace Samples\C;

use Engine\Config;
use Engine\Output;
use Services\Cookies;


class CookiesController
{
    private Config $config;
    private Output $output;
    private Cookies $cookies;

    public function __construct(
        Config   $config,
        Output   $output,
        Cookies   $cookies,
    )
    {
        //Services
        $this->config = $config;
        $this->output = $output;
        $this->cookies = $cookies;
    }
    public function main(): void
    {
        $view['testCookie']=$this->cookies->get('test');
        $view['title'] = '{{Cookies samples}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Cookies", $view, ['language'=>$this->config->get('defaultLanguage')]);
    }

    public function setTest(): void
    {
        $this->cookies->set('test','123');
        header("Location: /Samples-Cookies-main");
    }

    public function delTest(): void
    {
        $this->cookies->del('test');
        header("Location: /Samples-Cookies-main");
    }

}