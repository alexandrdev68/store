<?php 
class Actions{
#---------------------------------------
	function registr_action_handler(){
		$id_user = Dbase::dataFilter(@$_POST['uLogin']);
		$user_level = (string)Dbase::dataFilter(@$_POST['uLevel']);
		$name_user = Dbase::dataFilter($_POST['uFirstname']);
		$surname_user = Dbase::dataFilter($_POST['uLastname']);
		$lastname_user = Dbase::dataFilter($_POST['uPatronymic']);
		$phone = Dbase::dataFilter($_POST['uPhone']);
		//если регистрируем клиента - логин не нужен
		if($user_level == 4) $id_user = $phone;
		$pass = @$_POST['uPassword'];
		$repeat_pass = @$_POST['uConfirmPassword'];
		//проверка полей формы
		if(mb_strlen($id_user, 'utf-8') < 3){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_login']);
			return json_encode($response);
		}elseif(!isset($_POST['uLevel'])){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['user_level_not_set']);
			return json_encode($response);
		}elseif(!preg_match('/^[0-9]{12,12}$/', $phone) && @$_POST['uLevel'] == 4){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_phone']);
			return json_encode($response);
		}elseif(mb_strlen($pass, 'utf-8') < USER::$minpasswlen && @$_POST['uLevel'] != 4){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['password_to_small1'].USER::$minpasswlen.TEMP::$Lang['SYSTEM']['password_to_small2']);
			return json_encode($response);
		}elseif($pass !== $repeat_pass && @$_POST['uLevel'] != 4){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_confirm_passw']);
			return json_encode($response);
		}
		
		//загрузка и изменение размера фото
		$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/klients/", "klient_{$id_user}.jpg");
		if($res !== false && !is_array($res)){
			//если файл загрузился успешно изменяем размер фото
			$resized = Graph::imgResize(640, 400, $res, false);
			$imagepath = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
			//print_r($imagepath);die();
			if($res === false){
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
				return json_encode($response);
			}
		}elseif(is_array($res)) return json_encode($res);
		else{
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
			return json_encode($response);
		}

		$arProperties = array();
		//формирование допсвойств, если они заданы
		if(isset($_POST['resStore'])){
			$arProperties['store'] = $_POST['resStore'];
		}
		
		//добавление пользователя в БД
		$arFields = array('login'=>$id_user,
	  							'name'=>(string)$name_user,
    							'password'=>$pass,
	  							'patronymic'=>(string)$lastname_user,
	  							'surname'=>(string)$surname_user,
								'phone'=>$phone,
								'properties'=>json_encode($arProperties),
	  							'photo'=>$imagepath, 
	  							'user_level'=>(string)@$_POST['uLevel']);
		if(USER::add($arFields)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage());
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		return json_encode($response);
	}
