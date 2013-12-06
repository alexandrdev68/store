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
	function user_delete_handler(){
		if(USER::delete(@$_POST['uid']) === true){
			$response = array('status'=>'ok', 'mess'=>TEMP::$Lang['SYSTEM']['user_was_deleted']);

		}else $response = array('status'=>'bad', 'mess'=>USER::lastMessage());
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
}
?>