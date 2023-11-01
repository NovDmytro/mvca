<?php

namespace Index\C;
use Engine\Debug;
use Index\M;

use Services\Config;
use Services\Database;
use Services\Output;
use Services\Cookies;
use Services\Request;
use Services\Util;
use Services\Crypto;

class IndexController
{
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
           * This is Request example
           * You can add filters (int,dec,hex,email,latin,varchar,html) and case (low,up),
           *  examples: $request->POST('example','email','low'); $request->POST('id','int');
           * You can use: $request->GET $request->POST $request->COOKIE $request->SERVER $request->JSON
           */
        $request = Request::init(); //!!!IMPORTANT init Request before use!
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
        }


        /*
        * This is view load example
        *
        */
        $view['title'] = 'mvca';
        $view['config']['language'] = 'en';
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Index/Index", $view, ['language'=>'en']);

   }
}
