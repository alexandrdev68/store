<?php
class BIKE extends USER{
	
	static public $firstHourAmount = 20;
	static public $dayAmount = 70;
	static public $nextHourAmount = 10;
	static public $timeBuffer = 15;

/**
	 * Удаляет велосипед с указанным id
	 * Возвращает true операция прошла успешно
	 * Пример: BIKE::delete(30);
	 * @var static function
	 */
    static public function delete($bike_id){
    	$arBinfo = self::getInfo($bike_id);
    	if($arBinfo['on_rent'] != 'no'){
    		self::addMess(TEMP::$Lang['SYSTEM']['bike_in_rent']);
    		return false;
    	}elseif($arBinfo['foto'] != ''){
    		$delFile = self::delFile("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/{$arBinfo['foto']}");
    		$delFile = self::delFile("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/bike_{$arBinfo['id']}_resized_640.jpg");
    	}
    	
    	$sql = 'DELETE FROM bikes WHERE id = '.(is_numeric($bike_id) ? $bike_id : 0);
    	$result = mysql_query($sql);
    	if($result !== false){
			self::addMess(TEMP::$Lang['SYSTEM']['bike_was_deleted']);
			return true;
    	}else{
    		self::addMess(TEMP::$Lang['SYSTEM']['bike_with_id1'].$user_id.TEMP::$Lang['SYSTEM']['user_with_id2'].$sql);
    		return false;
    	}
    }
    
/**
	 * Возвращает данные о велосипеде по его id или false если велосипеда не существует.
	 * Пример: print_r(BIKE::getInfo('20202'));
	 * @var static function
	 */
	static public function getInfo($id){
		$sql = 'SELECT id, 
						model, 
						store_id,
						properties,
						foto,
						on_rent,
						serial_id FROM bikes WHERE id = "'.$id.'" LIMIT 1';
		$arRes = self::getData($sql);
		return count($arRes) > 0 ? $arRes[0] : false;
	}
	
	/**
	 *  Стартует отчет времени проката велосипеда с переданным id для пользователя с переданным id
	 *  также записывает в базу количество времени проката, переданное в часах
	 *  возвращает true в случае если операция прошла успешно
	 *  Пример:BIKE::startRent(12, 10, 1, 0); где $added - дополнительная одноразовая плата за услугу
	 */
	static public function startRent($bike_id, $user_id, $time, $added){
		//проверяем нет ли в прокате этого велосипеда или на пользователе не числится велосипед
		//$sql = "SELECT `bike_id`, `klient_id` FROM `rent` WHERE (`bike_id` = {$bike_id} OR `klient_id` = {$user_id}) AND `time_end` = 0";
		$sql1 = "SELECT `bike_id`, `klient_id` FROM `rent` WHERE `bike_id` = {$bike_id} AND `time_end` = 0";
		$result1 = mysql_query($sql1);
		$sql2 = "SELECT `bike_id`, `klient_id` FROM `rent` WHERE `klient_id` = {$user_id} AND `time_end` = 0";
		$result2 = mysql_query($sql2);
		
		$rows1 = mysql_num_rows($result1);
		
		$rows2 = mysql_num_rows($result2);
		
		
		//echo($sql); die();
		if($rows1 > 0){
			self::addMess(TEMP::$Lang['SYSTEM']['bike_in_rent']);
    		return false;
		}elseif($rows2 >= 3){
			self::addMess(TEMP::$Lang['SYSTEM']['user_in_rent']);
    		return false;
		}
		
		$time_start = time();
		//переводим часы в секунды
		$time = $time * 3600;
		
		$added *= 100;
		$added_json = addslashes(json_encode(array('added'=>$added)));
		
		$sql1 = "INSERT INTO `rent` (`bike_id`, `klient_id`, `time_start`, `project_time`, `properties`) 
									VALUES ({$bike_id}, {$user_id}, {$time_start}, {$time}, '{$added_json}')";
		$result1 = mysql_query($sql1);
		$last_id = mysql_insert_id();
		$sql2 = "UPDATE `bikes` SET `on_rent` = '{$last_id}' WHERE `id` = {$bike_id}";
    	$result2 = mysql_query($sql2);
    	if($result1 !== false && $result2 !== false){
			self::addMess(TEMP::$Lang['SYSTEM']['rent_was_started']);
			return true;
    	}else{
    		self::addMess(TEMP::$Lang['SYSTEM']['wrong_sql_request'].$sql1.' or '.$sql2);
    		return false;
    	}
	}
	
	
	/**
	 *  Останавливает отчет времени проката велосипеда с переданным id велосипеда и пункта приема
	 *  возвращает метку времени останова проката, в случае если операция прошла успешно или false
	 *  Пример:BIKE::stopRent(12, 2, 1020910921, 3600);
	 */
	static public function stopRent($bike_id, $store_id, $time_start, $project_time, $added){
		$currTime = time();
		
		$rent_period = $currTime - $time_start;
		$amount = self::getRentAmount($project_time);
		if($rent_period > $project_time){
			$amount += self::$nextHourAmount * floor(($rent_period - $project_time - self::$timeBuffer * 60) / 3600) + self::$nextHourAmount; 
		}
		
		$amount = ($amount * 100) + (int)$added;

		$sql = "UPDATE `rent` SET `time_end` = {$currTime}, `amount` = {$amount} WHERE `bike_id`= {$bike_id} AND `time_end` = 0";
		$sql2 = "UPDATE `bikes` SET `on_rent` = 'no', `store_id` = {$store_id} WHERE `id` = {$bike_id}";
		$result = mysql_query($sql);
		$result2 = mysql_query($sql2);
		if($result === false || $result2 === false){
			self::addMess(TEMP::$Lang['SYSTEM']['error_stop_rent'].$sql.'   '.$sql2);
			return false;
		}
		return array('time_stop'=>$currTime, 'amount'=>$amount);
	}

