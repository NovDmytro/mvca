<?php
/**
 * @var string $title
 * @var array $config
 */
?>


<!DOCTYPE html>
<html lang="<?=$config['language']?>">
<head>
    <meta charset="<?=$config['charset']?>">
    <meta name="viewport" content="Height=device-Height, initial-scale=1.0">

    <title><?=$title?></title>
    <link rel="icon" href="/media/Logo.ico">
    <link rel="stylesheet" href="/media/styles/style.css">
</head>
<body> 
    <header class="header">
        <div class="container">
            <div class="header__logo-container">
                <a class="header__logo-link" href="/">
                    <img class="header__logo" src="/media/logo.svg" alt="logo" >
                </a>
            
                <div class="header__logo-text-container">
                    <span class="header__logo-text-header">Logo LLC</span>
                    <span class="header__logo-text-route">Header: src/view/template/common/header.php</span>
                </div>
            </div>
            <div class="burger">
                <img src="/media/mvca-icon.svg" alt="" class="burger__img">
            </div>

            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    const burger = document.querySelector('.burger');
                    const navigation = document.querySelector('.navigation'); 
                    const body = document.body;

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

                    const cancelEventNavigation = (e) => {
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
                    <li class="navigation__link navigation__item"><a href="/" >
                        Home
                        </a></li>
                   <li class="navigation__item navigation__link><a href="/Samples-main" ">
                           Samples
                       </a></li>
                    <li class="navigation__item navigation__link"><a href="https://github.com/NovDmytro/mvca" >
                            Docs
                        </a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>