#---------------------------------------

	function edit_user_handler(){
		$id_user = Dbase::dataFilter($_POST['uLogin']);
		$name_user = Dbase::dataFilter($_POST['uFirstname']);
		$surname_user = Dbase::dataFilter($_POST['uLastname']);
		$lastname_user = Dbase::dataFilter($_POST['uPatronymic']);
		$id = Dbase::dataFilter($_POST['uId']);
		$phone = Dbase::dataFilter($_POST['uPhone']);
		$arUser = USER::getFullInfo($id);
		
		
		if(mb_strlen($id_user, 'utf-8') < 3){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_login']);
			return json_encode($response);
		}elseif(!isset($_POST['uLevel'])){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['user_level_not_set']);
			return json_encode($response);
		}
		
		//загрузка и изменение размера фото если пользователь ее изменил
		if(isset($_FILES['foto'])){
			$filename = "{$_SERVER['DOCUMENT_ROOT']}/upload/klients/".($arUser['photo'] == '' ? '_1.klm' : $arUser['photo']);
			if(file_exists($filename)){
				USER::delFile($filename);
			};
			$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/klients/", "klient_{$id_user}_ver".time().".jpg");
			if($res !== false && !is_array($res)){
				//если файл загрузился успешно изменяем размер фото
				$resized = Graph::imgResize(640, 400, $res, false);
				$imagepath = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
				//print_r($imagepath);die();
				if($res === false){
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
					return json_encode($response);
				}
			}elseif(is_array($res)) return json_encode($res);
			else{
				ob_clean();
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
				return json_encode($response);
			}
		}else $imagepath = '';
		
		
		
		//формирование допсвойств, если они заданы
		if(isset($_POST['resStore']) || isset($_POST['uLivePlace'])){
			if($arUser === false) $response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['wrong_sql_request']);
			$arProperties = $arUser['properties'] == 'null' ? array() : $arUser['properties'];
			if(isset($_POST['resStore']) && $_POST['resStore'] != '') $arProperties['store'] = $_POST['resStore'];
			if(isset($_POST['uLivePlace']) && $_POST['uLivePlace'] != '') $arProperties["live_place"] = Dbase::dataFilter($_POST['uLivePlace']);
			if(isset($_POST['blackList']) && $_POST['blackList'] != '') $arProperties['blackList'] = Dbase::dataFilter($_POST['blackList']);
			else $arProperties['blackList'] = 'off';
		}
		
		//print_r($arProperties); exit;
		
		//изменение данных пользователя в БД
		$arFields = array('login'=>$id_user,
	  							'name'=>(string)$name_user,
	  							'patronymic'=>(string)$lastname_user,
	  							'surname'=>(string)$surname_user,
								'properties'=>addslashes(json_encode($arProperties)),
	  							'photo'=>$imagepath,
								'phone'=>$phone, 
	  							'user_level'=>(string)@$_POST['uLevel']);
		//если пользователь не менял фото
		if($imagepath == '') unset($arFields['photo']);
		
		if(USER::change($arFields, $id, false)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage(), 'uploaded_photo'=>$imagepath == '' ? 'no' : 'yes');
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function edit_bike_handler(){
		$model = Dbase::dataFilter(@$_POST['bModel']);
		$serial = Dbase::dataFilter(@$_POST['bSerial']);
		$store_id = Dbase::dataFilter(@$_POST['bPlace']);
		$id = $_POST['bNumber'];
		if(mb_strlen($model, 'utf-8') < 2){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['small_bike_name']);
			return json_encode($response);
		}
		
		//завантажуємо фото, якщо воно є
		if(!isset($_POST['foto'])){
			$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/", "bike_{$id}.jpg");
			if($res !== false && !is_array($res)){
				//если файл загрузился успешно изменяем размер фото
				$resized = Graph::imgResize(640, 400, $res, true);
				$resized = Graph::imgResize(150, 50, $res, false);
				$imagepath = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
				//print_r($imagepath);die();
				if($res === false){
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
					return json_encode($response);
				}
			}elseif(is_array($res)) return json_encode($res);
			else{
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
				return json_encode($response);
			}
		}else $imagepath = '';
		
		$arFields = array('model'=>$model,
	  							'store_id'=>(string)$store_id,
	  							'serial_id'=>(string)$serial,
	  							'foto'=>$imagepath);
		if($imagepath == '') unset($arFields['foto']);
		
		if(BIKE::change($arFields, $id, false)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage(), 'uploaded_photo'=>$imagepath == '' ? 'no' : 'yes');
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function login_action_handler(){
		$auth = USER::authorize(array('login'=>Dbase::dataFilter($_POST['uLogin']), 'password'=>$_POST['uPassw']));
		if($auth !== false){
			$response = array('status'=>'ok', 'user_level'=>$auth);
			//print_r($_SESSION['CURRUSER']); die();
			return json_encode($response);
		}else{
			$response = array('status'=>'bad', 'message'=>USER::lastMessage());
			return json_encode($response);
		}
	}
