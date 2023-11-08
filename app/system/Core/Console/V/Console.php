<section class="mvca-terminal">
    <div class="resize-top-side"></div>

    <div class="mvca-terminal-navigation">
        <ul class="mvca-terminal-folders">
            <?php $firstActive = 'active'; ?>
            <?php foreach ($sources as $source) : ?>
                <li data-folder="source-<?= $source ?>"
                    class="mvca-terminal-folder <?= $firstActive ?>"><?= $source ?></li>
                <?php $firstActive = ''; ?>
            <?php endforeach ?>
        </ul>
        <div class="mvca-terminal-navigation-status-bar">
            <div class="mvca-terminal-navigation-status">
                <span><?= $memory ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120q-151 0-255.5-46.5T120-280v-400q0-66 105.5-113T480-840q149 0 254.5 47T840-680v400q0 67-104.5 113.5T480-120Zm0-479q89 0 179-25.5T760-679q-11-29-100.5-55T480-760q-91 0-178.5 25.5T200-679q14 30 101.5 55T480-599Zm0 199q42 0 81-4t74.5-11.5q35.5-7.5 67-18.5t57.5-25v-120q-26 14-57.5 25t-67 18.5Q600-528 561-524t-81 4q-42 0-82-4t-75.5-11.5Q287-543 256-554t-56-25v120q25 14 56 25t66.5 18.5Q358-408 398-404t82 4Zm0 200q46 0 93.5-7t87.5-18.5q40-11.5 67-26t32-29.5v-98q-26 14-57.5 25t-67 18.5Q600-328 561-324t-81 4q-42 0-82-4t-75.5-11.5Q287-343 256-354t-56-25v99q5 15 31.5 29t66.5 25.5q40 11.5 88 18.5t94 7Z"/></svg>
            </div>
            <div class="mvca-terminal-navigation-status">
                <span><?= round($executionTime,6) ?> ms</span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z"/></svg>
            </div>
            <div class="mvca-terminal-navigation-buttons-set">
                    <svg data-terminal="up" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M193.782-753.217q-22.087 0-37.544-15.457-15.456-15.456-15.456-37.544 0-22.087 15.456-37.544 15.457-15.456 37.544-15.456h572.436q22.087 0 37.544 15.456 15.456 15.457 15.456 37.544 0 22.088-15.456 37.544-15.457 15.457-37.544 15.457H193.782ZM480-100.782q-22.087 0-37.544-15.456-15.457-15.457-15.457-37.544v-320.52l-53.955 53.956Q358.087-405.389 336-405.389q-22.087 0-37.044-14.957-14.957-14.957-14.957-37.044 0-22.087 14.957-37.044l143.435-143.435q7.696-7.696 17.522-11.609 9.826-3.913 20.087-3.913t20.087 3.913q9.826 3.913 17.522 11.609l143.435 143.435q14.957 14.957 14.957 37.044 0 22.087-14.957 37.044-14.957 14.957-37.044 14.957-22.087 0-37.044-14.957l-53.955-53.956v320.52q0 22.087-15.457 37.544-15.457 15.456-37.544 15.456Z"/></svg>
                    <svg data-terminal="center" xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48"><path d="M450-80v-800h60v800h-60Zm120-210v-380h100v380H570Zm-280 0v-380h100v380H290Z"/></svg>
                    <svg data-terminal="close" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-405.912 293.044-218.956Q278.087-203.999 256-203.999q-22.087 0-37.044-14.957-14.957-14.957-14.957-37.044 0-22.087 14.957-37.044L405.912-480 218.956-666.956Q203.999-681.913 203.999-704q0-22.087 14.957-37.044 14.957-14.957 37.044-14.957 22.087 0 37.044 14.957L480-554.088l186.956-186.956q14.957-14.957 37.044-14.957 22.087 0 37.044 14.957 14.957 14.957 14.957 37.044 0 22.087-14.957 37.044L554.088-480l186.956 186.956q14.957 14.957 14.957 37.044 0 22.087-14.957 37.044-14.957 14.957-37.044 14.957-22.087 0-37.044-14.957L480-405.912Z"/></svg>
            </div>
        </div>

    </div>
    <?php $firstActive = ''; ?>
    <?php foreach ($sources as $source) : ?>
        <div class="mvca-terminal-error-area source-<?= $source ?> <?= $firstActive ?>" style="color:#ffffff">
            <?php foreach ($reports[$source] as $report) : ?>
                [<?= number_format($report['time'], 6, '.', '') ?>] <b><?= $report['type'] ?></b> - 
                <?php if (is_array($report['data'])) : ?>
                    <?php var_dump($report['data']); ?>
                <?php else : ?>
                    <?= $report['data'] ?>
                <?php endif ?>
            <br>
            <?php endforeach ?>
        </div>
        <?php $firstActive = 'hide'; ?>
    <?php endforeach ?>
