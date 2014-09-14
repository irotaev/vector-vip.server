<?
$APPLICATION->SetTitle("404 страница");
$APPLICATION->SetPageProperty("description",  'Строительная компания  vector-vip - ремонт и отделка квартир в Москве');
$APPLICATION->SetPageProperty("keywords", '404 страница');
?>  

<div class="scrolling-block">
    <header class="h1-header av-block">
        <div class="av-header"><h1>Запрашеваемая вами страница не найдена</h1></div>
    </header>

    <div class="content-for-edit">
        <div id="container">
            <div id="content">
                <div id="main-img-wrapper"></div>

                <div id="main-search" class="av-block">
                    <div class="av-content">
                        <form name="mainSearch">
                            <input type="text" class="search" placeholder="Введите слова для поиска" />
                            <input type="submit" class="search-btn" value=""/>
                        </form>
                    </div>
                </div><!-- #main-search-->

                <section id="changebleContent" class="p_content">
                    <article id="home-article" class="av-block ">
                        <header class="av-header"><span>Запрашеваемая вами страница не найдена</span></header>

                        <div class="av-content">
                             Проверьте правильность написания URL.
                             Либо перейдите на главную страницу или воспользуйтесь картой сайта.
                        </div>
                    </article>
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