<?php

require_once 'Abstract.php';
require_once 'Debug.php';
/**
 * Вставляет в header или в конец документа собранные в один файл скрипты
 *
 * @author dmitriy
 */
class MH_HelperJavascript extends MH_HelperAbstract
{
    protected $_scripts = array(
        'header'    =>  array(),
        'footer'    =>  array(),
    );
    protected $_path = '/design/js/';

    protected $_obHeaderIndex = null;
    protected $_obFooterIndex = null;
    protected $_debug = false;

    // это со старого хелпера оставлено для обратной совместимости
    protected $_isScrollerIncluded = false;
    
    protected $JSNamespaces = array();
    public $_JSdataObjects = array();

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $config = \MH\FrontSite::o()->getConfig('makeJS');
        $this->_debug = $config['debug'];
        //$this->_path = rtrim($config['path'], '/') . '/';

    }

    /**
     * @param string $name
     * @return \MH_HelperJavascript
     */
    public function addHeader($name)
    {                                
        $this->_add('header', $name);
        return $this;
    }

    /**
     * @param string $name
     * @return \MH_HelperJavascript
     */
    public function addFooter($name) {
        $this->_add('footer', $name);
        return $this;
    }

    protected function _add($key, $name)
    {
        if (!in_array($name, $this->_scripts[$key])) {
            $this->_scripts[$key][] = $name;
        }
    }

    public function getHeaderScript()
    {
        return $this->_getScript('header');
    }

    public function getFooterScript()
    {
        return $this->_getScript('footer');
    }

    public function _getScript($key)
    {
        if ($this->_debug){
            $scripts = '';
            foreach($this->_scripts[$key] as $script){
                $name = $this->_path.$script;
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$name))
                    $scripts .= '<script type="text/javascript" src="'.$name.'?'.\MH\FrontSite::o()->version.'"></script>'."\r\n";
            }
            return $scripts;
        } else {
            $name = $this->_makeName($key);
            if (!$name || file_exists($_SERVER['DOCUMENT_ROOT'] . $name)) {
                return $name;
            }

            $f = fopen($_SERVER['DOCUMENT_ROOT'] . $name, 'w+');
            foreach($this->_scripts[$key] as $file) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'].$this->_path.$file)) {
                    $content = '// ' . $file . PHP_EOL
                        . file_get_contents($_SERVER['DOCUMENT_ROOT'].$this->_path.$file);
                } else {
                    $content = '// ' . $file .' - not found';
                }
                fwrite($f, $content . PHP_EOL);
            }
            fclose($f);
            chmod ($_SERVER['DOCUMENT_ROOT'] . $name, 0664);
            return $name;
        }
    }

    protected function _makeName($key)
    {
        if (0 == count($this->_scripts[$key])) {
            return '';
        }
        if (count($this->_scripts[$key]) > 1) {
            $name = '';
            foreach($this->_scripts[$key] as $n) {
                $name .=  '-'.(preg_match('/(.*?)(\.js)?$/', $n, $m) ? $m[1] : $n);
            }
            $name = 'make/'.$key.'-'.md5($name).'.js';
        } else {
            $name = $this->_scripts[$key][0];
        }
        return $this->_path . $name;
    }

    public function showHeaderScript()
    {
        $that = $this;
        global $APPLICATION;
        
        if ($this->_debug) {
            $APPLICATION->AddBufferContent(function() use ($that) { return $that->getHeaderScript(); });
        } else {
            $APPLICATION->AddBufferContent(function() use ($that) {
                // Добавляю данные для яваскриптов
                $jsObjects = $that->getJSData();
                if (count($jsObjects)>0) {
                    $settings = '
                    <script>
                        window.JSDataStorge = window.JSDataStorge || {};

                        (function () 
                        {
                           var jsD = window.JSDataStorge;
                    ';  
                    foreach ($jsObjects as $obj) {  
                        $settings .= "jsD.$obj->namespace = {};                
                        jsD.{$obj->namespace} = ". json_encode($obj->data)
                        ;
                    }
                    $settings .= "               
                        })()                        
                    </script>       
                    ";       
                    $settings .= "
                    <script src='/design/js/JSDataStorge.js'></script>
                    ";   
                } else {
                    $settings = '';
                }
                return $settings . (
                    ($scriptName = $that->getHeaderScript())
                        ? '<script src="'.$scriptName.'?'.\MH\FrontSite::o()->version.'"></script>'
                        : ''
                );
            });
        }
    }

    public function showFooterScript()
    {
        if ($this->_debug){
            foreach($this->_scripts['footer'] as $script){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$this->_path.$script))
                    echo '<script type="text/javascript" src="'.$this->_path.$script.'?'.\MH\FrontSite::o()->version.'"></script>'."\r\n";
            }
        }
        else{
            if ( ($scriptName = $this->getFooterScript()) ) {
                echo '<script src="'.$scriptName.'?'.\MH\FrontSite::o()->version.'"></script>';
            }
        }
    }

    public function obStart()
    {
        $this->_obHeaderIndex = count($this->_scripts['header']);
        $this->_obFooterIndex = count($this->_scripts['footer']);
    }

    public function obGetClean()
    {
        if (is_null($this->_obHeaderIndex) || is_null($this->_obFooterIndex)) {
            return;
        }
        $result = array(
            'header' => array_slice($this->_scripts['header'], $this->_obHeaderIndex, count($this->_scripts['header'])-$this->_obHeaderIndex),
            'footer' => array_slice($this->_scripts['footer'], $this->_obFooterIndex, count($this->_scripts['footer'])-$this->_obFooterIndex),
        );
        $this->_obHeaderIndex = null;
        $this->_obFooterIndex = null;
        return $result;
    }

    // это со старого хелпера оставлено для обратной совместимости
    public function includeScroller()
    {
        if (!$this->_isScrollerIncluded) {
            global $APPLICATION;
            $APPLICATION->addHeadString('<script type="text/javascript" src="/design/js/jquery.tools.scroll.min.js"></script>');
            $this->_isScrollerIncluded = true;
        }
        return $this;
    }
    
    // Передает данные в JS
    public function addJSData($data, $namespace = null)
    {
        // Инициализация
        $currentNamespace = $namespace;
        $jsDataObj = null;
            
            // Проверка на мерность массива (мерность должна быть = 1)
        $checkDimension = function ($array) use (&$checkDimension)
        {   
            if (is_array(reset($array)))  
              $return = $checkDimension(reset($array)) + 1;
            else
              $return = 1;

            return $return;
        };                    
        
        // Проверка массива данных 
        if (!isset($data) || !is_array($data) || $checkDimension($data) != 1) 
            return null;                
        
        // Устанавливаем пространство имен
        if (is_null($currentNamespace)) {
            $currentNamespace = end($this->JSNamespaces).'_AUTOADD';
            $this->JSNamespaces[] = $currentNamespace;
        } else {
            // Если namespace уже имеется, то добавить 
            // в существующий объект данные
            foreach ($this->_JSdataObjects as $obj) { 
                if ($obj->namespace == $currentNamespace) {                     
                    $obj->data = $obj->data + $data;
                    
                    return true;
                }
            }
                                        
            $this->JSNamespaces[] = $currentNamespace;
        }
        
        $jsDataObj = new MH_JSDataStorge($data, $currentNamespace);
        if ($jsDataObj)
            $this->_JSdataObjects[] = $jsDataObj;       
    }
    
    public function getJSData()
    {
        return $this->_JSdataObjects;
    }
}
