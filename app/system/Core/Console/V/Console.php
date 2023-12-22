<?php
/**
 * @var array $sources
 * @var array $reports
 * @var string $memory
 * @var string $executionTime
 */
?>

<template id="terminalTMPL">
<style>
        /* fonts START */
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-Thin.woff2");
            font-weight: 100;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-ExtraLight.woff2");
            font-weight: 200;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-Light.woff2");
            font-weight: 300;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-Regular.woff2");
            font-weight: 400;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-Medium.woff2");
            font-weight: 500;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-SemiBold.woff2");
            font-weight: 700;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-Bold.woff2");
            font-weight: 800;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("/media/fonts/JetBrainsMono-ExtraBold.woff2");
            font-weight: 900;
        }
        /* fonts END */

        /* terminal styles START */
        :root {

            --mvca-terminal-bg-color: #2b2b2b;
            --mvca-terminal-height: 0px;
            --mvca-folder-text-color: #a9b7c6;
            --mvca-folder-border-bottom: #2b2b2b;
            --mvca-terminal-navigation-status-color: #a9b7c6;
            --mvca-terminal-error-erea-bg-color: #000;
            --mvca-terminal-error-text-color: #a9b7c6;
          
            --a: #2b2b2b;
            --b: #a9b7c6;
            --c: #cb602d;
            --d: #ffc66d;

            --body-margin-bottom: 0px;
        }

        body {
            margin-bottom: var(--body-margin-bottom);
        }

		.mvca-terminal * {
			font-family: 'JetBrains', sans-serif;
		}
		.mvca-terminal{

            position: fixed;
            bottom: 0;
            left: 0;
            display: grid;
            grid-template: 10px fit-content(100%) auto / 100vw;
            width: 100vw;
            height: var(--mvca-terminal-height);
            background-color: var(--mvca-terminal-bg-color); 
        }
        .mvca-terminal * {
            font-family: "CourierNew", serif;
            font-weight: 700;
        }
        .mvca-terminal .resize-top-side {
            width: 100%;
            height: 10px;
            background-color: var(--mvca-terminal-bg-color);
            cursor: row-resize;
        }
        .mvca-terminal-navigation {
			position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
			height: 34px;
        }
        .mvca-terminal-folders {
            padding: 0 25px;
            display: flex;
            gap: 0 10px;
			width: 100%;
            list-style-type: none;
            overflow: hidden;
        }
		.mvca-terminal-folder {
            padding: 5px;
            color: var(--mvca-folder-text-color);
            border-bottom: 3px solid var(--mvca-folder-border-bottom);
            user-select: none;
			cursor: pointer;
        }
        .mvca-terminal-folder:hover {
            --mvca-folder-border-bottom: #ffc66d;
            --mvca-folder-text-color:#ffc66d;
        }
        .mvca-terminal-folder.active {
            --mvca-folder-border-bottom: #cb602d;
            cursor:auto;
        }
		.mvca-popup-menu {
			display: flex;
            flex-direction: column-reverse;
			padding: 10px;
			position: absolute;
			right: 318px;
			background-color: var(--mvca-terminal-bg-color);
            list-style-type: none;
        }
		.mvca-popup-menu.below {
			bottom: 30px;
            top: auto;
        }
		.mvca-popup-menu.under{
			bottom: auto;
			top: 30px;
		}

		.mvca-popup-menu-item {
			padding: 5px 0;
			color: var(--mvca-folder-text-color);
			border-bottom: 3px solid var(--mvca-folder-border-bottom);
            width: max-content;
			cursor: pointer;
        }
		.mvca-popup-menu-item:hover {
			--mvca-folder-border-bottom: #ffc66d;
			--mvca-folder-text-color:#ffc66d;
		}
		.mvca-popup-menu-item.active {
			--mvca-folder-border-bottom: #cb602d;
			cursor:auto;
		}

        .mvca-terminal-navigation-status-bar {
			position: relative;
			padding: 0 25px 0 10px;
            display: flex;
            align-items: center;
            gap: 0 10px;
            margin-bottom: 5px;
        }
		.mvca-terminal-folder-icon {
			width: 32px;
			fill: var(--mvca-terminal-navigation-status-color);
			cursor: pointer;
		}
		.mvca-terminal-folder-icon:hover {
			--mvca-terminal-navigation-status-color: #CB602DFF;
        }
		.mvca-terminal-folder-icon.active {
			--mvca-terminal-navigation-status-color: #CB602DFF;
        }
		.mvca-terminal-navigation-status {
            display: flex;
            align-items: end;
            gap: 0 3px
        }
        .mvca-terminal-navigation-status span {
            color: var(--mvca-terminal-navigation-status-color);
			line-height: 1.1;
        }
        .mvca-terminal-navigation-status svg {
			display: block;
			width: 24px;
            fill: var(--mvca-terminal-navigation-status-color);
        }
		.mvca-terminal-navigation-status svg:hover {
			--mvca-terminal-navigation-status-color: #cb602d
        }

		.mvca-terminal-navigation-buttons-set {
			display: flex;
        }
        .mvca-terminal-navigation-buttons-set svg{
            width: 24px;
            height: 24px;
            fill: var(--mvca-terminal-navigation-status-color);
            cursor: pointer;
        }
        .mvca-terminal-navigation-buttons-set [data-terminal="center"] {
            transform: rotate(90deg);
        }
        .mvca-terminal-navigation-buttons-set svg:hover {
            --mvca-terminal-navigation-status-color: #cb602d;
        }
        .mvca-terminal-error-area {
			font-size: 16px;
            padding: 10px 25px;
            background: var(--mvca-terminal-error-erea-bg-color);
        }
        @media (max-width: 500px) {
			.mvca-terminal-folders {
                padding: 0 calc(0rem + 25 * (100vw - 320px) / ( 500 - 320) * 16 / 16 );
            }
            [data-server] {
				display: none;
            }
			.mvca-popup-menu {
				right: 120px;
            }
			.mvca-terminal-error-area {
				padding: 10px;
				font-size: 14px;
            }
        }
        .mvca-terminal-error-area {
            overflow-y: scroll;
		}
        .no-wrap {
            text-wrap: nowrap;
			white-space: nowrap;
        }
        .invisible {
			visibility: hidden;
        }
		.flex {
			display: flex;
		}
        .hide {
			display: none;
        }
        .show-inline {
			display: inline;
        }
		.show {
			display: block;
        }
		.revertTo180Deg {
			transform: rotate(180deg);
		}
        /* terminal styles END */
