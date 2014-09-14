<?
$APPLICATION->SetTitle("ремонт и отделка квартир в Москве | строительная компания  vector-vip");
$APPLICATION->SetPageProperty("description",  'Строительная компания  vector-vip - ремонт и отделка квартир в Москве');
$APPLICATION->SetPageProperty("keywords", 'ремонт квартир в москве отделка квартир');
?>  

<div class="scrolling-block">
    <header class="h1-header av-block">
        <div class="av-header"><h1>Новости компании vector-vip</h1></div>
    </header><!--.h1-header (главный H1)-->

    <div class="content-for-edit">
        <div class="breadcrumbs av-block">
            <a href="/">
                <div class="bc-block bc-block-1">
                    <div class="group-strelka"><div class="left-strelka-bg"></div><div class="left-strelka"></div></div>
                    <span class="link">Главная</span>
                    <div class="group-strelka"><div class="right-strelka-bg"></div><div class="right-strelka"></div></div>
                </div>
            </a>
            <div class="bc-block bc-block-2 active">
                <div class="group-strelka"><div class="left-strelka-bg"></div><div class="left-strelka"></div></div>
                <span class="link">Новости компании</span>
                <div class="group-strelka"><div class="right-strelka-bg"></div><div class="right-strelka"></div></div>
            </div>            

            <div class="height-clear"></div>
        </div><!--breadcrumbs-->

        <div id="container">
            <div id="content">
                <div id="main-search" class="av-block">
                    <div class="av-content">
                        <form name="mainSearch">
                            <input type="text" class="search" placeholder="Введите слова для поиска" />
                            <input type="submit" class="search-btn" value=""/>
                        </form>
                    </div>
                </div><!-- #main-search-->

                <section id="changebleContent" class="p_content">
                    <?
                    $copyright = $arResult["CopyrightArticle"];
                    if ($copyright):
                    ?>
                    <article id="home-article" class="av-block ">
                        <header class="av-header"><span><?=$copyright->name?></span></header>
                        
                            <div class="av-content">
                                <?=$copyright->detailText?>
                            </div>
                    </article>
                    <?  endif;?>

                </section><!-- #changebleContent-->
            </div><!-- #content-->
        </div><!-- #container-->

        <aside id="sideLeft">
            <div class="scroll-down"></div>
            <div class="scroll-top"></div>
            <div class="menu-open-btn"></div>

            <div class="content">
                <div id="side-left-additional-content">
                    <div class="inner-wrapper">
                        <?$APPLICATION->IncludeComponent('im:block', 'news', array("template" => "aside_news"))?>
                    </div>
                </div>
            </div>
        </aside><!-- #sideLeft -->
    </div><!--.content (формирует контент с отступами)-->

    <div class="height-clear"></div>
</div><!--.scrolling-block-->
