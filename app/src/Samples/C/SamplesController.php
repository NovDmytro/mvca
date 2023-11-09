<?php

namespace Samples\C;
use Engine\Debug;
use Samples\M;

use Services\Config;
use Services\Output;

class SamplesController
{
    private Config $config;
    private Output $output;

    public function __construct(
        Config   $config,
        Output   $output,
    )
    {
        //Services
        $this->config = $config;
        $this->output = $output;
    }
    public function main(): void
    {
        $view['title'] = '{{Samples}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Samples", $view, ['language'=>$this->config->get('defaultLanguage')]);
   }
}