<section class="cookie-sample">
    <h2 class="sample__header ">Cookies samples</h2>
    <p class="sample__text sample__subheader">This is basics MVCA Cookies sample</p>
    <p class="sample__text"> MVCA has simple cookies service</p>
    <p class="sample__text"><span class="route">Setter</span>: <span class="important">$this->cookies->set('key','value')</span></p>
    <p class="sample__text"><span class="route">Getter</span>: <span class="important">this->cookies->get('key')</span></p>
    <p class="sample__text"><span class="route">Unsetter</span>: <span class="important">$this->cookies->del('key')</span></p>

    <p class="sample__text">Source code at:</p>
    <ul class="basic__ul">
        <li><p class="sample__text"><span class="route">app/src/Samples/C/CookiesController.php</span></p></li>
        <li><p class="sample__text"><span class="route">app/src/Samples/V/CookiesView.php</span></p></li>
    </ul>


    <p class="sample__text"><span class="important">"Click"</span> to the buttons and got to check Cookie in your  <span class="route">BrowserDevTool</span> or via buttons bellow. &dArr;</p>
    <p class="sample__text sample__subheader"><span class="warning">WATCH OUT!</span> Cookie will add in the end of string <span class="important">$this->cookies->get(<span class="route">'test'</span>)</span> is: <span class="route"><?= $testCookie ?></span></p>
    <a class="sample__link"  href="/Samples-Cookies-setTest">Click to run $this->cookies->set('test','123')</a><br>
    <a class="sample__link"  href="/Samples-Cookies-delTest">Click to run $this->cookies->del('test')</a>
    <br>
    <p class="sample__text"><span class="warning">Controller example:</span></p>

    <pre>
namespace ControllerFolder\C;

use Services\Cookies; //Cookie name space class

class YourController
{
    private Cookies $cookies; //SET THIS

    public function __construct(
        Cookies  $cookies,  //USE CONSTRUCT THIS
    )
    {
        $this->cookies = $cookies;   //SET THIS
    }
    public function someMethod(): void
    {
    /*
        Your controller code, where you can use
        $this->cookies->set('key','value')
        $this->cookies->get('key')
        $this->cookies->del('key')
        before body sent.
    */
    }
}
</pre>

    <p class="sample__text">Source code at:</p>
    <ul class="basic__ul">
        <li><p class="sample__text"><span class="route">src/Samples/C/CookiesController.php</span></p></li>
        <li><p class="sample__text"><span class="route">src/Samples/V/CookiesView.php</span></p></li>
    </ul>
</section>