</section>
<script>
    // terminal scripts 
    const terminal = document.querySelector('.mvca-terminal');
    const secondResize = document.querySelector('body');
    const dragElem = terminal.querySelector('.resize-top-side');
    const navigation = terminal.querySelector('.mvca-terminal-navigation');
    const toTopButton = terminal.querySelector('[data-terminal="up"]');
    const centeringButton = terminal.querySelector('[data-terminal="center"]');
    const closeButton = terminal.querySelector('[data-terminal="close"]');

    const getCookiesObject = () => {
        const obj = {};

        document.cookie.split(';').forEach(cookie => {
            const arrCook = cookie.split('=');
            obj[arrCook[0].trim()] = arrCook[1];
        })

        return obj;
    }
    const cookiesObject = getCookiesObject();

    // position buttons control START
    let initialY = 0;
    let curentHeight = cookiesObject.terminalPos !== undefined ? cookiesObject.terminalPos : terminal.getBoundingClientRect().height;
    let prevHeight;

    const terminalMinHeight = parseInt(window.getComputedStyle(dragElem).height) + parseInt(window.getComputedStyle(navigation).height);

        // memorize terminal height in COOKIE START
        const terminalPositionMEMO = () => {
            document.cookie = `terminalPos=${curentHeight}`;
        }
        // memorize terminal height in COOKIE END

    const toTopButtonControl = () => {
        if (curentHeight === window.innerHeight) {
            toTopButton.classList.add('revertTo180Deg');
            return;
        } 
        toTopButton.classList.remove('revertTo180Deg');
    }

    const setTerminalHeight = () => {
        let isButtonHide = false;
    
        if (curentHeight <= terminalMinHeight) {
            curentHeight = terminalMinHeight;
            isButtonHide = true;
        }
        if (curentHeight >= window.innerHeight) {
            curentHeight = window.innerHeight;
        }
        isButtonHide ? hideCloseButton() : revealCloseButton();
        terminalPositionMEMO();
        terminal.style.cssText = `--mvca-terminal-height: ${curentHeight}px`;
        secondResize.style.cssText = `--body-margin-bottom: ${curentHeight}px`;
        toTopButtonControl();
    }

    const hideCloseButton = () => {
        closeButton.classList.add('invisible');
    }
    const revealCloseButton = () => {
        closeButton.classList.remove('invisible');
    }

    setTerminalHeight();

    const closeTerminal = (e) => {
        curentHeight = terminalMinHeight;
        setTerminalHeight();
        hideCloseButton();
    }
    closeButton.onclick = closeTerminal;

    const centeringTerminal = () => {
        curentHeight = Math.round(window.innerHeight / 2);
        setTerminalHeight();
    }
    centeringButton.onclick = centeringTerminal;

    const toTopTerminal = () => {
        if (curentHeight === window.innerHeight) {
            curentHeight = prevHeight;
            setTerminalHeight();
            return;
        }

        prevHeight = curentHeight;
        curentHeight = window.innerHeight;
        setTerminalHeight();           
    }
    toTopButton.onclick = toTopTerminal;

    window.addEventListener('keydown', (e) => {
        console.log(e.code)
        e.code === 'Escape' ? closeTerminal() : null;
    })
    // position buttons control END

    // drag functions START
    const draging = (e) => {
        e.preventDefault();
        const deference = initialY - e.clientY;
        curentHeight = +prevHeight + deference;
        
        setTerminalHeight();
    }
    const dragInit = (e) => {
        e.preventDefault();
        initialY = e.clientY;    
        prevHeight = curentHeight;
        window.addEventListener('mousemove', draging);
    }
    dragElem.addEventListener('mousedown', dragInit);

    const removeListeners = () => {
            window.removeEventListener('mousemove', draging);
    }
    window.addEventListener('mouseup', removeListeners);   
    // drag functions END

    // tabs functions START
    const folders = terminal.querySelector('.mvca-terminal-folders');
    const foldersButtons = folders.querySelectorAll('.mvca-terminal-folder');
    const errorAreas = terminal.querySelectorAll('.mvca-terminal-error-area');

    const changeActiveButton = (e) => {
        const isItFolder = e.target.hasAttribute('data-folder');

        if (e.target.classList.contains('active')) {
            return;
        }

        if (isItFolder) {
            const errorAreaToReveal = e.target.getAttribute('data-folder');
            removeActiveButtons();
            closeErrorsAreas();
            e.target.classList.add('active');
            terminal.querySelector(`.${errorAreaToReveal}`).classList.remove('hide');
        }
    }

    const removeActiveButtons = () => {
        foldersButtons.forEach(button => {
            button.classList.remove('active');
        })
    }
    const closeErrorsAreas = () => {
        errorAreas.forEach(area => {
            area.classList.add('hide');
        })
    }

    folders.onclick = changeActiveButton;
</script>