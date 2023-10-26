<?php

namespace Index\C;

use Index\M;
use Engine\Base;

class IndexController extends Base
{
    public function main(): void
    {
        $request = \Services\Request::init();
        echo $request->GET('q');

        $this->config->set('defaultLang', '123');

        $indexModel = new M\IndexModel();
        $view['index'] = $indexModel->test();
        $view['config']['lang'] = $this->config->get('defaultLang');
        $view['title'] = '{{Index}} - 123';
        $this->output->load("Index/Index", $view, []);
   }
}
