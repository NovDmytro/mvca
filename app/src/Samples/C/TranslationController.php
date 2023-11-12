<?php
namespace Samples\C;
use Engine\Config;
use Engine\Output;
use Services\Request;

class TranslationController
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
        $request = Request::init();
        $useLanguage=$request->GET('language','latin','low');
        $view['title'] = '{{Translation sample}} - MVCA';
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Translation", $view,['language'=>$useLanguage]);
    }
}