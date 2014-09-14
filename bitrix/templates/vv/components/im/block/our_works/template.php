<?
$APPLICATION->SetTitle("Работы компании vector-vip");
$APPLICATION->SetPageProperty("description",  'Работы компании vector-vip');
$APPLICATION->SetPageProperty("keywords", 'ремонт квартир строительная компания');
?> 

<div class="scrolling-block">
    <header class="h1-header av-block">
        <div class="av-header"><h1>Работы компании vector-vip</h1></div>
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
                <span class="link">Работы компании</span>
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
                    <article id="home-article" class="av-block">
                        <header class="av-header"><span>Работы компании</span></header>
                        
                        <?$counter1 = 1;?>
                        <?  foreach ($arResult["ourWorksArticles"] as $article):?>
                        <article class="av-preview">
                            <header class="header"><?=$article->name?></header>

                            <div class="av-content article">
                                <?=$article->detailText?>
                            </div>

                            <div class="av-content photo-gallery">
                                <?$counter2 = 1;?>
                                <?  foreach ($article->imageGallery as $image):?>
                                <?if ($counter2 == 1 || $counter2  % 3 == 0):?>
                                <!--<tr>-->
                                <?endif;?>
                                <div class="gallery-item">     
                                    <a class="fancybox" href="<?=$image->GetUrl()?>" title="<?=$article->name?>" rel="group<?=$counter1?>"> 
                                        <img src="<?=$image->Crop(210, 140)->GetUrl()?>" alt="<?=$article->name?>"/>
                                    </a></div>
                                <?$counter ++;?>    
                                <?if ($counter2 == 1 || $counter2  % 3 == 0):?>
                                <!--</tr>-->
                                <?endif;?>
                                <?  endforeach;?>   

                                <div class="clear-both"></div>
                             </div>
                        </article>
                        <?$counter1++;?>
                        <?  endforeach;?>
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
