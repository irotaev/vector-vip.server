<?php
// �������������� ���������
define( 'LOG_FILENAME', $_SERVER['DOCUMENT_ROOT'] . '/_sb/mh_log.txt' );
include_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/all/libraries/Site.php');

require_once 'FrontSite.php';
//require_once 'classes/Facebook/facebook.php';

// ������� �������������� ����� ������
\MH\FrontSite::o()->setErrorLevel();

AddEventHandler( 'main', 'OnEpilog', Array( \MH\FrontSite::o()->meta, 'setupBitrixMeta' ) );
//AddEventHandler( 'iblock', 'OnBeforeIBlockElementAdd', Array( \MH\FrontSite::o()->url, 'getBitrixCodeByName' ) );
AddEventHandler( 'main', 'OnBeforeUserAdd', 'OnBeforeUserAddHandler' );
AddEventHandler( 'main', 'OnAfterUserAuthorize', array( \MH\FrontSite::o()->social, 'OnBeforePrologHandler' ) );


CPageOption::setOptionString( 'main', 'nav_page_in_session', 'N' );
COption::SetOptionString( 'main', 'component_cache_on', 'N', false, SITE_ID );


function OnBeforeUserAddHandler( &$arFields )
{
    if ( $arFields['UF_RULES'] != '1' && empty( $arFields['EXTERNAL_AUTH_ID'] ) ) {
        global $APPLICATION;
        $APPLICATION->throwException( "�� ������ ������� ������������ ����������" );
        return false;
    }
}


function OnAfterUserAuthorizeHandler( &$arFields )
{
    $f = fopen( $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/md/log.txt', 'a+' );

    global $USER;
    $rsUser = CUser::GetByID( $USER->GetId() );
    $arUser = $rsUser->Fetch();

    fwrite( $f, "---start\n" );

    if ( $arUser['ID'] > 0 && $arUser['EXTERNAL_AUTH_ID'] == 'Facebook' && $arUser['UF_RULES'] != '1' ) {
        $facebook = new Facebook_Facebook(
                array(
                    'appId'    => '447579571927341',
                    'secret'   => '2f2cf9cd60f9e98d6cf3309e6b7bde5d',
                )
        );
        $id        = $arUser["XML_ID"];

        $user_info = $facebook->api( '/' . $id . '?fields=id,name,first_name,middle_name,last_name,gender,birthday,email,picture' );

        $props = array( );

        if ( !$arUser['PERSONAL_GENDER'] ) {
            if ( isset( $user_info['gender'] ) && $user_info['gender'] ) {
                $props['PERSONAL_GENDER'] = $user_info['gender'] == 'male' ? 'M' : 'F';
            }
        }

        if ( !empty( $props ) ) {
            $user = new CUser;
            $user->Update( $arUser["ID"], $props );
        }

        CModule::IncludeModule("blog");
        $blogUser = CBlogUser::GetByID($USER->GetId(), BLOG_BY_USER_ID);
        $props = array( );

        if ( !$blogUser['AVATAR'] ) {
            if ( isset( $user_info['picture'] ) && $user_info['picture'] ) {
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, "http://graph.facebook.com/$id/picture?type=large" );
                curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 3 );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
                curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
                $output = curl_exec( $ch );

                if ( $output ){

                    $fileName = md5($user_info['picture']);
                    $fullPath = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/cache/social_pictures/$fileName.jpg";

                    if (  file_put_contents($fullPath, $output) !== false ){
                        $picture = CFile::MakeFileArray($fullPath);
                        $props['AVATAR'] = $picture;
                        $user = new CBlogUser;
                        $user->Update( $blogUser["ID"], $props );
                        unlink($fullPath);
                    }
                }
            }

        }

    }
    fwrite( $f, "---finish\n" );
    fclose( $f );
}
