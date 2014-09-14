<section id="aside-news" class="av-block">
    <header class="av-header"><span>Новости</span></header>

    <div class="av-content">
        <?foreach($arResult["news"] as $news):?>
        <article class="news-block">
            <header><?=date( 'Y-m-d', strtotime($news->dateActiveFrom))?></header>

            <div class="content">
                <a href="/news/<?=$news->id?>"><?=$news->name?></a> <br/>
                <?=$news->previewText?>
            </div>
        </article>
        <?  endforeach;?>
    </div>
</section><!-- #aside-news-->
