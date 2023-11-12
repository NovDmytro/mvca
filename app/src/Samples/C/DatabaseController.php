<?php

namespace Samples\C;

use Samples\M;
use Engine\Config;
use Engine\Output;

class DatabaseController
{
    private M\DatabaseModel $databaseModel;
    private Config $config;
    private Output $output;

    public function __construct(
        M\DatabaseModel $databaseModel,
        Config $config,
        Output $output,
    )
    {
        $this->databaseModel = $databaseModel;
        $this->config = $config;
        $this->output = $output;
    }
    public function main(): void
    {
        $databaseModel=$this->databaseModel->getExampleById(1);
        $view['modelExample']=$databaseModel;
        $view['title'] = '{{Database sample}} - MVCA';
        $view['config']['language'] = $this->config->get('defaultLanguage');
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Samples/Database", $view);
    }
}