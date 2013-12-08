<?class Dbase {
    public $host = 'localhost';
    public $user;
    public $passw;
    public $base;
    static public $PDOConnection;
    static public $messages = array();
    
    static protected function addMess($message){
        self::$messages[] = $message;
    }
    
    static protected function clearMess(){
        self::$messages = array();
    }
    
    
    
    public function m_connect(){
		try{
		    self::$PDOConnection = new PDO('mysql:host='.$this->host.';dbname='.$this->base, $this->user, $this->passw);
		}catch (PDOException $e) {
		    $this->addMess($e->getMessage());
		    return false;
		}
    	
    	return true;
    }
    
    public function get_data($arParam = array('table'=>'', 'fields'=>'', 'where'=>'', 'limit'=>'10', 'sort'=>'ASC')){
	$query = 'SELECT '.(@$arParam['fields'] == '' || !isset($arParam['fields']) ? '*' : $arParam['fields']).' FROM '
						.$arParam['table'].(isset($arParam['where']) ? ' WHERE '.$arParam['where'] : '')
						.(isset($arParam['limit']) ? ' LIMIT '.$arParam['limit'] : '')
						.@$arParam['sort'];
	try{
		foreach(self::$PDOConnection->query($query, 2) as $row) {
	        $arData[] = $row;
	    }
	}catch(PDOException $e){
		return array('status'=>101, 'data'=>false, 'message'=>'query: '.$query.' return error: '.$e->getMessage());
	}
	
	return array('status'=>0, 'data'=>$arData, 'message'=>'query: '.$query.' OK.');
    }
    
    static protected function getData($sql){
    	try{
			foreach(self::$PDOConnection->query($sql, 2) as $row) {
		        $arResult[] = $row;
		    }
		    return $arResult;
		}catch(PDOException $e){
			self::addMess('mysql request error: '.$e->getMessage());
			return false;
		}
    }
    
    
    /**
	 * Фильтрует данные переданные через поля ввода на предмет тэгов и пробелов, числа преобразует к целому
	 * Пример: Dbase->dataFilter('12345'[, 'i']) по умолчанию работает со строками;
	 * @var public function
	 */
    static public function dataFilter($data, $i='s'){
		if($i=='s' && gettype($data) != 'integer'){
			return trim(stripslashes(strip_tags($data)));
		} elseif($i=='i' || gettype($data) == 'integer'){
			return (int)$data;
		}
			
	}
	
	/**
	 * Делает выборку из базы данных и возвращает ассоциативный массив или false если нет ни одно значения
	 * Пример: Dbase->getArray('SELECT * FROM myDB');
	 * @var public function
	 */
	public function getArray($query){
		try{
			foreach(self::$PDOConnection->query($sql, 2) as $row) {
		        $arResult[] = $row;
		    }
		}catch(PDOException $e){
			self::addMess('mysql request error: '.$e->getMessage());
		}
		return count($arResult > 0) ? $arResult : false;
	}
    
	/**
	 * Возвращает количество рядов в таблице с переданным функции именем
	 * Пример: Dbase::getCountRowsOfTable('users');
	 * @var static public function
	 */
	static public function getCountRowsOfTable($tablename){
		$sql = 'SELECT COUNT(*) FROM `'.$tablename.'`';
		
		try{
			foreach(self::$PDOConnection->query($sql, 2) as $row) {
		        $arResult[] = $row;
		    }
		}catch(PDOException $e){
			self::addMess('mysql request error: '.$e->getMessage());
		}

		return $arResult[0];
	}
    
}?>