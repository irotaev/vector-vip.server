<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>    
    <?$APPLICATION->ShowHead();?>
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <title><?$APPLICATION->ShowTitle();?></title>   
    <link rel="stylesheet" href="/design/css/screen.css" type="text/css" media="screen, projection" />
    <link rel="stylesheet" href="/design/css/jquery.mCustomScrollbar.css" type="text/css" media="screen, projection" />
    <!--[if gte IE 9]><link rel="stylesheet" href="/design/css/styleIE9.css" type="text/css" media="screen, projection" /><![endif]-->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="/design/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="/design/js/underscore-min.js"></script>
    <script type="text/javascript" src="/design/js/backbone-min.js"></script>
    <script type="text/javascript" src="/design/js/test.js"></script>

    <!-- Подключение подсказок -->
    <link rel="stylesheet" href="/design/css/tip-darkgray/tip-darkgray.css" type="text/css" media="screen, projection" />
    <script type="text/javascript" src="/design/js/jquery.poshytip.min.js"></script>
    <!-- /// Подключение подсказок -->  
    
    <!--Плагин фотогалереи и сопутствующие библиотеки-->
    <script type="text/javascript" src="/design/js/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="/design/js/jquery.fancybox.pack.js"></script>
    <link rel="stylesheet" href="/design/css/fancybox/jquery.fancybox.css" type="text/css" media="screen, projection" />
    <!-- /// Плагин фотогалереи и сопутствующие библиотеки-->
    
    <!--Скрипт для bitrix-->
    <script type="text/javascript" src="/design/js/bitrix.js"></script>
    <!--//Скрипт для bitrix-->

<!--Подключение сборочных скриптов-->
<?  require_once $_SERVER["DOCUMENT_ROOT"].'/design/index.php';?>
<!-- /// Подключение сборочных скриптов-->
</head>

<body> 
    
<!--Панель bitrix-->
<div id="bitrix-panel-controll">
    <div id="panel">
        <?$APPLICATION->ShowPanel();?>
        <div id="btn-close-open-bitrix-panel"></div>
    </div>       
</div>
<!-- /// Панель bitrix-->

    <div id="wrapper">
    <div id="main-border-left"></div>
    <div id="main-border-right"></div>

    <header id="header">
        <div class="wrapper">
            <!--<div id="logo-text-header"></div>-->
            <div id="head-menu-wrapper">
                <div id="head-main-menu"></div>

                <nav id="left-menu" class="left-menu-block">
                    <ul class="mainMenu">
                        <li class="info">
                            <a href="/">Главная</a>
                        </li>
                        <li class="info">
                            <a href="/about-company/">О компаниии</a>
                        </li>
<!--                        <li class="info">
                        <a href="/sotrudnichestvo.html">Сотрудничество</a>
                        </li>-->
                        <li class="info">
                        <a href="/nashi-raboti/">Наши работы</a>
                        </li>
                        <li class="info">
                            <a href="/news/">Новости</a>
                        </li>
                    </ul>
                </nav><!-- #left-menu-->
            </div>    

            <a href="/"><img  id="header-logo" src="<?=$pfx["design"]?>img/header/vvip-logo.png" alt="строительная компания vector-vip" /></a>

            <div id="header-telephone"></div>

            <div id="login-header">
            <form name="header-login">
            <table>
                <tr>
                    <td><label class="info" for="login-box-header">логин:</label></td>
                    <!--<td class="info reg-text"><a href="#">Регистрация</a></td>-->

                    <td colspan="2"><input type="text" id="login-box-header" placeholder="Введите логин"/></td>
                </tr>
                <tr>
                    <td><label class="info" for="password-box-header">пароль:</label></td>
                    <td colspan="2"><input type="password" id="password-box-header" placeholder="Введите пароль"/></td>
                </tr>
                <tr>
                    <td><input type="image" src="<?=$pfx["design"]?>img/header/login-input.png" alt="Войти"/></td>
                    <td class="info fogot-reg-text">
                        <span>
                            <a class="reg-text" href="#">Регистрация</a>
                            <br/><a href="#">Забыли пароль?</a>
                        </span>
                    </td>
                </tr>
            </table>
            </form>
            </div><!--#login-header-->
        </div><!--.wrapper-->
    </header><!-- #header-->

    <section id="middle">
        <div id="middle-scrolling-section">