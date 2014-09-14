<?php
require_once 'Abstract.php';
/**
 * Description of FooterStrings
 *
 * @author d.bychkov
 */
class MH_HelperFooterStrings extends MH_HelperAbstract
{
    protected $_strings = '';

    public function add($string)
    {
        $this->_strings .= $string;
    }

    public function show()
    {
        global $APPLICATION;
        $APPLICATION->AddBufferContent(Array($this, 'get'));
    }

    public function get()
    {
        return $this->_strings;
    }

}