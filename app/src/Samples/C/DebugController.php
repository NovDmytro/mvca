<?php
namespace Samples\C;
use Engine\Config;
use Engine\Output;
use Engine\Debug;

class DebugController
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
        $debug=Debug::init();
        if($debug->enabled()){
            $debug->addReport('some string','DebugSample','Info');
            $debug->addReport(['some'=>'array'],'DebugSample','Info');
        }
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Debug");
    }
}