<?

class MH_HelperBanner extends MH_HelperAbstract
{
    
    protected $_cacheTime = 0;
    protected $_cacheDir = '';
    
	protected $_typeSids = array();
    

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        
        $this->_cacheTime = 1*60*60;
        $this->_cacheDir = SITE_ID.'/banners';
    }
    
	// возвращает HTML произвольного баннера по типу
	public function get($typeSID)
	{
		// ƒл€ показа баннера одного типа только 1 раз на странице
		if(!empty($this->_typeSids[$typeSID])){
			return false;
		}

		if (!empty($_SESSION['SESS_SHOW_INCLUDE_TIME_EXEC'])) {
			$debugKey = $_SESSION['SESS_SHOW_INCLUDE_TIME_EXEC'];
			$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"] = 'N';
		}
        
		$cacheBlock = new CPHPCache();
        
        $url = CAdvBanner::GetCurUri();
        if ( ($pos = strpos(CAdvBanner::GetCurUri(), '?')) !== false ) {
            $url = substr($url, 0, $pos);
        }
        $cacheId = $typeSID.'-'.$url;

		if ($cacheBlock->StartDataCache($this->_cacheTime, $cacheId, $this->_cacheDir)) {
            $result = CAdvBanner::Show($typeSID, '', '');
            $cacheBlock->EndDataCache(array('VARS' => $result));
		} else {
            $vars = $cacheBlock->GetVars();
            $result = $vars['VARS'];
		}
        
		if(!empty($_SESSION['SESS_SHOW_INCLUDE_TIME_EXEC'])){
			$_SESSION['SESS_SHOW_INCLUDE_TIME_EXEC'] = $debugKey;
		}
        
		/*ѕодсчЄт показов*/
		if(empty($this->_typeSids[$typeSID])){
			$this->_typeSids[$typeSID] = 1;
		} else {
			$this->_typeSids[$typeSID] = $this->_typeSids[$typeSID]+1;
		}
		/**/
		return $result;
	}

	public function show($typeSID) {
		echo $this->get($typeSID);
	}
}