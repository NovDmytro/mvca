<h2>Cookies samples</h2>

This is cookies samples<br>
MVCA has simple cookies service that can set, get and delete some client's cookies<br>
$this->cookies->set('key','value'), $this->cookies->get('key') and $this->cookies->del('key')<br>
Please visit app/src/Samples/C/CookiesController.php and app/src/Samples/V/CookiesView.php to understand how its work<br>
In this example we will work with "test" cookie<br><br>


$this->cookies->get('test') is:<?= $testCookie ?><br><br>



<a href="/Samples-Cookies-setTest">Click to run $this->cookies->set('test','123')</a><br>

<a href="/Samples-Cookies-delTest">Click to run $this->cookies->del('test')</a>


<br><br>
<br>controller example:
<pre>
< ?php
namespace ControllerFolder\C;
use Services\Cookies; //ADD THIS
class YourController
{
    private Cookies $cookies; //ADD THIS
    public function __construct(
        Cookies  $cookies,  //ADD THIS
    )
    {
        $this->cookies = $cookies;   //ADD THIS
    }
    public function someMethod(): void
    {/*
Your controller code, where you can use
$this->cookies->set('key','value')
$this->cookies->get('key')
$this->cookies->del('key')
before body sent.
  */}
}
</pre>