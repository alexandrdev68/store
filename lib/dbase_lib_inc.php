<?class Dbase {
    public $host = 'localhost';
    public $user;
    public $passw;
    public $base;
    public $PDOConnection;
    static public $messages = array();
    
    static protected function addMess($message){
        self::$messages[] = $message;
    }
    
    static protected function clearMess(){
        self::$messages = array();
    }
    
    
    
    public function m_connect(){
	try{
	    $this->PDOConnection = new PDO('mysql:host='.$this->host.';dbname='.$this->base, $this->user, $this->passw);
	}catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
    	
    	$conn = mysql_connect($this->host, $this->user, $this->passw);
        if($conn === false){
            $this->addMess('mysql connection error');
            return false;
        }elseif(!mysql_select_db($this->base)){
            $this->addMess('cannot connect to database '.$this->base);
            return false;
        }else{
           $this->addMess('mysql connected');
           mysql_set_charset('utf8');
		   ob_clean();
           return true; 
        }
    }
    
    public function get_data($arParam = array('table'=>'', 'fields'=>'', 'where'=>'', 'limit'=>'10', 'sort'=>'ASC')){
	$query = 'SELECT '.(@$arParam['fields'] == '' || !isset($arParam['fields']) ? '*' : $arParam['fields']).' FROM '
						.$arParam['table'].(isset($arParam['where']) ? ' WHERE '.$arParam['where'] : '')
						.(isset($arParam['limit']) ? ' LIMIT '.$arParam['limit'] : '')
						.@$arParam['sort'];
	try{
		foreach(DB::$database->query($query, 2) as $row) {
	        $arData[] = $row;
	    }
	}catch(PDOException $e){
		return array('status'=>101, 'data'=>false, 'message'=>'query: '.$query.' return error: '.$e->getMessage());
	}
	
	return array('status'=>0, 'data'=>$arData, 'message'=>'query: '.$query.' OK.');
    }
    
    static protected function getData($sql){
    	$result = mysql_query($sql);
    	if($result !== false){
			while($arResult[] = mysql_fetch_assoc($result)){
				
			};
			array_pop($arResult);
			return $arResult;
    	}else{
    		//echo($sql);
            self::addMess('mysql request error: '.$sql);
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
		$result = mysql_query($query);
		if($result === false) return false;
		while($arResult[] = mysql_fetch_assoc($result)){
			
		};
		array_pop($arResult);
		return count($arResult > 0) ? $arResult : false;
	}
    
	/**
	 * Возвращает количество рядов в таблице с переданным функции именем
	 * Пример: Dbase::getCountRowsOfTable('users');
	 * @var static public function
	 */
	static public function getCountRowsOfTable($tablename){
		$sql = 'SELECT COUNT(*) FROM `'.$tablename.'`';
		$res = mysql_query($sql);
		$rows = mysql_fetch_array($res);
		return $rows[0];
	}
    
}?>