</style>
<section class="mvca-terminal">
    <div class="resize-top-side"></div>
   
    <div class="mvca-terminal-navigation">
        <ul class="mvca-terminal-folders">
            <!-- heremust be PHP code -->
            <?php $firstActive = 'active'; ?>
            <?php foreach ($sources as $source) : ?>
                <li data-folder="source-<?= $source ?>"
                    class="mvca-terminal-folder no-wrap <?= $firstActive ?>"><?= $source ?></li>
                <?php $firstActive = ''; ?>
            <?php endforeach ?>

            <!-- heremust be PHP code -->
        </ul>

        <div class="mvca-terminal-navigation-status-bar">
            <ul class="mvca-popup-menu below hide">
                <?php $firstActive = 'active'; ?>
                <?php foreach ($sources as $source) : ?>
                    <li data-folder="source-<?= $source ?>"
                        class="mvca-popup-menu-item no-wrap <?= $firstActive ?>"><?= $source ?></li>
                    <?php $firstActive = ''; ?>
                <?php endforeach ?>
            </ul>
            <svg class="mvca-terminal-folder-icon hide" xmlns="http://www.w3.org/2000/svg"  viewBox="0 -960 960 960"><path d="M140-160q-24 0-42-18.5T80-220v-520q0-23 18-41.5t42-18.5h281l60 60h339q23 0 41.5 18.5T880-680v460q0 23-18.5 41.5T820-160H140Zm0-60h680v-460H456l-60-60H140v520Zm0 0v-520 520Z"/></svg>
            <div class="mvca-terminal-navigation-status">
                <span data-server="volume" class="no-wrap"><?= $memory ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120q-151 0-255.5-46.5T120-280v-400q0-66 105.5-113T480-840q149 0 254.5 47T840-680v400q0 67-104.5 113.5T480-120Zm0-479q89 0 179-25.5T760-679q-11-29-100.5-55T480-760q-91 0-178.5 25.5T200-679q14 30 101.5 55T480-599Zm0 199q42 0 81-4t74.5-11.5q35.5-7.5 67-18.5t57.5-25v-120q-26 14-57.5 25t-67 18.5Q600-528 561-524t-81 4q-42 0-82-4t-75.5-11.5Q287-543 256-554t-56-25v120q25 14 56 25t66.5 18.5Q358-408 398-404t82 4Zm0 200q46 0 93.5-7t87.5-18.5q40-11.5 67-26t32-29.5v-98q-26 14-57.5 25t-67 18.5Q600-328 561-324t-81 4q-42 0-82-4t-75.5-11.5Q287-343 256-354t-56-25v99q5 15 31.5 29t66.5 25.5q40 11.5 88 18.5t94 7Z"/></svg>
            </div>
            <div class="mvca-terminal-navigation-status">
                <span data-server="time" class="no-wrap"><?= round($executionTime,6) ?> ms</span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z"/></svg>
            </div>
            <div class="mvca-terminal-navigation-buttons-set">
                    <svg data-terminal="up" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M193.782-753.217q-22.087 0-37.544-15.457-15.456-15.456-15.456-37.544 0-22.087 15.456-37.544 15.457-15.456 37.544-15.456h572.436q22.087 0 37.544 15.456 15.456 15.457 15.456 37.544 0 22.088-15.456 37.544-15.457 15.457-37.544 15.457H193.782ZM480-100.782q-22.087 0-37.544-15.456-15.457-15.457-15.457-37.544v-320.52l-53.955 53.956Q358.087-405.389 336-405.389q-22.087 0-37.044-14.957-14.957-14.957-14.957-37.044 0-22.087 14.957-37.044l143.435-143.435q7.696-7.696 17.522-11.609 9.826-3.913 20.087-3.913t20.087 3.913q9.826 3.913 17.522 11.609l143.435 143.435q14.957 14.957 14.957 37.044 0 22.087-14.957 37.044-14.957 14.957-37.044 14.957-22.087 0-37.044-14.957l-53.955-53.956v320.52q0 22.087-15.457 37.544-15.457 15.456-37.544 15.456Z"/></svg>
                    <svg data-terminal="center" xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48"><path d="M450-80v-800h60v800h-60Zm120-210v-380h100v380H570Zm-280 0v-380h100v380H290Z"/></svg>
                    <svg data-terminal="close" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-405.912 293.044-218.956Q278.087-203.999 256-203.999q-22.087 0-37.044-14.957-14.957-14.957-14.957-37.044 0-22.087 14.957-37.044L405.912-480 218.956-666.956Q203.999-681.913 203.999-704q0-22.087 14.957-37.044 14.957-14.957 37.044-14.957 22.087 0 37.044 14.957L480-554.088l186.956-186.956q14.957-14.957 37.044-14.957 22.087 0 37.044 14.957 14.957 14.957 14.957 37.044 0 22.087-14.957 37.044L554.088-480l186.956 186.956q14.957 14.957 14.957 37.044 0 22.087-14.957 37.044-14.957 14.957-37.044 14.957-22.087 0-37.044-14.957L480-405.912Z"/></svg>
            </div>
        </div>

    </div>
    <!-- heremust be PHP code -->
    <?php $firstActive = ''; ?>
    <?php foreach ($sources as $source) : ?>
        <div class="mvca-terminal-error-area source-<?= $source ?> <?= $firstActive ?>" style="color:#ffffff">
            <?php foreach ($reports[$source] as $report) : ?>
                [<?= number_format($report['time'], 6, '.', '') ?>] <b><?= $report['type'] ?></b>
                <?php if (is_array($report['data'])) : ?>
            <pre><?= json_encode($report['data'],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?></pre>
                <?php else : ?>
                    - <?= $report['data'] ?>
                <?php endif ?>
                <br>
            <?php endforeach ?>
        </div>
        <?php $firstActive = 'hide'; ?>
    <?php endforeach ?>
    <!-- heremust be PHP code -->
</section>
      
<script>
    window.addEventListener('DOMContentLoaded', () => {
        // terminal scripts
      
        const body = document.body;
        const shadowRoot = document.querySelector('#terminala').shadowRoot;
        const terminal = shadowRoot.querySelector('.mvca-terminal');
        const dragElem = terminal.querySelector('.resize-top-side');
        const navigation = terminal.querySelector('.mvca-terminal-navigation');
        const toTopButton = terminal.querySelector('[data-terminal="up"]');
        const centeringButton = terminal.querySelector('[data-terminal="center"]');
        const closeButton = terminal.querySelector('[data-terminal="close"]');

        const terminalMinHeight = parseInt(window.getComputedStyle(dragElem).height) + parseInt(window.getComputedStyle(navigation).height);

        let initialY = 0;
        let curentHeight = localStorage.getItem('terminalPos') ? localStorage.getItem('terminalPos') : terminal.getBoundingClientRect().height;

        let timer;
        let isTouch;
        let prevHeight;

        setTerminalHeight();

        dragElem.addEventListener('mousedown', dragInit);                       // drag event
        dragElem.addEventListener('touchstart', dragInit, {'passive':true});    // drag event
        closeButton.onclick = closeterminal;                                    // position buttons control
        centeringButton.onclick = centeringterminal;                            // position buttons control
        toTopButton.onclick = toTopterminal;                                    // position buttons control

        window.addEventListener('keydown', (e) => {
            e.code === 'Escape' ? closeterminal() : null;
        })
        window.addEventListener('mouseup', removeListeners);
        window.addEventListener('touchend', removeListeners, {'passive':true});

        function debounceHandler(debounceCallback, delay = 1000) {
            clearTimeout(timer);
            timer = setTimeout(debounceCallback, delay)
        }

        function terminalPositionMEMO() {
            debounceHandler(() => localStorage.setItem('terminalPos', curentHeight));
        }

        function toTopButtonControl () {
            toTopButton.classList.toggle('revertTo180Deg', curentHeight === window.innerHeight);
        }

        function setTerminalHeight() {
            let isButtonHide = curentHeight <= terminalMinHeight;

            if (isButtonHide) {
                curentHeight = terminalMinHeight;
            } else if (curentHeight >= window.innerHeight) {
                curentHeight = window.innerHeight;
            }
            isButtonHide ? hideCloseButton() : revealCloseButton();

            terminal.style.setProperty('--mvca-terminal-height', `${curentHeight}px`);
            body.style.setProperty('--body-margin-bottom', `${curentHeight}px`);
            toTopButtonControl();
            terminalPositionMEMO();
        }

        function hideCloseButton() {
            closeButton.classList.add('invisible');
        }
        function revealCloseButton() {
            closeButton.classList.remove('invisible');
        }

        function closeterminal (e) {
            curentHeight = terminalMinHeight;
            setTerminalHeight();
            hideCloseButton();
        }
        function centeringterminal () {
            curentHeight = Math.round(window.innerHeight / 2);
            setTerminalHeight();
        }
        function toTopterminal() {
            if (curentHeight === window.innerHeight) {
                curentHeight = prevHeight;
                setTerminalHeight();
                return;
            }
            prevHeight = curentHeight;
            curentHeight = window.innerHeight;
            setTerminalHeight();
        }

        function draging(e)  {
            // e.preventDefault();
            if (!isTouch) e.preventDefault();
            const deference = initialY - (isTouch ? e.touches[0].clientY : e.clientY);
            curentHeight = +prevHeight + deference;

            setTerminalHeight();
        }
        function dragInit(e) {
            isTouch = e.type === 'touchstart';
            initialY = isTouch ? e.touches[0].clientY : e.clientY;
            prevHeight = curentHeight;
            const eventOptions = isTouch ? {'passive':true} : {'passive':false};
            window.addEventListener(isTouch ? 'touchmove' : 'mousemove', draging, eventOptions);
        }

        function removeListeners() {
            window.removeEventListener('mousemove', draging);
            window.removeEventListener('touchmove', draging, {'passive':true} );
        }


        // tabs functions START
        const folders = terminal.querySelector('.mvca-terminal-folders');
        const mvcaPopupMenu = terminal.querySelector('.mvca-popup-menu');
        const foldersButtons = terminal.querySelectorAll('[data-folder]');
        const errorAreas = terminal.querySelectorAll('.mvca-terminal-error-area');
        const folderIcon = terminal.querySelector('.mvca-terminal-folder-icon');

        folders.onclick = changeActiveButton;
        mvcaPopupMenu.onclick = changeActiveButton;
        folderIcon.onclick = () => {
            mvcaPopupMenu.classList.toggle('hide');
            const popUpMenuHeight = mvcaPopupMenu.getBoundingClientRect().height;
            const isPopUpHeightEnough = window.innerHeight - curentHeight > popUpMenuHeight;

            isPopUpHeightEnough ? mvcaPopupMenu.classList.remove('under') : mvcaPopupMenu.classList.add('under');
        };
        body.onclick = (e) => {
            if (!e.target.classList.contains('data-folder') && e.target !== folderIcon) {
                mvcaPopupMenu.classList.add('hide');
            }
        }

        function changeActiveButton(e) {
            if (!e.target.classList.contains('active') && e.target.dataset.folder) {
                foldersButtons.forEach((folder, i)=> {
                    folder.dataset.folder === e.target.dataset.folder ? folder.classList.add('active') : folder.classList.remove('active');
                    if (errorAreas[i]) errorAreas[i].classList.add('hide');
                })
                terminal.querySelector(`.${e.target.dataset.folder}`).classList.remove('hide');
            }
        }

       // tabs hiddimg
        const getFolder = terminal.querySelectorAll('.mvca-terminal-folder');
        const getPopupFolder = terminal.querySelectorAll('.mvca-popup-menu-item');
        const observer = new IntersectionObserver(hideFolderAtIntersect, {root: terminal, rootMargin: '0px', threshold: 0.99,});

        getFolder.forEach(fol =>  observer.observe(fol));

        function hideFolderAtIntersect(entries, observer) {
            entries.forEach(entry => {
                entry.target.classList.toggle('invisible', !entry.isIntersecting);
            });

            debounceHandler(() => {
                getFolder.forEach((fol, i)=> {
                    if (fol.classList.contains('invisible')) {
                        folderIcon.classList.remove('hide');
                        getPopupFolder[i].classList.remove('hide');

                    } else {
                        folderIcon.classList.add('hide');
                        getPopupFolder[i].classList.add('hide');
                        mvcaPopupMenu.classList.add('hide');
                    }
                })
            }, 200);

        }

        const serverData = terminal.querySelectorAll('[data-server]');
        const serverDataButtons = terminal.querySelectorAll('[data-server]+svg');
        serverDataButtons.forEach((button, i)=> {
            button.onclick = () =>{
                if (window.innerWidth <= 500) {
                    serverData.forEach(data => {
                        data.classList.remove('show');
                    })
                    serverData[i].classList.toggle('show');
                }
            }
        })
    })
</script>
</template>


<div id='terminala'></div>
<script>
   terminala.attachShadow({mode: 'open'});
   terminala.shadowRoot.append(terminalTMPL.content.cloneNode(true));
</script> 
