<?php
namespace VV;

/**
 * Description of Router
 *
 * @author dmitriy
 */
class Router
{
    protected $_routes = array();
    
    protected $_currentUrl = '';
    
    protected $_currentRoute = null;
    
    public function __construct($config) {
        $this->_routes = $config;
    }
    
    public function makeUrl($name, $params = array())
    {
        if (!array_key_exists($name, $this->_routes)) {
            throw new \Exception('Route '.$name.' not found');
        }
        
        $url = $this->_routes[$name]['template'];
        if (count($params) > 0) {
            $search = array();
            $replace = array();
            foreach($params as $param => $value) {
                $search[] = '#'.$param.'#';
                $replace[] = $value;
            }
            $url = str_replace($search, $replace, $url);
        }

        return $url;
    }
    
    public function setUrl($url)
    {
        $this->_currentUrl = $url;
        $this->_currentRoute = null;
        return $this;
    }
    
    public function dispatch()
    {
        if (!is_null($this->_currentRoute)) {
            return $this->_currentRoute;
        }
        
        // ������� http:// � �����
        $cutUrl = $paramUrl = str_replace(array('http://', $_SERVER['HTTP_HOST']), '',  $this->_currentUrl);
        
        // ������� ���������, ���� ����
        if ( ($pos = strpos($paramUrl, '?')) ) {
            $cutUrl = substr($paramUrl, 0, $pos);
        }

        // ������� �����
        $this->_currentRoute = false;
        foreach($this->_routes as $route => $page) {
            if (isset($page['query_rule'])) {
                $pattern = $page['query_rule'];
                $subject = $paramUrl;
            } else {
                $pattern = $page['rule'];
                $subject = $cutUrl;
            }
            if (preg_match('~'.$pattern.'~', $subject, $m)) {
                // �����, �������������� ���������
                $this->_currentRoute = array(
                    'route'  => $route,
                    'layout' => isset($page['layout']) ? $page['layout'] : '', 
                    'module' => $page['module'], 
                    'params' => array(),
                    'ajax'  => isset($page['ajax']) ? $page['ajax'] : false,
                );
                if (isset($page['rule_params'])) {
                    foreach((array)$page['rule_params'] as $pos => $param) {
                        $this->_currentRoute['params'][$param] = isset($m[$pos]) ? $m[$pos] : null;
                    }
                }
                if (isset($page['fixed_params'])) {
                    $this->_currentRoute['params'] = array_merge(
                        $this->_currentRoute['params'], $page['fixed_params']
                    );
                }
                break;
            }
        }
        return $this->_currentRoute;
    }
    
}