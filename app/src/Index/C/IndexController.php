<?php

namespace Index\C;
use Engine\Debug;
use Index\M;

use Engine\Config;
use Engine\Output;
use Services\Cookies;
use Services\Request;
use Services\Util;
use Services\Crypto;

class IndexController
{
    private M\IndexModel $indexModel;
    private Config $config;
    private Output $output;
    private Cookies $cookies;
    private Util $util;
    private Crypto $crypto;

    public function __construct(
        //Model example
        M\IndexModel $indexModel,
        //Services
        Config   $config,
        Output   $output,
        Cookies  $cookies,
        Util     $util,
        Crypto   $crypto
    )
    {
        //Model example
        $this->indexModel=$indexModel;
        //Services
        $this->config = $config;    //Configuration container
        $this->output = $output;    //View output class
        $this->cookies = $cookies;  //Cookies class
        $this->util = $util;        //Helpers
        $this->crypto = $crypto;    //Encrypt/Decrypt tool
    }

    public function main(): void
    {
        $view['headText']= 'This is examples file, located at: app/src/Index/C/IndexController.php';


        /*
        * This is model example
        */
        $view['indexModel']=$this->indexModel->getProductById(1);

        /*
        * This is crypto example
        */
        $secret='Super secret data';
        $encSecret=$this->crypto->Encrypt($secret);
        $decSecret=$this->crypto->Decrypt($encSecret);
        $view['cryptoExample']=['encrypted'=>$encSecret,'decrypted'=>$decSecret];

        /*
        * This is debug example
        */
        $debug = Debug::init();
        if ($debug->enabled()) {
            $debug->addReport($view['indexModel'], 'IndexController', 'Test');
            $debug->addReport($view['indexModel'], 'Test1', 'Test2');
            $debug->addReport($view['indexModel'], 'Test2', 'Test2');
            $debug->addReport($view['indexModel'], 'Test3', 'Test2');
            $debug->addReport($view['indexModel'], 'Test4', 'Test2');
        }
        /*
        * This is view load example
        *
        */
        $view['title'] = 'mvca';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Index/Index", $view, ['language'=>$this->config->get('defaultLanguage')]);

   }
}
