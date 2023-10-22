<?php

namespace Index;

use Engine\Base;


class IndexController extends Base
{

    public function main()
    {
        $view['config']['lang']=$this->config->get('defaultLang');
        $view['title']='{{Index}} - 123';
        $this->output->load("index/Index",$view,$view['config']['lang'],'');
    }
}
