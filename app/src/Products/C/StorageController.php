<?php
namespace Products\C;
use Products\M;
use Engine\Config;
use Engine\Output;
use Services\Request;
use Services\Cookies;
class StorageController
{
    private M\StorageModel $storageModel;
    private Config $config;
    private Output $output;
    private Cookies $cookies;
    public function __construct(M\StorageModel $storageModel, Config $config, Output $output, Cookies $cookies)
    {
        $this->storageModel = $storageModel;
        $this->config = $config;
        $this->output = $output;
        $this->cookies = $cookies;
    }
    public function main(): void
    {
        $request = Request::init();
        $view['storage'] = $this->storageModel->getItemById($request->GET('var1', 'int'));
        $view['title'] = '{{Storage sample}} - MVCA';
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Products/Storage", $view, ['language' => $this->cookies->get('language')]);
    }
}