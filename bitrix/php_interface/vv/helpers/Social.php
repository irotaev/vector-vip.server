<?php

require_once 'Abstract.php';



/**
 * Description of user
 *
 * @author r.galeev
 */
class MH_HelperSocial extends MH_HelperAbstract
{


    public function __construct()
    {

    }


    public function OnAfterUserAuthorizeHandler( &$arFields )
    {

        global $USER;
        $rsUser = CUser::GetByID( $USER->GetId() );
        $arUser = $rsUser->Fetch();

        $social = MH_Social_SocialFabric::getSocial( $arUser['EXTERNAL_AUTH_ID'], $arUser["XML_ID"] );

        if ( $arUser['ID'] > 0 && $arUser['UF_RULES'] != '1' ) {

            $props = array( );

            if ( !$arUser['PERSONAL_GENDER'] ) {
                $props['PERSONAL_GENDER'] = $social->getGender();
            }
            if ( !$arUser['PERSONAL_BIRTHDAY'] ) {
                $props['PERSONAL_BIRTHDAY'] = $social->getBirthday();
            }

            if ( !empty( $props ) ) {
                $user = new CUser;
                $user->Update( $arUser["ID"], $props );
            }


            CModule::IncludeModule( "blog" );
            $blogUser = CBlogUser::GetByID( $arUser['ID'], BLOG_BY_USER_ID );


            if ( !$blogUser || !$blogUser['AVATAR'] ) {
                $props = array( 'USER_ID' => $arUser['ID'] );

                if ( $path = $social->getPicture() ) {
                    $picture         = CFile::MakeFileArray( $path );
                    $props['AVATAR'] = $picture;
                    $user            = new CBlogUser;
                    if ( $blogUser ) {
                        $user->Update( $blogUser["ID"], $props );
                    } else {
                        $user->Add( $props );
                    }

                    unlink( $path );
                }
            }
        }
    }


    public function OnBeforePrologHandler()
    {
        global $USER;
        $rsUser = CUser::GetByID( $USER->GetId() );
        $arUser = $rsUser->Fetch();
        $uri = $_SERVER['REQUEST_URI'];

        if ( $pos = strpos( $_SERVER['REQUEST_URI'], '?' ) ){
            $uri = substr($_SERVER['REQUEST_URI'],0,$pos);
        }

        if ( $arUser['ID'] > 0 && $arUser['UF_RULES'] != '1' && $uri != '/licenzionnoe-soglasheniye/' && $uri != '/personal/registration/license_agreement.php' ) {
            $backUrl = '';

            if ( isset( $_GET['backurl'] ) ){
                $backUrl = '?backurl='.$_GET['backurl'];
            }
            LocalRedirect("/licenzionnoe-soglasheniye/".$backUrl);
        }
    }
}
