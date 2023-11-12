<?php
namespace Samples\C;
use Engine\Config;
use Engine\Output;

class OutputController
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

    public function main(): void
    {
        $view['sampleString']='Sample string';
        $view['sampleArray']=[1=>'one',2=>'two'];
        $view['title'] = '{{Translation sample}} - MVCA';
        $view['config']['charset'] = $this->config->get('charset');
        $settings['language']=$this->config->get('defaultLanguage');
        $settings['header']=$this->config->get('defaultHeader');
        $settings['footer']=$this->config->get('defaultFooter');
        $this->output->load("Samples/Output", $view,$settings);
    }
}