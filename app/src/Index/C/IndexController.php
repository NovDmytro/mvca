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

        $this->Config->set('defaultLang', '123');

        $indexModel = new M\IndexModel();
        $view['index'] = $indexModel->test();
        $view['config']['lang'] = $this->Config->get('defaultLang');
        $view['title'] = '{{Index}} - 123';
        $this->Output->load("Index/Index", $view, );
   }
}
