<?php

namespace Samples\C;
use Engine\Debug;
use Samples\M;

use Engine\Config;
use Engine\Output;
use Services\Request; //Singleton malt whiskey


class RequestsController
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
    public function getSamples(): void
    {
        $request = Request::init();//!IMPORTANT init Request before use!

        $view['getDemoURL']='/'.$this->config->get('route').'/s1/s2/s3/s4/s5/s6/?querySample1=test1&querySample2=test2&intFilterSample=a1B2c3';

        $view['dirs']['route']= $request->GET('route');
        $view['dirs']['var1']= $request->GET('var1');
        $view['dirs']['var2']= $request->GET('var2');
        $view['dirs']['var3']= $request->GET('var3');
        $view['dirs']['var4']= $request->GET('var4');
        $view['dirs']['var5']= $request->GET('var5');
        $view['dirs']['var6']= $request->GET('var6');

        $view['query']['querySample1']= $request->GET('querySample1');
        $view['query']['querySample2']= $request->GET('querySample2');

        $view['query']['intFilterSample']= $request->GET('intFilterSample','int');

        $view['title'] = '{{Request GET samples}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/GETRequests", $view, ['language'=>$this->config->get('defaultLanguage')]);
    }

    public function postSamples(): void
    {
        $request = Request::init();//!IMPORTANT init Request before use!

        $view['postSample']['upCaseSample']= $request->POST('upCaseSample','varchar','up');
        $view['postSample']['emailSample']= $request->POST('emailSample','email','low');
        $view['postSample']['filteredNumbersSample']= $request->POST('filteredNumbersSample','int','low');

        $view['title'] = '{{Request POST samples}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/POSTRequests", $view, ['language'=>$this->config->get('defaultLanguage')]);
    }


    public function jsonSamples(): void
    {
        $request = Request::init();//!IMPORTANT init Request before use!
        if ($request->GET('jsonDemo') == 'yes') {
            echo json_encode(['id' => $request->JSON('id', 'int'), 'name' => $request->JSON('name', 'varchar')]);
        } else {
        $view['title'] = '{{Request JSON samples}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/JSONRequests", $view, ['language' => $this->config->get('defaultLanguage')]);
    }
    }



    public function serverSamples(): void
    {
        $request = Request::init();//!IMPORTANT init Request before use!
        $view['server']['REDIRECT_STATUS']=$request->SERVER('REDIRECT_STATUS');
        $view['server']['HTTP_HOST']=$request->SERVER('HTTP_HOST');
        $view['server']['HTTP_CONNECTION']=$request->SERVER('HTTP_CONNECTION');
        $view['server']['HTTP_CACHE_CONTROL']=$request->SERVER('HTTP_CACHE_CONTROL');
        $view['server']['HTTP_SEC_CH_UA']=$request->SERVER('HTTP_SEC_CH_UA');
        $view['server']['HTTP_SEC_CH_UA_MOBILE']=$request->SERVER('HTTP_SEC_CH_UA_MOBILE');
        $view['server']['HTTP_SEC_CH_UA_PLATFORM']=$request->SERVER('HTTP_SEC_CH_UA_PLATFORM');
        $view['server']['HTTP_DNT']=$request->SERVER('HTTP_DNT');
        $view['server']['HTTP_UPGRADE_INSECURE_REQUESTS']=$request->SERVER('HTTP_UPGRADE_INSECURE_REQUESTS');
        $view['server']['HTTP_USER_AGENT']=$request->SERVER('HTTP_USER_AGENT');
        $view['server']['HTTP_ACCEPT']=$request->SERVER('HTTP_ACCEPT');
        $view['server']['HTTP_SEC_FETCH_SITE']=$request->SERVER('HTTP_SEC_FETCH_SITE');
        $view['server']['HTTP_SEC_FETCH_MODE']=$request->SERVER('HTTP_SEC_FETCH_MODE');
        $view['server']['HTTP_SEC_FETCH_USER']=$request->SERVER('HTTP_SEC_FETCH_USER');
        $view['server']['HTTP_SEC_FETCH_DEST']=$request->SERVER('HTTP_SEC_FETCH_DEST');
        $view['server']['HTTP_REFERER']=$request->SERVER('HTTP_REFERER');
        $view['server']['HTTP_ACCEPT_ENCODING']=$request->SERVER('HTTP_ACCEPT_ENCODING');
        $view['server']['HTTP_ACCEPT_LANGUAGE']=$request->SERVER('HTTP_ACCEPT_LANGUAGE');
        $view['server']['HTTP_COOKIE']=$request->SERVER('HTTP_COOKIE');
        $view['server']['PATH']=$request->SERVER('PATH');
        $view['server']['SERVER_SIGNATURE']=$request->SERVER('SERVER_SIGNATURE');
        $view['server']['SERVER_SOFTWARE']=$request->SERVER('SERVER_SOFTWARE');
        $view['server']['SERVER_NAME']=$request->SERVER('SERVER_NAME');
        $view['server']['SERVER_ADDR']=$request->SERVER('SERVER_ADDR');
        $view['server']['SERVER_PORT']=$request->SERVER('SERVER_PORT');
        $view['server']['REMOTE_ADDR']=$request->SERVER('REMOTE_ADDR');
        $view['server']['DOCUMENT_ROOT']=$request->SERVER('DOCUMENT_ROOT');
        $view['server']['REQUEST_SCHEME']=$request->SERVER('REQUEST_SCHEME');
        $view['server']['CONTEXT_PREFIX']=$request->SERVER('CONTEXT_PREFIX');
        $view['server']['CONTEXT_DOCUMENT_ROOT']=$request->SERVER('CONTEXT_DOCUMENT_ROOT');
        $view['server']['SERVER_ADMIN']=$request->SERVER('SERVER_ADMIN');
        $view['server']['SCRIPT_FILENAME']=$request->SERVER('SCRIPT_FILENAME');
        $view['server']['REMOTE_PORT']=$request->SERVER('REMOTE_PORT');
        $view['server']['REDIRECT_URL']=$request->SERVER('REDIRECT_URL');
        $view['server']['REDIRECT_QUERY_STRING']=$request->SERVER('REDIRECT_QUERY_STRING');
        $view['server']['GATEWAY_INTERFACE']=$request->SERVER('GATEWAY_INTERFACE');
        $view['server']['SERVER_PROTOCOL']=$request->SERVER('SERVER_PROTOCOL');
        $view['server']['REQUEST_METHOD']=$request->SERVER('REQUEST_METHOD');
        $view['server']['QUERY_STRING']=$request->SERVER('QUERY_STRING');
        $view['server']['REQUEST_URI']=$request->SERVER('REQUEST_URI');
        $view['server']['SCRIPT_NAME']=$request->SERVER('SCRIPT_NAME');
        $view['server']['PHP_SELF']=$request->SERVER('PHP_SELF');
        $view['server']['REQUEST_TIME_FLOAT']=$request->SERVER('REQUEST_TIME_FLOAT');
        $view['server']['REQUEST_TIME']=$request->SERVER('REQUEST_TIME');


            $view['title'] = '{{Request JSON samples}} - MVCA';
            $view['config']['language'] = $this->config->get('defaultLanguage');
            $view['config']['charset'] = $this->config->get('charset');
            $this->output->load("Samples/SERVERRequests", $view, ['language' => $this->config->get('defaultLanguage')]);
        }

}