<?php
require_once 'Abstract.php';
/**
 * Description of MH_HelperDebug
 *
 * @property-read string $environment Текущая среда исполнения (production | staging | development)
 *
 * @author dmitriy
 */
class MH_HelperDebug extends MH_HelperAbstract
{
    protected $_environment = null;

    public function __get($name)
    {
        switch ($name) {
            case 'environment':
                return $this->_getEnvironment();
                break;

            default:
                break;
        };
    }

    protected function _getEnvironment()
    {
        if (is_null($this->_environment)) {
            
            if (isset($_SERVER['APPLICATION_ENV'])) {
                if ('mh' == $_SERVER['APPLICATION_ENV']) {
                    $this->_environment = 'production';
                } elseif ('ms' == $_SERVER['APPLICATION_ENV']) {
                    $this->_environment = 'staging';
                } else {
                    $this->_environment = 'development';
                }
            } else {
                // запускается с другого домена
                $this->_environment = 'production';
            }

        }

        return $this->_environment;
    }
}