#---------------------------------------
	function logout_handler(){
		if(isset($_SESSION['CURRUSER'])) unset($_SESSION['CURRUSER']);
		$response = array('status'=>'ok');
		return json_encode($response);
	}
#---------------------------------------
	function get_users_list_handler(){
		if(USER::isAdmin()){
			$db = new Dbase();
			$rows = Dbase::getCountRowsOfTable('users');
			$curr_page = ceil(@$_POST['from_user_id'] / 100);
			$offset = @$_POST['from_user_id'];
			$pages = ceil($rows / 100);
			$len = 8;
			$arNav = array();
			if($pages > 1){
				$arNav = BIKE::build_nav($curr_page + 1, $pages, $len);
			}
			$sql = 'SELECT id, 
					name,
					login, 
					patronymic,
					surname,
					photo, 
					properties,
					email,
					phone,
					user_level FROM users LIMIT '.$offset.', 100';
			$arResult = $db->getArray($sql);
			foreach($arResult as $num=>$user){
				switch ($user['user_level']){
					case 552071 :
						$arResult[$num]['user_level'] = 'Administrator';
						break;
					case 1 :
						$arResult[$num]['user_level'] = 'Reseption';
						break;
					case 2 :
						$arResult[$num]['user_level'] = 'User';
						break;
					case 4 :
						$arResult[$num]['user_level'] = 'Klient';
						break;
				}
				$arResult[$num]['properties'] = json_decode($user['properties'], true);
			}
			$response = array('status'=>'ok', 'users_list'=>$arResult, 'nav'=>$arNav);
			return json_encode($response);
		}else{
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['you_dont_access']);
			return json_encode($response);
		}
	}
#---------------------------------------
	function get_bikes_list_store_handler(){
			$db = new Dbase();
			$from_id = Dbase::dataFilter(@$_POST['from_bike_id']);
			if(@$_POST['filter'] == 'in_store') $on_rent = 'no';
			elseif(@$_POST['filter'] == 'on_rent') $on_rent = 1;
			
			//если у текущего пользователя есть допсвойства
			if(mb_strlen($_SESSION['CURRUSER']['properties'], 'utf-8') > 0){
				$properties = json_decode($_SESSION['CURRUSER']['properties'], true);
			}
			
			$sqlSelect = 'SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`s`.`adress`';
			
			//если пользователю ограничен обзор велосипедов только одним пунктом
			if(isset($properties['store']) && $on_rent == 'no'){
				$store_id = (int)$properties['store'];
				$sqlWhere = 'WHERE `b`.`id` >= '.$from_id.' AND `b`.`on_rent` = "'.$on_rent.'" AND `b`.`store_id` = '.$store_id.' ORDER BY `b`.`id` LIMIT 100';
			}elseif($on_rent == 1){
				$sqlSelect = 'SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`r`.`id`,
					`r`.`bike_id`,
					`r`.`time_start`,
					`r`.`project_time`,
					`r`.`klient_id`,
					`r`.`properties` AS `rent_prop`,
					`s`.`adress`,
					`u`.`name`,
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`phone`';
				$sqlWhere = 'LEFT OUTER JOIN `rent` `r` ON `b`.`id` = `r`.`bike_id` LEFT OUTER JOIN `users` `u` ON `u`.`id` = `r`.`klient_id` WHERE `b`.`id` >= '.$from_id.' AND `b`.`on_rent` = `r`.`id` ORDER BY `r`.`time_start` LIMIT 100';
			}else{
				
				$sqlWhere = 'WHERE `b`.`id` >= '.$from_id.' AND `b`.`on_rent` = "'.$on_rent.'" ORDER BY `b`.`id` LIMIT 100';
			}
			
			$sql = $sqlSelect.' FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` '.$sqlWhere;
			//echo $sql; die();
			$arResult = $db->getArray($sql);
			if($arResult === false){
				$response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['wrong_sql_request'].$sql);
				return json_encode($response);
			}
			//print_r($arResult); die();
			$now = time() * 1000;
			foreach($arResult as $num=>$bike){
				if($bike['foto'] != '') $arResult[$num]['foto'] = 'upload/bikes/'.$bike['foto'];
				if($on_rent == 1){
					$arResult[$num]['now'] = $now;
					$arResult[$num]['rent_prop'] = json_decode($bike['rent_prop'], true);
					$arResult[$num]['project_amount'] = BIKE::getRentAmount($bike['project_time']);
				}
			}
			$response = array('status'=>'ok', 'bikes_list'=>$arResult);
			return json_encode($response);
	}
