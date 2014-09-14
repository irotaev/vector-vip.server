<?
/**
 * Логирование 404
 */
class Logger404
{

    private $_dontLogMe = array(
    );

    protected $_logFileName;
    protected $_serverState;

    
    public function __construct($logFileName)
    {
        $this->_logFileName = $logFileName;
    }

    /**
     * Пишет логи с проверкой бот или нет
     * @param array $serverState $_SERVER
     * @return <type>
     */
    public function log($serverState)
    {
        $this->_serverState = $serverState;
        
        foreach ($this->_dontLogMe as $rule) {
            if (preg_match($rule, $this->_serverState['REQUEST_URI'])) {
               return;
            }
        }

        
        if ($this->_isBot()) {
            return;
        }

        $time = date('H:i:s Y-m-d', $this->_serverState['REQUEST_TIME']);
        $this->_serverState['REQUEST_URI'] = urldecode($this->_serverState['REQUEST_URI']);

        $info404 = array();
        $info404[] = 'REQUEST_TIME: ' . $time;
        $info404[] = 'REQUEST_URI: ' . $this->_serverState['REQUEST_URI'];
        if(!empty($this->_serverState['HTTP_REFERER'])) {
            $info404[] = 'HTTP_REFERER: ' . $this->_serverState['HTTP_REFERER'];
        }
        $info404[] = 'HTTP_REMOTE_ADDR: ' . (isset($this->_serverState['HTTP_REMOTE_ADDR']) ? $this->_serverState['HTTP_REMOTE_ADDR'] : '');
        $info404[] = 'REMOTE_ADDR: ' . (isset($this->_serverState['REMOTE_ADDR']) ? $this->_serverState['REMOTE_ADDR'] : '');
        if(!empty($this->_serverState['HTTP_USER_AGENT'])) {
            $info404[] = 'HTTP_USER_AGENT: ' . $this->_serverState['HTTP_USER_AGENT'];
        }

        file_put_contents($this->_logFileName, join("\n", $info404) . "\n=======================================================\n\n\n", FILE_APPEND);
    }

    protected function _isBot()
    {
        return    stripos($this->_serverState['REQUEST_URI'], '/_vti_bin')!==false
               || stripos($this->_serverState['REQUEST_URI'], '/MSOffice')!==false;
    }


}