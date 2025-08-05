<?php

namespace Samples\C; //Samples - is the main folder src/Samples; C - is the controllers folder src/Samples/C

use Samples\M;         //Models namespace
use Engine\Config;   //Class that contains all configuration data
use Engine\Output;   //Class that work with view
use Services\Request;  //Singleton Class that handles $_GET $_POST etc.

/* Unused in this sample, but can be added:
use Engine\Debug;      //Singleton Class that allows to have additional debug
use Services\Cookies;  //Class that handles cookies
use Services\Util;     //Class with helpers
use Services\Crypto;   //Class with few crypto methods
*/

class BasicsController
{
    private M\BasicsModel $basicsModel;
    private Config $config;
    private Output $output;
    /* Unused in this sample, but can be added:
    private Cookies $cookies;
    private Util $util;
    private Crypto $crypto;
    */
    public function __construct(
        M\BasicsModel $basicsModel,
        Config $config,
        Output $output,
        /* Unused in this sample, but can be added:
        Cookies $cookies,
        Util $util,
        Crypto $crypto,
        */
    )
    {
        $this->basicsModel = $basicsModel;
        $this->config = $config;
        $this->output = $output;
        /* Unused in this sample, but can be added:
        $this->cookies = $cookies;
        $this->util = $util;
        $this->crypto = $crypto;
        */
    }

    /* This is controllers method "main" that can be accessed from url: Samples-Basics-main
    Where:
    Samples is a directory name src/Samples
    Basics is this controllers filename part src/Samples/C/BasicsController.php and ClassName part BasicsController
    main is a methods name that will be called
    */
    public function main(): void
    {
		/* This is request singleton init
         */
		$request = Request::init();
        /* This is model example, please visit src/Samples/M/BasicsModel.php right now
         */
        $example=$this->basicsModel->getExampleById($request->GET('var1', 'int'));

        /* This is view example, we can simply echo some data or json from this controller or run view
        To run view we need to use Output class and load method
        $this->output->load('route', 'data array', 'settings array');

        'route' in this sample is "Samples/Basics"
        Where:
        Samples is a directory name src/Samples
        Basics is view filename part src/Samples/V/BasicsView.php

        'data array' is the array that became variables in models,
        for example $view['foo']='123'; will be $foo='123'; in view
        and $view['bar']['key']='123'; will be $bar['key']='123'; in view

        'settings array' can redeclare default view settings
        header - is the header path, set empty if you don't need header in this view
        footer - is the footer path, set empty if you don't need footer in this view
        language - is language that Service Translator will try to use. To translate something you need to
        use double curly braces, for example {{translateMe}}, but dont forget to set translation in system/translations

        default settings is stored in config.php
            "defaultLanguage" => "en",
            "defaultHeader" => "src/Common/V/Header.php",
            "defaultFooter" => "src/Common/V/Footer.php",
         */
        $view['modelExample']=$example; //$example is array, because model returns an array
        $view['someString']='Some string data'; //Add some data to $someString


        $view['title'] = '{{Basics sample}} - MVCA'; //This is title because by default we use $title in header
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Basics", $view);
    }
}