<?php

namespace Common\C;

use Common\M;
use Engine\Base;
use Services\Request;

class ErrorController extends Base
{
    public function error404(): void
    {
        $view['config']['lang'] = $this->Config->get('defaultLang');
        $view['title'] = '{{Error404}} - 123';
        $this->Output->load("Common/Error404", $view, $view['config']['lang'], '');
    }
}
