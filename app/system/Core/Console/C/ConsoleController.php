<?php

namespace Core\Console\C;

use Engine\Debug;
use Services\Config;
use Services\Cookies;
use Services\Crypto;
use Services\Output;
use Services\Util;

class ConsoleController
{
    public function __construct(
        //Services
        Config   $config,
        Output   $output,
        Cookies  $cookies,
        Util     $util,
        Crypto   $crypto
    )
    {
        //Model example
        //Services
        $this->config = $config;    //Configuration container
        $this->output = $output;    //View output class
        $this->cookies = $cookies;  //Cookies class
        $this->util = $util;        //Helpers
        $this->crypto = $crypto;    //Encrypt/Decrypt tool
    }

    public function main(): void
    {
        $request = \Services\Request::init(); //!!!IMPORTANT init Request before use!
        $view['requestExample']= '<h3>Request example</h3>';
        $view['requestExample'].= '<a href="/'.$this->config->get('route').'/v1/v2/v3/v4/v5/v6/?test1=123&test2=456">Click to go to test url</a><br>';
        $view['requestExample'].= 'First URL dir (route): <b>'.$request->GET('route').'</b><br>';
        $view['requestExample'].= 'Second URL dir (var1): <b>'.$request->GET('var1').'</b><br>';
        $view['requestExample'].= 'Third URL dir (var2): <b>'.$request->GET('var2').'</b><br>';
        $view['requestExample'].= 'Fourth URL dir (var3): <b>'.$request->GET('var3').'</b><br>';
        $view['requestExample'].= 'Fifth URL dir (var4): <b>'.$request->GET('var4').'</b><br>';
        $view['requestExample'].= 'Sixth URL dir (var5): <b>'.$request->GET('var5').'</b><br>';
        $view['requestExample'].= 'Seventh URL dir (var6): <b>'.$request->GET('var6').'</b><br>';
        $view['requestExample'].= 'test1: <b>'.$request->GET('test1').'</b><br>';
        $view['requestExample'].= 'test2: <b>'.$request->GET('test2').'</b><br>';



        $secret='Super secret data';
        $encSecret=$this->crypto->Encrypt($secret);
        $decSecret=$this->crypto->Decrypt($encSecret);
        $view['cryptoExample']=['encrypted'=>$encSecret,'decrypted'=>$decSecret];

        $debug = Debug::init();
        if ($debug->enabled()) {
            $debug->addReport($view['indexModel'], 'IndexController', 'Test');
        }



        $view['title'] = 'mvca';
        $view['config']['language'] = 'en';
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Index/Index", $view, ['language'=>'en','header'=>'','footer'=>'']);


   }
}
