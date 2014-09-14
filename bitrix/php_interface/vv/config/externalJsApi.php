<?php
// подключаемые в head (headd), верхней части body (top-body) в нижней части body(bottom-body) скрипты
$socConfig = \MH\FrontSite::o()->getConfig( 'social' );
$config = array(
    'facebook'  => array(
        'head'      => '<meta property="fb:app_id" content="'.$socConfig['facebook']['appId'].'" /><meta content="article" property="og:type" />',
        'top-body'  => <<<FBTOP
<div id="fb-root"></div>
<script src="http://connect.facebook.net/ru_RU/all.js"></script>
<script type="text/javascript">
    FB.init({
        appId : '{$socConfig['facebook']['appId']}',
        channelUrl : 'http://'+window.document.location.hostname+'/static/channel.php',
        oauth : false,
        status : true, // check login status
        cookie : true, // enable cookies to allow the server to access the session
        xfbml : true // parse XFBML
    });
</script>
FBTOP
    ),
    
    'vk'        => array(
        'head'      => <<<VKHEAD
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?52"></script>
<script type="text/javascript">
  VK.init({apiId: {$socConfig['vkontakte']['appId']}, onlyWidgets: true});
</script>
VKHEAD
        ,
    ),
    'twitter'   => array(
        'head'      => <<<TWHEAD
<script src="http://platform.twitter.com/anywhere.js?id={$socConfig['twitter']['consumer_key']}&v=1" type="text/javascript"></script>
TWHEAD

    ),
);