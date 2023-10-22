<?php

namespace Common;

use Engine\Base;


class ErrorController extends Base
{

    public function error404()
    {
        $view['config']['lang']=$this->config->get('defaultLang');
        $view['title']='{{Error404}} - 123';
        $this->output->load("common/Error404",$view,$view['config']['lang'],'');
    }
}
