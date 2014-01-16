<?class Dbase {
    public $host = 'localhost';
    public $user;
    public $passw;
    public $base;
    static public $PDOConnection;
    static public $messages = array();
    
    static protected function addMess($message, $arData = array()){
        $now = date('Y-m-d H:i:s', time());
        $request = (isset($arData['request']) ? $arData['request'] : '');
        $response = (isset($arData['response']) ? $arData['response'] : '');
        $operation = (isset($arData['operation']) ? $arData['operation'] : '');
        $status = (isset($arData['status']) ? $arData['status'] : '');
        self::setRecord(array('table'=>'engine_logs', 'fields'=>array(
        												'log_date'=>$now,
        												'operation'=>$operation,
        												'request'=>$request,
        												'response'=>$response,
        												'status'=>$status,
        												'message'=>$message												
        
        										)));
        self::$messages[] = $message;
    }
    
    static protected function clearMess(){
        self::$messages = array();
    }
    
    
    
    public function m_connect(){
		try{
		    $DbaseParams = 'mysql:host='.$this->host.';dbname='.$this->base;
			self::$PDOConnection = new PDO($DbaseParams, $this->user, $this->passw);
		}catch (PDOException $e) {
			//если не подключились к БД - выход их программы
			if(!is_object(self::$PDOConnection)){
				ob_clean();
				echo json_encode(array('status'=>'bad', 'message'=>'can\'t connect to mySQL'));
				exit;
			};
			$this->addMess($e->getMessage());
		    return false;
		}
    	
    	return true;
    }
    
	static public function get_data($arParam = array('table'=>'', 'fields'=>'', 'where'=>'', 'limit'=>'10', 'sort'=>'ASC')){
		$arData = array();
		$query = 'SELECT '.(@$arParam['fields'] == '' || !isset($arParam['fields']) ? '*' : $arParam['fields']).' FROM '
							.$arParam['table'].(isset($arParam['where']) ? ' WHERE '.$arParam['where'] : '')
							.(isset($arParam['limit']) ? ' LIMIT '.$arParam['limit'] : '')
							.@$arParam['sort'];
		try{
			if(!is_object(self::$PDOConnection)) throw new PDOException('don\'t connect to database');
			$arRes = self::$PDOConnection->query($query, 2);
			if(count($arRes) < 1 || $arRes == '') return array('status'=>0, 'data'=>array(), 'message'=>'query: '.$query.' OK.');
			foreach($arRes as $row) {
			  $arData[] = $row;
			}
		}catch(PDOException $e){
			return array('status'=>101, 'data'=>false, 'message'=>'query: '.$query.' return error: '.$e->getMessage());
		}
		
		return array('status'=>0, 'data'=>$arData, 'message'=>'query: '.$query.' OK.');
    }
    
    static protected function getData($sql){
    	$arResult = array();
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
		$arResult = array();
		try{
			foreach(self::$PDOConnection->query($query, 2) as $row) {
		        $arResult[] = $row;
		    }
		}catch(PDOException $e){
			self::addMess('mysql request error: '.$e->getMessage(), array('sql'=>$query));
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
	
	
	/**
	 * Добавляет в заданную таблицу запись с указанными ячейками
	 * Пример: DBase::setRecord(array('table'=>'gm_log_operation', 'fields'=>array(
     *          													'dt_created'=>date('Y-m-d h:i:s', time()),
     *          													'operation'=>'login ',
     *         													'status'=>$err_no,
     *          													'input_data'=>'login: '.$ldap_login,
     *          													'response_str'=>$this->db->add_slash($err_text)
     *          												)));;
	 * @var static public function
	 */
	static public function setRecord($arParams, $filtr = true){
		if(isset($arParams['table']) && count($arParams['fields']) > 0){
			$sql_p1 = 'INSERT INTO `'.$arParams['table'].'` (';
		//формирование sql запроса
			$sql_p2 = ") VALUES (";
			$count = count($arParams['fields']);
			$i = 1;
			foreach($arParams['fields'] as $index=>$field){
					$sql_p1 .= $index;
					$sql_p2 .= (gettype($field) == 'integer' ? "" : "'").($filtr === true ? self::dataFilter($field) : $field).(gettype($field) == 'integer' ? "" : "'");
					if($i < $count){
						$sql_p2 .=', ';
						$sql_p1 .=', '; 
					}
					else $sql_p2 .= ')';
						
				$i++;
			}
			
			$res = true;
			try{
				$count = self::$PDOConnection->exec($sql_p1.$sql_p2);
				if($count == 0) $error = self::$PDOConnection->errorCode();
				else $error = 0;
			}catch(Exception $e){
				$res = false;
				$mess = $e->getMessage();
			}
			
			return ($res === true && $error === 0) ? array('status'=>true) : array('status'=>false, 'message'=>'mySQL error#'.$error, 'sql'=>$sql_p1.$sql_p2, 'error'=>$error);
		};
	}
    
}?>