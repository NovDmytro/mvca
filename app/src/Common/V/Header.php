<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="Height=device-Height, initial-scale=1.0">

    <title>Document</title>
    <link rel="icon" href="/media/Logo.ico">
</head>
<body> 
    <style>
        /* fonts START */
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-Thin.woff2");
            font-weight: 100;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-ExtraLight.woff2");
            font-weight: 200;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-Light.woff2");
            font-weight: 300;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-Regular.woff2");
            font-weight: 400;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-Medium.woff2");
            font-weight: 500;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-SemiBold.woff2");
            font-weight: 700;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-Bold.woff2");
            font-weight: 800;
        }
        @font-face {
            font-family: "JetBrains";
            src: url("./media/fonts/JetBrainsMono-ExtraBold.woff2");
            font-weight: 900;
        }
        /* fonts END */

        /* basic styles START */
        * {
            box-sizing: border-box;
            font-family: 'JetBrains', sans-serif;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            line-height: 1.3;
        }
        a {
            color: black;
            text-decoration: none;
        }
        :root {
            --header-logo-height: 60px;
            --header-logo-text-size: 30px;
            --header-logo-text-rout-size: 20px;
            --header-logo-header-text-color: #509B89;
            --header-bg-color: #fff;

            --navigation-link-fz: 20px;
            --navigation-item: #509B89; 
            --navigation-item-hover: #6bbaa7; 
            --navigation-item-text-color: #fff;

            --burger-bg-color: #509B89;
            --burger-bg-color-hover: #6bbaa7;
            --burger-lines-color-hover: #fff;

            --footer-logo-text-size: 30px;
            --footer-logo-header-text-color: #fff;
            --footer-logo-route-text-color: #fff;
            --footer-logo-height: 60px;
            --footer-logo-border-color:#1b725e;
            --footer-bg-img: linear-gradient(#6bbaa7, #509B89);
        }
        /* basic styles END */

        main {
            margin-top: 120px;
        }

        /* header styles START */
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
            box-shadow: 0 0 7px rgba(0, 0, 0, 0.537);
            background-color: #fff;
        } 
        @media (max-width: 650px) {
            .header {
                padding:calc(0.375rem + 14 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            }
        }

        .header .container {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 1150px;
        }
        @media (max-width: 800px) {
            .header .container {
                width: 100%;
            }
        }

        .header__logo-container {
            display: flex;
            align-items: center;
        }
        .header__logo-link {
            display: flex;
            align-items: center;
        }
        .header__logo {
            height: var(--header-logo-height);
            margin-right: 20px;
            border-radius: 50%;
        }
        @media (max-width: 650px) {
            .header__logo {
                margin-right: 10px;
                --header-logo-height: calc(2.5rem + 20 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            } 
        }

        .header__logo-text-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header__logo-text-header {
            color: var(--header-logo-header-text-color);
            font-size: var(--header-logo-text-size);
            font-weight: 600;
        }
        @media (max-width: 650px) {
            .header__logo-text-header {
                --header-logo-text-size: calc(1.25rem + 10 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            }
        }

        .header__logo-text-route {
            font-size: var(--header-logo-text-rout-size);
        }
        @media (max-width: 650px) {
            .header__logo-text-route {
            display: none;
            }
        }
        /* header styles END */


        /* navigaiton styles START*/
        .burger  {
            padding: 10px;
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 80px;
            background-color: var(--burger-bg-color);
            border-radius: 5px;
        }
        .burger:hover {
            background-color: var(--burger-bg-color-hover);
        }
        @media (max-width: 800px) {
            .burger {
                display: flex;
                width: calc(3rem + 32 * (100vw - 320px) / ( 800 - 320) * 16 / 16 );
            }
        }
        .navigation {
            display: flex;
            align-items: end;
        }
        @media (max-width: 800px) {
            .navigation {
                position: absolute;
                top: 95px;
                right: -100%;
            } 
        }
        @media (max-width: 650px) {
            .navigation {
                top: calc(3.4375rem + 40 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            }
        }

        .navigation__menu {
            display: flex;
            align-items: center;
            gap: 0 20px;
        }
        @media (max-width: 800px) {
            .navigation__menu {
                flex-direction: column;
                gap: 20px 0;
            }
        }
        @media (max-width: 650px) {
            .navigation__menu {
                gap: calc(0.3125rem + 15 * (100vw - 320px) / ( 650 - 320) * 16 / 16 ) 0;
            }
        }

        .navigation__item {
            padding: 10px 20px;
            background-color: var(--navigation-item);
            border-radius: 16px; 
        }
        .navigation__item:hover {
            background-color: var(--navigation-item-hover);
        }
        @media (max-width: 800px) {
            .navigation__item {
                width: 100%;
                text-align: center;
            }
        }
        @media (max-width: 650px) {
            .navigation__item {
                padding: calc(0.3125rem + 5 * (100vw - 320px) / ( 650 - 320) * 16 / 16 ) calc(0.625rem + 10 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            }
        }

        .navigation__link {
            color: var(--navigation-item-text-color);
            font-weight: 600;
            font-size: var(--navigation-link-fz);
        }
        @media (max-width: 650px) {
            .navigation__link {
                --navigation-link-fz: calc(0.875rem + 6 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            }
        }
        /* navigaiton styles END*/

        /* footer styles START */
        .footer {
            grid-column: 1 / 4;
            padding: 20px;
            display: flex;
            justify-content: center;
            background-image: var(--footer-bg-img);
        } 
        @media (max-width: 650px) {
            .footer {
                padding: calc(0.375rem + 14 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            }
        }

        .footer .container {
            display: flex;
            justify-content: space-between;
            width: 1150px;
        }
        .footer__logo-container {
            display: flex;
            align-items: center;
        }
        .footer__logo-link {
            display: flex;
            align-items: center;
        }
        .footer__logo {
            height: var(--footer-logo-height);
            margin-right: 20px;
            border: 2px solid #1b725e;
            border-radius: 50%;
        }
        @media (max-width: 650px) {
            .footer__logo {
                --footer-logo-height: calc(2.5rem + 20 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
                margin-right: 10px;  
            }
        }

        .footer__logo-text-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .footer__logo-text-header {
            color: var(--footer-logo-header-text-color);
            font-size: var(--footer-logo-text-size);
            font-weight: 600;
        }
        @media (max-width: 650px) {
            .footer__logo-text-header {
                --footer-logo-text-size: calc(1.25rem + 10 * (100vw - 320px) / ( 650 - 320) * 16 / 16 );
            } 
        }

        .footer__logo-text-route {
            color: var(--footer-logo-route-text-color);
        }
        @media (max-width: 650px) {
            .footer__logo-text-route {
                display: none;
            }
        }

        .content   {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
        }


        /* Animations START */
        .menu-in {
            animation: menu-in 0.5s ease forwards;
        }
        .menu-out {
            animation: menu-out 0.5s ease forwards;
        }
        @keyframes menu-in {
            0% {
                right: -100%;
            }
            100% {
                right: 0%;
            }

        }
        @keyframes menu-out {
            0% {
                right: 0%;
            }
            100% {
                right: -100%;
            }
        }

        /* Animations END */
        pre {
            font-size: 20px;
        }

        @media (max-width: 755px) {
            pre {
                font-size: calc(0.5rem + 12 * (100vw - 320px) / ( 755 - 320) * 16 / 16 );
            }
        }


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

        .mvca-terminal {
            position: fixed;
            bottom: 0px;
            display: grid;
            grid-template: 10px fit-content(100%) auto / 100vw;
            width: 100vw;
            height: var(--mvca-terminal-height);
            background-color: var(--mvca-terminal-bg-color); 
        }
        .mvca-terminal .resize-top-side {
            width: 100%;
            height: 10px;
            background-color: var(--mvca-terminal-bg-color);
            cursor: row-resize;
        }
        .mvca-terminal-navigation {
            padding-right: 50px;
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .mvca-terminal-folders {
            padding: 0 25px;
            display: flex;
            gap: 0 10px;
            width: fit-content;
            list-style-type: none;
        }

        .mvca-terminal-folder {
            padding: 5px;
            color: var(--mvca-folder-text-color);
            cursor: pointer;
            border-bottom: 3px solid var(--mvca-folder-border-bottom);
            user-select: none;
        }
        .mvca-terminal-folder:hover {
            --mvca-folder-border-bottom: #ffc66d;
            --mvca-folder-text-color:#ffc66d;
        }
        .mvca-terminal-folder.active {
            --mvca-folder-border-bottom: #cb602d;
            cursor:auto;
        }

        .mvca-terminal-navigation-status-bar {
            display: flex;
            width: fit-content;
            gap: 0 10px;
            margin-bottom: 5px;
        }
        .mvca-terminal-navigation-status {
            display: flex;
            align-items: center;
            gap: 0 3px
        }
        .mvca-terminal-navigation-status span {
            color: var(--mvca-terminal-navigation-status-color);
        }
        .mvca-terminal-navigation-status svg {
            fill: var(--mvca-terminal-navigation-status-color);
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
            padding: 10px 25px;
            background: var(--mvca-terminal-error-erea-bg-color);
        }

        .mvca-terminal-error-area pre {
            color: var(--mvca-terminal-error-text-color);
        }


        /* terminal styles END */

        /* service styles MUST BE IN THE END OF ALL STYLES */

        .hide {
            display: none;
        }

        .invisible {
            visibility: hidden;
        }

        .revertTo180Deg {
            transform: rotate(180deg);
        }
        /* service styles MUST BE IN THE END OF ALL STYLES */
    </style>
        <header class="header">
            <div class="container">
                <div class="header__logo-container">
                    <a class="header__logo-link" href="#">
                         <img class="header__logo" src="/media/logo.svg" alt="logo" >
                    </a>
                   
                    <div class="header__logo-text-container">
                        <span class="header__logo-text-header">Logo LLC</span>
                        <span class="header__logo-text-route">Header: src/view/template/common/header.php</span>
                    </div>
                </div>
                <div class="burger">
                    <img src="./media/mvca-icon.svg" alt="" class="burger__img">
                </div>

                <script>
                    window.addEventListener('DOMContentLoaded', () => {
                        const burger = document.querySelector('.burger');
                        const navigation = document.querySelector('.navigation'); 
                        const body = document.querySelector('body'); 

                        const openNavigation = () => {
                            navigation.classList.add('menu-in');
                            navigation.classList.remove('menu-out');
                        }
                        const cancelNavigation = () => {
                            navigation.classList.add('menu-out');
                            navigation.classList.remove('menu-in');
                        }
                        
                        const toggleEventNavigation = (e) => {
                            const burgerCondition = navigation.classList.contains('menu-out') || !navigation.classList.contains('menu-out') && !navigation.classList.contains('menu-in');
                            burgerCondition ? openNavigation() :  cancelNavigation();                                  
                        }; 

                        const cancelEventNavigation =  (e) => {
                            if (!navigation.contains(e.target) && !burger.contains(e.target) && navigation.classList.contains('menu-in')){
                                cancelNavigation(); 
                            }
                        };       

                        burger.onclick = toggleEventNavigation;
                        body.onclick = cancelEventNavigation;
                        body.onscroll = cancelEventNavigation;

                    })
                </script>

                <nav class="navigation">
                    <ul class="navigation__menu">
                        <div class="navigation__item">
                            <a class="navigation__link" href="#">Home</a>
                        </div>
                        <div class="navigation__item">
                            <a class="navigation__link" href="#">Samples</a>
                        </div>
                        <div class="navigation__item">
                            <a class="navigation__link" href="#">Docs</a>
                        </div>
                    </ul>
                </nav>
            </div>
        </header>
        <main>