	/** Возвращает разницу между датами в виде ассоциативного
	* массива. В качестве параметра указываются метки времени.
	*	Пример: self::getTimeBetween(0, 125543235);
	*/
	static public function getTimeBetween($date1 , $date2){
		date_default_timezone_set('Europe/London');
		$datetime1 = new DateTime(date('d-m-Y H:i:s', $date1));
		$datetime2 = new DateTime(date('d-m-Y H:i:s', $date2));
		$interval = $datetime1->diff($datetime2); 
		$arRet['days'] = $interval->format('%d');
		$arRet['hours'] = $interval->format('%H');
		$arRet['minutes'] = $interval->format('%i');
		$arRet['seconds'] = $interval->format('%s');
		return $arRet;
	}
	
/**
	 * Изменяет данные велосипеда c id  переданным в качестве параметра (3-й параметр отключает/включает фильтр данных).
	 * Пример: BIKE::change(array('model'=>'aist',
	 * 							'serial_id'=>3,
	 * 							), 14, false);
	 * @var static function
	 */
	static public function change($arFields, $id, $filtr = true){
	//формирование sql запроса
		$sql = "UPDATE `bikes` SET ";
		$count = count($arFields);
		$i = 1;
		foreach($arFields as $index=>$field){
			if($field != ''|| $field != NULL){
				$sql .= $index.' = '.(gettype($field) == 'integer' ? "" : "'").($filtr === true ? self::dataFilter($field) : $field).(gettype($field) == 'integer' ? "" : "'");
				if($i < $count){
					$sql .=', '; 
				}	
			}
			$i++;
		}
		$sql .= " WHERE id = ".$id;
		if(mysql_query($sql) !==false){
			self::addMess(TEMP::$Lang['SYSTEM']['store_changed_success']);
			return true;
		}
		else {
			self::addMess(TEMP::$Lang['SYSTEM']['wrong_sql_request'].$sql);
			return false;
		}
		
	}


	/** Вычисляет стоимость аренды. В качестве параметра передается
	 *  количество времени аренды в секундах.
	 */
	static public function getRentAmount($rent_seconds){
		
		$arDiff = self::getTimeBetween(0, $rent_seconds);

		$days = $arDiff['days'];
		$hours = $arDiff['hours'];
		$minutes = $arDiff['minutes'];

		$amount = $days * 70;
		if($amount == 0){
			$amount += $amount == 0 && ($hours * 60 + $minutes <= 60 + self::$timeBuffer) ? self::$firstHourAmount : ($minutes <= self::$timeBuffer ? self::$firstHourAmount + (($hours - 1) * self::$nextHourAmount) : self::$firstHourAmount + ($hours * self::$nextHourAmount));
		}else{
			$amount += $amount > 0 && ($hours * 60 + $minutes <= 60 + self::$timeBuffer) ? self::$nextHourAmount : ($minutes <= self::$timeBuffer ? self::$nextHourAmount + (($hours - 1) * self::$nextHourAmount) : self::$nextHourAmount + ($hours * self::$nextHourAmount));
			$amount -= self::$nextHourAmount;
		}
		

		
		return $amount;			
	}

	static public function declension($value, $arDeclens){	//$value-число; $arDeclens - массив склонений типа [значение]=>['склонение']
											//[0]=>стандартное склонение, под другими значениями принимаються исключения (числа от 10 до 20 выводятся как стандарт)
		if($value>20){
			$ak = fmod($value, 10);
		}else{
			$ak = fmod($value, 100);
		};
		foreach($arDeclens as $index=>$decl){
			if($ak == $index){
				return $decl;
				break;
			};
		};
		return $arDeclens[0];
	}

	/** Возвращает информацию про закрытые прокаты на пункте (если без привязки к пункту - не передавать) за период времени переданный в функцию
	 *  (передаются метки времени в формате юникс)
	 */
	static public function getRentsFromPeriod($date_from, $dato_to, $store = 'no'){
		$sql = "SELECT `r`.`time_start`,
						`r`.`id` AS `rent_id`,
						`r`.`time_end`,
						`r`.`project_time`,
						`r`.`amount`,
						`b`.`model`,
						`b`.`id`,
						`b`.`serial_id`,
						`u`.`name`,
						`u`.`surname`,
						`u`.`patronymic` FROM `rent` `r` LEFT OUTER JOIN `bikes` `b` ON `r`.`bike_id` = `b`.`id` 
														LEFT OUTER JOIN `users` `u` ON `u`.`id` = `r`.`klient_id` 
														WHERE `r`.`time_end` >= {$date_from} AND `r`.`time_end` <= {$dato_to}  AND `r`.`time_end` <> 0".($store == 'no' ? '' : " AND `b`.`store_id` = {$store}")." AND `r`.`amount` <> -1 ORDER BY `r`.`time_end` LIMIT 5000";

		$arRes = self::getData($sql);
		return $arRes;
	}

