<?php

namespace Welcome\C;

use Engine\Config;
use Engine\Output;

class WelcomeController
{
    private Config $config;
    private Output $output;

    public function __construct(
        Config   $config,
        Output   $output,
    )
    {
        $this->config = $config;
        $this->output = $output;
    }

    public function main(): void
    {
        $view['title'] = '{{Welcome}} - mvca';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Welcome/Welcome", $view, ['language'=>$this->config->get('defaultLanguage')]);
   }
}
