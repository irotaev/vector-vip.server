<?php



/**
 * @author: r.galeev
 * @email: r.galeev@imedia.ru
 */
class MH_Redirect_Short
{

    protected $_url;

    protected $_newUrl;

    protected $_sets = array(
        array( '~^/26kur/~', '/diet/videorecipes/kak-prigotovit-kuriczu-26-reczeptov/' ),
    );


    public function __construct( $url )
    {
        $this->_url = $url;
    }


    public function isMatch()
    {
        foreach ( $this->_sets as $rule ) {
            if ( preg_match( $rule[0], $this->_url, $m ) ) {
                $this->_newUrl = $rule[1];
                return true;
            }
        }

        return false;
    }


    public function getUrl()
    {
        if ( !$this->isMatch() ) {
            return false;
        }
        return $this->_newUrl;
    }

}