<?php
namespace VV;

/**
 * @property-read string $version
 * @property-read Router $router
 * @property-read \MH_User $user
 * @property-read \MH_Acl $acl
 * @property-read \MH_HelperMeta $meta
 * @property-read \MH_HelperUrl $url
 * @property-read \MH_HelperIblock $iblock
 * @property-read \MH_HelperText $text
 * @property-read \MH_HelperHtml $html
 * @property-read \MH_HelperDatetime $datetime
 * @property-read \MH_HelperJavascript $javascript
 * @property-read \MH_HelperVote $vote
 * @property-read \MH_HelperBanner $banner
 * @property-read \MH_HelperDebug $debug
 * @property-read \MH_HelperFooterStrings $footerStrings
 * @property-read \MH_HelperExternalJsApi $externalJsApi
 * @property-read \MH_HelperWebRender $webRender
 * @property-read \MH_HelperSp $sp
 *
 * @author dmitriy
 */
class FrontSite
{
    /**
     *
     * @var \MH\FrontSite
     */
    static private $_o = NULL;

    protected $_router = null;
    protected $_helpers = null;
    protected $_config = array();

    /**
     *
     * @var MH_User
     */
    protected $_user = null;

    /**
     *
     * @var MH_Acl
     */
    protected $_acl = null;

    protected $_layout = 'article';

    /**
     * @var \MH_HelperDebug
     */
    protected $_debug = null;


    private function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));

        // ������ ��� ������� �������� �������� - router, debug � acl
        require_once 'helpers/Debug.php';
        $this->_debug = new \MH_HelperDebug;
        $this->setErrorLevel();
    }

    private function __clone()
    {
    }

    /**
     *
     * @return \MH\FrontSite
     */
    static public function o()
    {
        if (self::$_o == NULL) {
            self::$_o = new self();
            self::$_o->_routerBootstrap();
        }
        return self::$_o;
    }

    public function setErrorLevel($forceEnv = '')
    {
        // �������� ������� �����, ����� ���������
        if ('production' != $forceEnv && 'production' != $this->_debug->environment) {
            set_error_handler(function ($errno, $errstr, $errfile, $errline)
            {
                if (   strpos($errfile, '/bitrix/modules') !== false
                    || strpos($errfile, '/bitrix/components/bitrix/') !== false
                    || strpos($errfile, '\\bitrix\\modules') !== false
                    || strpos($errfile, '\\bitrix\\components\\bitrix\\') !== false
                    || 2048 == $errno // Avod message "Non-static method SomeMethod should not be called statically"
                ) {
                    return true;
                }
                return false;
            });
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }

    public function __get($name)
    {
        if ('router' == $name) {
            return $this->_router;
        }
        if ('user' == $name) {
            if (is_null($this->_user)) {
                $this->_user = \MH_UserRepository::o()->getCurrent();
            }
            return $this->_user;
        }

        if ('acl' == $name) {
            if (is_null($this->_acl)) {
                $this->_acl = new \MH_Acl($this->getConfig('acl'));
            }
            return $this->_acl;
        }

        if ('debug' == $name) {
            return $this->_debug;
        }

        if ('version' == $name) {
            $version = $this->getConfig('version');
            return is_array($version) ? array_shift($version) : '';
        }

        if (!isset($this->_helpers[$name])) {
            // ���� ������
            $filename = dirname(__FILE__).'/'.'helpers/'.ucfirst($name).'.php';
            if (!file_exists($filename)) {
                return null;
            }
            require_once $filename;

            $className = 'MH_Helper'.ucfirst($name);
            // ���� ����� �������
            if (!class_exists($className)) {
                return null;
            }
            $config = $this->getConfig($name);

            $this->_helpers[$name] = new $className($config);
        }

        return $this->_helpers[$name];
    }

    public function getConfig($name)
    {
        if (array_key_exists($name, $this->_config)) {
            return $this->_config[$name];
        }

        $configName = false;

        // ��� ������������� � ������������� ����� ���������� ����� ���� �������
        if (  'production' != $this->_debug->environment ) {
            $configName = dirname(__FILE__).'/'.'config/'.$this->_debug->environment.'/'.lcfirst($name).'.php';
            if (!file_exists($configName)) {
                $configName = false;
            }
        }
        if (!$configName) {
            $configName = dirname(__FILE__).'/'.'config/'.lcfirst($name).'.php';
        }

        if (file_exists($configName)) {
            include $configName;
        } else {
            return $this->_config[$name] = array();
        }
        if (!isset($config)) {
            $config = array();
        }
        return $this->_config[$name] = $config;
    }

    /**
     * �������� ��������������
     */
    protected function _routerBootstrap()
    {
        // ����
        require dirname(__FILE__).'/config/routes.php';

        // ������� ������
        require_once dirname(__FILE__).'/Router.php';
        $this->_router = new Router($routes);

    }

    public function setLayout($layout)
    {
        $this->_layout = $layout;
    }

    public function autoload($classname)
    {
        if (class_exists($classname) || interface_exists($classname)) {
            return;
        }                                
        
        // Поддержка namespace
        if (strstr($classname, "\\"))
        {
            $dirs = explode('\\', $classname);            
            $prefix = count($dirs) > 1 ? array_shift($dirs) : '';
            $file   = array_pop($dirs);            
            $filename = dirname(__FILE__) . '/classes/'
                . ( empty($dirs) ? '' : implode('/', $dirs) . '/'  )
                . $file . '.php';            
            
            if (file_exists($filename))
            { 
                require_once $filename;
                
                if (class_exists($classname))
                { 
                    return;
                }
            }                         
        }
        
        $dirs   = explode('_', $classname);
        $prefix = count($dirs) > 1 ? array_shift($dirs) : '';
        $file   = array_pop($dirs);
        
        // ����� ����������?
        if ($prefix != 'MH') {
            $filename = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/include/shared/'
                . ($prefix ? ucfirst(strtolower($prefix)).'/' : '')
                . (empty($dirs) ? '' : ucwords(strtolower(implode('/', $dirs))).'/' )
                . $file . '.php';

            if (file_exists($filename)) {
                require_once $filename;
                return;
            }
        }

        // ������� � ����� �����
        $filename = dirname(__FILE__) . '/classes/'
            . ( empty($dirs) ? '' : implode('/', $dirs) . '/'  )
            . $file . '.php';

        if (file_exists($filename)) {
            require_once $filename;
            return;
        }

        // � ����� ����������
        if (!empty($dirs)) {
            $component = strtolower(array_shift($dirs));
            $filename = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH
                . '/components/im/block/'
                . $component . '/classes/'
                . ( empty($dirs) ? '' : implode('/', $dirs) . '/'  )
                . $file . '.php';
            if (file_exists($filename)) {
                require_once $filename;
            }
        }
    }



}
