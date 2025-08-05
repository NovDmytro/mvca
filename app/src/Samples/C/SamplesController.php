<?php

namespace Samples\C;

use Engine\Config;
use Engine\Output;

class SamplesController
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
        $view['title'] = '{{Samples}} - mvca';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Samples", $view, ['language'=>$this->config->get('defaultLanguage')]);
   }
}