#---------------------------------------
	function user_delete_handler(){
		if(USER::delete(@$_POST['uid']) === true){
			$response = array('status'=>'ok', 'mess'=>TEMP::$Lang['SYSTEM']['user_was_deleted']);

		}else $response = array('status'=>'bad', 'mess'=>USER::lastMessage());
		return json_encode($response);
	}
#---------------------------------------
	function bike_delete_handler(){
		if(BIKE::delete(@$_POST['bid']) === true){
			$response = array('status'=>'ok', 'mess'=>TEMP::$Lang['SYSTEM']['bike_was_deleted']);
		}else $response = array('status'=>'bad', 'mess'=>TEMP::$Lang['SYSTEM']['user_with_id2']);
		return json_encode($response);
	}
#---------------------------------------
	function add_bike_handler(){
		$model = Dbase::dataFilter(@$_POST['bModel']);
		$serial = Dbase::dataFilter(@$_POST['bSerial']);
		$number = Dbase::dataFilter(@$_POST['bNumber']);
		$store_id = Dbase::dataFilter(@$_POST['bPlace']);
		if(mb_strlen($model, 'utf-8') < 2){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['small_bike_name']);
			return json_encode($response);
		}elseif(!preg_match('/^[0-9]{1,}$/', $number)){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['number_uncorrect']);
			return json_encode($response);
		}elseif(BIKE::getInfo($number) !== false){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['bike_number_dublicat']);
			return json_encode($response);
		}
		//print_r(@$_FILES);die();
		//завантажуємо фото, якщо воно є
		if(!isset($_POST['foto'])){
			$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/bikes/", "bike_{$number}.jpg");
			if($res !== false && !is_array($res)){
				//если файл загрузился успешно изменяем размер фото
				$resized = Graph::imgResize(640, 400, $res, true);
				$resized = Graph::imgResize(150, 50, $res, false);
				$imagepath = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
				//print_r($imagepath);die();
				if($res === false){
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
					return json_encode($response);
				}
			}elseif(is_array($res)) return json_encode($res);
			else{
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
				return json_encode($response);
			}
		}
		//записываем в БД новый велосипед
		$foto = isset($imagepath) ? $imagepath : '';
		$sql = "INSERT INTO `bikes` (`id`, `model`, `store_id`, `foto`, `serial_id`) VALUES ({$number}, '{$model}', {$store_id}, '{$foto}', '{$serial}')";
		if(mysql_query($sql) !==false){
			$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['bike_add_success']);
		}
		else {
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_add_bike']);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function find_user_handler(){
			$db = new Dbase();
			if(USER::isAdmin()){
				$sql_where = 'WHERE (login LIKE "%'.@$_POST['key'].
						'%" OR name LIKE "%'.@$_POST['key'].
						'%" OR patronymic LIKE "%'.@$_POST['key'].
						'%" OR surname LIKE "%'.@$_POST['key'].
						'%" OR phone LIKE "%'.@$_POST['key'].
						'%") AND user_level = 4 LIMIT 20';
			}else{
				$sql_where = 'WHERE (login LIKE "%'.@$_POST['key'].
						'%" OR phone LIKE "%'.@$_POST['key'].
						'%") AND user_level = 4 LIMIT 20';
			}
			$sql = 'SELECT id, 
					name,
					login, 
					patronymic,
					surname,
					photo, 
					properties,
					phone,
					user_level FROM users '.$sql_where;
			$arResult = $db->getArray($sql);
			if($arResult === false) return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);
			foreach($arResult as $num=>$user){
				$arResult[$num]['properties'] = json_decode($user['properties'], true);
			}
			$response = array('status'=>'ok', 'find'=>$arResult);
			return json_encode($response);
	}
