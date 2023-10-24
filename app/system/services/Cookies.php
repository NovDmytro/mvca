<?php

namespace Service;


class Cookies
{
    private $cookies;

    public function get(string $name)
    {
        $request = Request::start();

if(!$this->cookies[$name]){$this->cookies[$name]=$request->COOKIE($name);}
return $this->cookies[$name];
    }

    public function set(string $name, string $value)
    {
        setcookie($name,$value,time()+60*60*24*30,'/');
        $this->cookies[$name]=$value;
    }

    public function del(string $name)
    {
        setcookie($name,'',time()-3600,'/');
        unset($this->cookies[$name]);
    }
}
