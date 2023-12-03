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
    <link rel="icon" href="/media/logo.svg">
    <link rel="stylesheet" href="/media/styles/style.css">
</head>
<body> 
    <header class="header">
        <div class="container">
            <div class="header__logo-container">
                <img class="header__logo" src="/media/logo.svg" alt="logo">

                <div class="header__logo-text-container">
                    <span class="header__logo-text-header">Logo LLC</span>
                    <span class="header__logo-text-route">Header: src/view/template/common/header.php</span>
                </div>
            </div>
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
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
                <a class="navigation__item navigation__link" href="/" >Home</a>
                <a class="navigation__item navigation__link" href="/Samples.main">Samples</a>
                <a class="navigation__item navigation__link" href="https://github.com/NovDmytro/mvca" >Docs</a>
            </nav>
        </div>
    </header>
    <main>