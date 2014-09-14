<?php
require_once 'Abstract.php';

/**
 * Description of MH_HelperVote
 *
 * @author d.bychkov
 */
class MH_HelperVote extends MH_HelperAbstract
{
    public function getByChannel($channel, $tplname='')
    {
        return $this->_get(array(
            'CHANNEL_SID' => $channel,
            'VOTE_ID'   => '',
        )
        , $tplname);
    }
    
    public function getById($id, $tplname='')
    {
        return $this->_get(array(
            'CHANNEL_SID' => '',
            'VOTE_ID'   => $id,            
        )
        , $tplname);
    }
    
    protected function _get($params, $tplname='')
    {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent(
            'bitrix:voting.current', 
            $tplname,
            array_merge(
                $params,
                array( 
                    'CACHE_TYPE' => 'A', 
                    'CACHE_TIME' => '3600', 
                    'AJAX_MODE' => 'N', 
                    'AJAX_OPTION_SHADOW' => 'Y', 
                    'AJAX_OPTION_JUMP' => 'N', 
                    'AJAX_OPTION_STYLE' => 'Y', 
                    'AJAX_OPTION_HISTORY' => 'N', 
                    'AJAX_OPTION_ADDITIONAL' => '' 
                ) 
            )
            
        );
        return ob_get_clean();
    }
}