#---------------------------------------
	function add_klient_handler(){
		$id_user = @$_POST['uPhone'];
		$name_user = Dbase::dataFilter($_POST['uFirstname']);
		$surname_user = Dbase::dataFilter($_POST['uLastname']);
		$lastname_user = Dbase::dataFilter($_POST['uPatronymic']);
		$live_place = Dbase::dataFilter($_POST['uLivePlace']);
		//echo json_encode($live_place); die();
		$phone = @$_POST['uPhone'];
		//проверка полей формы
		if(mb_strlen($id_user, 'utf-8') < 3){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_login']);
			return json_encode($response);
		}elseif(!preg_match('/^[0-9]{12,12}$/', $phone)){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_phone']);
			return json_encode($response);
		}elseif(isset($_POST['ufoto'])){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['photo_missing']);
			return json_encode($response);
		}elseif(USER::getInfo($id_user) !== false){
			$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['mess_phone_was_created1'].$id_user.TEMP::$Lang['SYSTEM']['mess_login_was_created2']);
			return json_encode($response);
		}
		
		//загрузка и изменение размера фото
		if(isset($_FILES['foto'])){
			$res = Graph::upload_photo("{$_SERVER['DOCUMENT_ROOT']}/upload/klients/", "klient_{$id_user}.jpg");
			if($res !== false && !is_array($res)){
				//если файл загрузился успешно изменяем размер фото
				$resized = Graph::imgResize(640, 400, $res, false);
				$imagepath = mb_substr($resized['path'], mb_strrpos($resized['path'], '/') + 1);
				//print_r($imagepath);die();
				if($res === false){
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['resize_error']);
					return json_encode($response);
				}
			}elseif(is_array($res)) return json_encode($res);
			else{
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['wrong_upload']);
				return json_encode($response);
			}
		}else $imagepath = '';
		

		//добавление пользователя в БД
		$arFields = array('login'=>(string)$id_user,
	  							'name'=>(string)$name_user,
    							'phone'=>(string)$phone,
	  							'patronymic'=>(string)$lastname_user,
	  							'surname'=>(string)$surname_user,
	  							'properties'=>$live_place == '' ? '' : addslashes(json_encode (array('live_place'=>$live_place))),
	  							'photo'=>$imagepath, 
	  							'user_level'=>'4');
		//print_r($arFields); die();
		if(USER::add($arFields, false)){
			$response = array('status'=>'ok', 'message'=>USER::lastMessage());
		}else{
			$response = array('status'=>'error', 'message'=>USER::lastMessage(), 'errors'=>USER::$messages, 'request'=>$_POST);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function go_rent_handler(){
		$bike_id = Dbase::dataFilter(@$_POST['bike_id']);
		$user_id = Dbase::dataFilter(@$_POST['user_id']);
		$rent_period = Dbase::dataFilter(@$_POST['rent_period']);
		$print_flag = @$_POST['print'] == 'true' ? true : false;
		$seat_flag = @$_POST['seat'] == 'true' ? true : false;
		$added = 0;
		
		if($seat_flag === true) $added += 10;
		
		if(BIKE::startRent($bike_id, $user_id, $rent_period, $added) === true){
			$response = array('status'=>'ok', 'print'=>'no', 'message'=>USER::lastMessage());

			if($print_flag === true){
				$_SESSION['PRINT']['type'] = 'contract';
				$_SESSION['PRINT']['info'] = USER::getFullInfo($user_id);
				$_SESSION['PRINT']['info']['bikes'] = BIKE::getKlientBikes($user_id);
				$response['print'] = 'yes';
			}
		}else{
			$response = array('status'=>'bad', 'message'=>USER::lastMessage());
		}

		return json_encode($response);
	}
#---------------------------------------	
	function stop_rent_handler(){
		$db = new Dbase();
		$bike_id = Dbase::dataFilter(@$_POST['bike_id']);
		$store_id = Dbase::dataFilter(@$_POST['store_id']);
		$user_id = Dbase::dataFilter(@$_POST['user_id']);
		$added = 0;
		
		//получаем полные данные о пользователе
		$sql = 'SELECT 	`u`.`name`, 
						`u`.`patronymic`,
						`u`.`surname`,
						`u`.`login`,
						`u`.`photo`,
						`u`.`properties`,
						`u`.`email`,
						`u`.`phone`,
						`u`.`user_level`,
						`r`.`id` AS `rent_id`,
						`r`.`bike_id`,
						`r`.`time_start`,
						`r`.`time_end`,
						`r`.`project_time`,
						`r`.`properties` AS `rent_prop`,
						`b`.`model`,
						`b`.`serial_id`,
						`b`.`foto`
						 FROM `users` `u` 
							LEFT OUTER JOIN `rent` `r` ON `r`.`klient_id` = `u`.`id` 
							AND `r`.`time_end` = 0 AND `r`.`bike_id` = '.$bike_id.'
							LEFT OUTER JOIN `bikes` `b` ON `b`.`on_rent` = `r`.`id` AND `b`.`id` = '.$bike_id.' WHERE `u`.`id` = "'.$user_id.'" LIMIT 1';
		//echo $sql; die();
		$arInfo = $db->getArray($sql);
		if(count($arInfo) > 0){
			$arInfo[0]['properties'] = json_decode($arInfo[0]['properties'], true);
			$arInfo[0]['rent_prop'] = @$arInfo[0]['rent_prop'] == null ? '' : json_decode($arInfo[0]['rent_prop'], true);
		}
		$arInfo = $arInfo[0];
		
		
		if(@$arInfo['rent_prop']['added'] > 0) $added += 1000;
		
		unset($arInfo['foto']);
		unset($arInfo['time_end']);
		unset($arInfo['photo']);


		$arStop = BIKE::stopRent($bike_id, $store_id, $arInfo['time_start'], $arInfo['project_time'], $added);
		
		if($arStop !== false){
			$response = array('status'=>'ok', 'stopTime'=>$arStop['time_stop'], 'rent_amount'=>((int)$arStop['amount'] / 100), 'fullInfo'=>$arInfo, 'message'=>TEMP::$Lang['SYSTEM']['stop_rent_sucess']);

		}else $response = array('status'=>'bad', 'message'=>USER::lastMessage());
		
		
		return json_encode($response);
		
	}
#---------------------------------------
	function get_user_info_handler(){
		$klient_id = Dbase::dataFilter(@$_POST['klient_id']);

		$arUser = USER::getFullInfo($klient_id);

		if($arUser === false){
			$response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['wrong_sql_request']);
		}else{
			if($arUser['photo'] != '') $arUser['photo'] = 'upload/klients/'.$arUser['photo'];
			$arUser['now'] = time();
			//$arUser['properties'] = json_decode($arUser['properties']);
			$response = array('status'=>'ok', 'info'=>$arUser);
		} 
		
		return json_encode($response);
	}
