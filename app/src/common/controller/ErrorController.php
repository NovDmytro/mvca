<?php

namespace Common;

use Engine\Base;

/**
 * @property $config
 * @property $output
 */
class ErrorController extends Base
{



    public function error404(): void
    {
        $view['config']['lang']=$this->config->get('defaultLang');
        $view['title']='{{Error404}} - 123';
        $this->output->load("common/Error404",$view,$view['config']['lang'],'');
    }
}
