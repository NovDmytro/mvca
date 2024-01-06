<?php
namespace Samples\C;
use Engine\Config;
use Engine\Output;

class ConfigController
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
        $view['ENVIRONMENT']=ENVIRONMENT; //Current ENVIRONMENT in app/public/index.php
        $this->config->set('testSetting','123');
        $testConfigsArray=['arrTest1'=>1,'arrTest2'=>2];
        $this->config->setArray($testConfigsArray);
        $view['arrTest1']=$this->config->get('arrTest1');
        $view['arrTest2']=$this->config->get('arrTest2');
        $view['testSetting']=$this->config->get('testSetting');
        $view['supportedLanguages']=$this->config->get('supportedLanguages');
        $this->output->load("Samples/Config", $view);
    }
}