#---------------------------------------
	function search_main_handler(){
		//поиск среди велосипедов на складе
		if(isset($_POST['#_bikesAllPage'])){
			$search = Dbase::dataFilter($_POST['#_bikesAllPage']);

			$db = new Dbase();
			$sql = 'SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`s`.`adress` FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` WHERE `b`.`id` >= 0 AND `b`.`on_rent` = \'no\' AND (`b`.`model` LIKE "%'.$search.'%" OR `b`.`serial_id` LIKE "%'.$search.'%" OR `s`.`adress` LIKE "%'.$search.'%") LIMIT 20';
			$arResult = $db->getArray($sql);
			if($arResult === false) return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);

			foreach($arResult as $num=>$bike){
				if($bike['foto'] != '') $arResult[$num]['foto'] = 'upload/bikes/'.$bike['foto'];
			}

		}elseif(isset($_POST['#_bikesRent'])){
			$search = Dbase::dataFilter($_POST['#_bikesRent']);

			$db = new Dbase();

			$sql = 'SELECT `b`.`id`, 
					`b`.`model`,
					`b`.`store_id`, 
					`b`.`properties`,
					`b`.`foto`,
					`b`.`serial_id`,
					`r`.`id`,
					`r`.`bike_id`,
					`r`.`time_start`,
					`r`.`project_time`,
					`r`.`klient_id`,
					`s`.`adress`,
					`u`.`name`,
					`u`.`patronymic`,
					`u`.`surname`,
					`u`.`phone` FROM `bikes` `b` LEFT OUTER JOIN `store` `s` ON `s`.`id` = `b`.`store_id` LEFT OUTER JOIN `rent` `r` ON `b`.`id` = `r`.`bike_id` LEFT OUTER JOIN `users` `u` ON `u`.`id` = `r`.`klient_id` WHERE `b`.`id` >= 0 AND `b`.`on_rent` = `r`.`id` AND (`b`.`model` LIKE "%'.$search.'%" OR `b`.`serial_id` LIKE "%'.$search.'%" OR `s`.`adress` LIKE "%'.$search.'%") LIMIT 20';
			$arResult = $db->getArray($sql);
			if($arResult === false) return array('status'=>'error', 'message'=>TEMP::$Lang['bad_response_find']);

			$now = time() * 1000;
			foreach($arResult as $num=>$bike){
				$arResult[$num]['now'] = $now;
			}

		}elseif(isset($_POST['#_usListPage'])){
			$search = Dbase::dataFilter($_POST['#_usListPage']);

			$db = new Dbase();
			$sql = 'SELECT id, 
					name,
					login, 
					patronymic,
					surname,
					photo, 
					properties,
					phone,
					user_level FROM users WHERE (login LIKE "%'.$search.'%" OR name LIKE "%'.$search.'%" OR patronymic LIKE "%'.$search.'%" OR surname LIKE "%'.$search.'%" OR phone LIKE "%'.$search.'%") LIMIT 20';
			$arResult = $db->getArray($sql);
			foreach($arResult as $num=>$user){
				switch ($user['user_level']){
					case 552071 :
						$arResult[$num]['user_level'] = 'Administrator';
						break;
					case 1 :
						$arResult[$num]['user_level'] = 'Reseption';
						break;
					case 2 :
						$arResult[$num]['user_level'] = 'User';
						break;
					case 4 :
						$arResult[$num]['user_level'] = 'Klient';
						break;
				}
			}

		}
		$response = array('status'=>'ok', 'find'=>$arResult);
		return json_encode($response);
	}
