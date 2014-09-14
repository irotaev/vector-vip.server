<?php

$config = array(
    'admin' => array( ),
    'front' => array(
        array(
            'module'  => 'main',
            'event'   => 'OnAfterUserAuthorize',
            'handler' => array( \MH\FrontSite::o()->social, 'OnAfterUserAuthorizeHandler' )
        ),
        array(
            'module'  => 'main',
            'event'   => 'OnBeforeProlog',
            'handler' => array( \MH\FrontSite::o()->social, 'OnBeforePrologHandler' )
        ),
        array(
            'module'  => 'main',
            'event'   => 'OnEndBufferContent',
            'handler' => array(\MH\FrontSite::o()->publishingStatus, 'OnEndBufferContentHandler')
        ),
    ),
);