	/** Возвращает информацию про велосипеды, которые числятся на клиенте с id, переданным в качестве пар-ра
	 *  
	 */
	static public function getKlientBikes($id_klient){
		$sql = "SELECT `b`.`id` AS `bike_id`,
						`b`.`model`,
						`b`.`serial_id`,
						`r`.`project_time`,
						`r`.`properties` AS `rent_prop`,
						`r`.`id` AS `rent_id` FROM `rent` `r` LEFT OUTER JOIN `bikes` `b` ON `r`.`bike_id` = `b`.`id` WHERE `r`.`klient_id` = {$id_klient} AND `r`.`time_end` = 0";

		$arRes = self::getData($sql);
		foreach($arRes as $num=>$bike){
			$arRes[$num]['rent_prop'] = json_decode($bike['rent_prop'], true);
		}

		return count($arRes) == 0 ? false : $arRes;
	}
	
	
/*Функция возвращает навигационную цепочку
	входные параметры: $curr_page - текущая страница; $pages - всего страниц; $len - длина вывода цепочки (>=6)
	возвращает индексный массив, где значениями являются номера страниц и:-
	"curr" - означает, что страница является текущей;
	"pred" - кнопка "Предыдущая страница";
	"next"- кнопка "Следующая страница";
	"all" - кнопка "Вывести все";
	"first" - обычно выводится как троеточие вначале цепочки;
	"last" - обычно выводится как троеточие в конце цепочки.
	
	индекс "current" - номер текущей страницы
	*/
	static public function build_nav($curr_page, $pages, $len){
	   //длинна цепочки не может быть меньше 6
	   if($len < 6) return false;
	   if($pages <= $len){
	       $index = 1;
	       if($curr_page > 1){
	       $result[$index] = '<';
	       $index++;
	       }
	       for($c = 1; $c <= $pages; $c++){
	           if($c == $curr_page){
	               $result[$index] = 'curr';
	               $index++;
	           }else{
	               $result[$index] = $c;
	               $index++;
	           }
	       }
	       if($curr_page < $pages){
	           $result[$index] = '>';
	           $index++;
	       }
	       //$index++; $result[$index] = "all";
	   }elseif($pages > $len){
	       $index = 1;
	       $val = 1;
	       if($curr_page > 1){
	           $result[$index] = '<';
	           $index++;
	       }
	       if($curr_page == (int)$val){
	           $result[$index] = "curr";
	           $index++; $val++;
	       }
	       $result[$index] = $val;
	       $index++; $val++;
	       if($curr_page <= ceil(($len-1)/2)){
	           //текущая страница в первой половине видимости
	           for($c = 1; $c<=($len-2); $c++){
	               if($curr_page == (int)$val){
	                   $result[$index] = "curr";
	                   $index++; $val++;
	               }
	               $result[$index] = $val;
	               $index++; $val++;
	           }
	           $result[$index] = "...";
	           $index++;
	           $result[$index] = $pages; $index++;
	           if($curr_page < $pages)    $result[$index] = ">";
	           //$index++; //$result[$index] = "all";
	           $result['current'] = $curr_page; return $result;
	           break;
	       }else{
	           //текущая страница за пределами видимости
	           $result[$index] = "...";
	           $index++;
	           $val = $pages-$len+1;
	           if(($curr_page+ceil(($len-1)/2)) > $pages){
	               for($c = $val; $c< $pages; $c++){
	                   if($curr_page == (int)$val){
	                       $result[$index] = "curr";
	                       $index++; $val++;
	                   }
	                   $result[$index] = $val;
	                   $index++; $val++;
	                   if($curr_page < $pages)    $result[$index] = ">"; else $result[$index] = "curr";
	               }
	           }else{
	           $val = round($curr_page - ($len-4)/2);
	           for($c = 1; $c<=($len-4); $c++){
	                   if($curr_page == (int)$val){
	                       $result[$index] = "curr";
	                       $index++; $val++;
	                   }
	                   $result[$index] = $val;
	                   $index++; $val++;
	               }
	           $result[$index] = "...";
	           $index++;
	           $result[$index] = $pages; $index++;
	           if($curr_page < $pages)    $result[$index] = ">";
	           }
	       }
	       }
	   //$index++;
	   //$result[$index] = 'all';
	   $result['current'] = $curr_page;
	   return $result;
	}
	
	//обрезает слишком большие номера страниц
	static public function navChainFilter($arChain){
		foreach ($arChain as $num=>$chain){
			if(is_numeric($chain) && $chain > 1000){
				$arChain[$num] = substr($chain, 0, 2).'...';
			}
		}
		return $arChain;
	}
}
?>