#---------------------------------------
	function get_stores_handler(){
		$response = array('status'=>'ok', 'stores'=>$_SESSION['STORES']);
		return json_encode($response);
	}
#---------------------------------------
	function accept_stores_handler(){
		
		foreach (@$_POST['accepted'] as $store){
			$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_changed_nothing']);
			$store['adress'] = Dbase::dataFilter($store['adress']);
			if($store['store_id'] != 'new'){
				$sql = 'UPDATE `store` SET `adress` = "'.$store['adress'].'" WHERE `id` = '.$store['store_id'];
			}else{
				$sql = 'INSERT INTO `store` (`adress`) VALUES ("'.$store['adress'].'")';
			}
			if($store['adress'] == '') continue;
			else{
				if(mysql_query($sql) !==false){
					$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_changed_success']);
				}
				else {
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_change_store']);
				}
			}
			
		}
		$db = new Dbase();
		$sql = "SELECT `id`, adress FROM `store`";
		$arRes = $db->getArray($sql);
		$_SESSION['STORES'] = $arRes;
		return json_encode($response);
	}
#---------------------------------------
	function delete_stores_handler(){
		foreach (@$_POST['deleted'] as $store){
			$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_changed_nothing']);
			if($store['store_id'] != 'new'){
				$sql = 'DELETE FROM `store` WHERE `id` = '.$store['store_id'];
				if(mysql_query($sql) !==false){
						$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['store_deleted_success']);
				}
				else {
					$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_delete_store']);
				}
			}
			
		}
		$db = new Dbase();
		$sql = "SELECT `id`, adress FROM `store`";
		$arRes = $db->getArray($sql);
		$_SESSION['STORES'] = $arRes;
		return json_encode($response);
	}
