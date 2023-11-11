<section class="server-request">
    <h2 class="sample__header">SERVER Requests samples</h2>
    <p class="sample__text sample__subheader">Provide requestion to the super global <span class="important">$_SERVER</span></p>

    <div class="sample__container">
        <h3 class="sample__sec-header">Results</h3>
        <p class="sample__text">
            $request->SERVER('REDIRECT_STATUS') is: <?= $server['REDIRECT_STATUS'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_HOST') is: <?= $server['HTTP_HOST'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_CONNECTION') is: <?= $server['HTTP_CONNECTION'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_CACHE_CONTROL') is: <?= $server['HTTP_CACHE_CONTROL'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_SEC_CH_UA') is: <?= $server['HTTP_SEC_CH_UA'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_SEC_CH_UA_MOBILE') is: <?= $server['HTTP_SEC_CH_UA_MOBILE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_SEC_CH_UA_PLATFORM') is: <?= $server['HTTP_SEC_CH_UA_PLATFORM'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_DNT') is: <?= $server['HTTP_DNT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_UPGRADE_INSECURE_REQUESTS') is: <?= $server['HTTP_UPGRADE_INSECURE_REQUESTS'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_USER_AGENT') is: <?= $server['HTTP_USER_AGENT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_ACCEPT') is: <?= $server['HTTP_ACCEPT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_SEC_FETCH_SITE') is: <?= $server['HTTP_SEC_FETCH_SITE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_SEC_FETCH_MODE') is: <?= $server['HTTP_SEC_FETCH_MODE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_SEC_FETCH_USER') is: <?= $server['HTTP_SEC_FETCH_USER'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_SEC_FETCH_DEST') is: <?= $server['HTTP_SEC_FETCH_DEST'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_REFERER') is: <?= $server['HTTP_REFERER'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_ACCEPT_ENCODING') is: <?= $server['HTTP_ACCEPT_ENCODING'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_ACCEPT_LANGUAGE') is: <?= $server['HTTP_ACCEPT_LANGUAGE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('HTTP_COOKIE') is: <?= $server['HTTP_COOKIE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('PATH') is: <?= $server['PATH'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SERVER_SIGNATURE') is: <?= $server['SERVER_SIGNATURE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SERVER_SOFTWARE') is: <?= $server['SERVER_SOFTWARE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SERVER_NAME') is: <?= $server['SERVER_NAME'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SERVER_ADDR') is: <?= $server['SERVER_ADDR'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SERVER_PORT') is: <?= $server['SERVER_PORT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REMOTE_ADDR') is: <?= $server['REMOTE_ADDR'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('DOCUMENT_ROOT') is: <?= $server['DOCUMENT_ROOT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REQUEST_SCHEME') is: <?= $server['REQUEST_SCHEME'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('CONTEXT_PREFIX') is: <?= $server['CONTEXT_PREFIX'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('CONTEXT_DOCUMENT_ROOT') is: <?= $server['CONTEXT_DOCUMENT_ROOT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SERVER_ADMIN') is: <?= $server['SERVER_ADMIN'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SCRIPT_FILENAME') is: <?= $server['SCRIPT_FILENAME'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REMOTE_PORT') is: <?= $server['REMOTE_PORT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REDIRECT_URL') is: <?= $server['REDIRECT_URL'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REDIRECT_QUERY_STRING') is: <?= $server['REDIRECT_QUERY_STRING'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('GATEWAY_INTERFACE') is: <?= $server['GATEWAY_INTERFACE'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SERVER_PROTOCOL') is: <?= $server['SERVER_PROTOCOL'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REQUEST_METHOD') is: <?= $server['REQUEST_METHOD'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('QUERY_STRING') is: <?= $server['QUERY_STRING'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REQUEST_URI') is: <?= $server['REQUEST_URI'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('SCRIPT_NAME') is: <?= $server['SCRIPT_NAME'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('PHP_SELF') is: <?= $server['PHP_SELF'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REQUEST_TIME_FLOAT') is: <?= $server['REQUEST_TIME_FLOAT'] ?>
        </p>
        <p class="sample__text">
            $request->SERVER('REQUEST_TIME') is: <?= $server['REQUEST_TIME'] ?>
        </p>

    </div>

</section>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const strings = [...document.querySelectorAll('.sample__text')];
        const text = strings[0].textContent.replace(/(\$.+\))/,'<span class="important">$1</span>');
        console.log(text);

        strings.forEach(string => {
            let text = string.textContent.replace(/(\$.+?\))/,'<span class="important">$1</span>');
            text = text.replace(/('.+?')/,'<span class="positive">$1</span>');
            string.innerHTML = text;
        })
    });
</script>