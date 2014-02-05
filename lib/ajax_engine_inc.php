<?php
if(isset($_POST['action'])){
	if(!isset($_SESSION['CURRUSER']) && @$_POST['action'] !== 'login_action'){
		echo json_encode(array('status'=>'session_close'));
		exit;
	}
	//require_once($_SERVER['DOCUMENT_ROOT'].'/php_interface/db_init_inc.php');
	$temp = new TEMP();
	if(!isset($_GET['page'])){
		TEMP::component('localization', array('language'=>isset($_SESSION['user_lang']) ? $_SESSION['user_lang'] : 'ua'), false);
		$actions = new Actions();
	}else{
		echo __NAMESPACE__.'\\'.'Actions'; die();
		//$actions = new __NAMESPACE__.'\\'.'Actions';
	}
	
	$method = $_POST['action'].'_handler';
	if(method_exists($actions, $method)){
		echo $actions->$method();
		exit;
	}else{
		echo json_encode(array('status'=>'bad', 'err'=>'action not exists'));
		exit;
	}
}
?>