#---------------------------------------
	function get_bike_by_id_handler(){
		$arRes = BIKE::getInfo(Dbase::dataFilter($_POST['bike_id']));
		if($arRes !== false){
			if($arRes['foto'] != '') $arRes['foto'] = 'upload/bikes/bike_'.$_POST['bike_id'].'_resized_640.jpg';
			$response = array('status'=>'ok', 'bike_info'=>$arRes);
		}else $response = array('status'=>'error', 'message'=>USER::lastMessage());
		return json_encode($response);
	}
#---------------------------------------
	function day_report_handler(){
		$store_id = Dbase::dataFilter($_POST['store_id']);

		$now = time();
		$day = date('d', $now);
		$month = date('m', $now);
		$year = date('Y', $now);

		$from = strtotime($day.'-'.$month.'-'.$year.' 00:00:00');
		$to = strtotime($day.'-'.$month.'-'.$year.' 23:59:59');

		$arRents = BIKE::getRentsFromPeriod($from, $to, $store_id);
		/*foreach($arRents as $index=>$value){
			
		}*/
		$response = array('status'=>'ok', 'rents'=>$arRents);
		return json_encode($response);
	}
#---------------------------------------	
	function period_report_handler(){
		$store_id = Dbase::dataFilter($_POST['store_id']);
		$from = (int)Dbase::dataFilter($_POST['from']);
		$to = (int)Dbase::dataFilter($_POST['to']) + 86399;
		
		if($to - $from > 2764800){
			$response = array('status'=>'bad', 'message'=>TEMP::$Lang['SYSTEM']['period_to_big']);
		}else{
			$arRents = BIKE::getRentsFromPeriod($from, $to, $store_id);
			$response = array('status'=>'ok', 'rents'=>$arRents);
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function cancel_rents_handler(){
		foreach (@$_POST['cancel'] as $rent){
			$sql = 'UPDATE `rent` SET `amount` = -1 WHERE `id` = '.$rent;
			if(mysql_query($sql) !==false){
					$response = array('status'=>'ok', 'message'=>TEMP::$Lang['SYSTEM']['rent_canceled_success']);
			}
			else {
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_cancel_rent'].$sql);
			}
		}
		
		return json_encode($response);
	}
#---------------------------------------
	function recalc_fact_handler(){
		if(@$_POST['fact_time'] != '' && @$_POST['rent_id'] >= 0){
			$amount = BIKE::getRentAmount($_POST['fact_time']) * 100 + @$_POST['added'];
		
		
			$sql = 'UPDATE `rent` SET `amount` = '.$amount.' WHERE `id` = '.$_POST['rent_id'];
			
			if(mysql_query($sql) !==false){
					$response = array('status'=>'ok', 'message'=>'', 'amount'=>$amount);
			}
			else {
				$response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_recalc_rent'].$sql);
			}
			
			
		}else $response = array('status'=>'error', 'message'=>TEMP::$Lang['SYSTEM']['error_recalc_rent']);
		
		return json_encode($response);
	}
#---------------------------------------
}
?>