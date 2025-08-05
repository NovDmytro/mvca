<?php
namespace Samples\C;
use Engine\Config;
use Engine\Output;

class RoutingController
{
    private Config $config;
    private Output $output;

    public function __construct(
        Config $config,
        Output $output,
    ){
        $this->config = $config;
        $this->output = $output;
    }


    public function sample(): void
    {
        $view['route']=$this->config->get('route');
        $view['title'] = '{{Routing sample}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Routing", $view);
    }
}