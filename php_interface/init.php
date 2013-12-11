<?ini_set('display_errors', 1);

define("PROTECTED_DIR", $_SERVER['DOCUMENT_ROOT'].'/-protected-/');
define("DEFAULT_TEMPLATE", 'store');

error_reporting(E_ALL);

session_start(0);
date_default_timezone_set('Europe/Kiev');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/main_lib_inc.php');

TEMP::init(DEFAULT_TEMPLATE);

require_once($_SERVER['DOCUMENT_ROOT'].'/php_interface/db_init_inc.php');

if(isset($_GET['page'])){
	if(is_dir(PROTECTED_DIR.$_GET['page'])){
		require_once(PROTECTED_DIR.$_GET['page'].'/model.php');
		TEMP::component('localization', array('language'=>isset($_SESSION['user_lang']) ? $_SESSION['user_lang'] : 'ua'), false);
		//ob_clean();
		require_once ($_SERVER['DOCUMENT_ROOT'].'/'.TEMP::$header_path);
		require_once(PROTECTED_DIR.$_GET['page'].'/controller.php');
		require_once(PROTECTED_DIR.$_GET['page'].'/view.php');
		require_once ($_SERVER['DOCUMENT_ROOT'].'/'.TEMP::$footer_path);
	}else{
		//will be redirect to 404 page
	}
}else{
	//will be redirect to default page
	require_once('templates/index.php');
}

//print_r($db->messages);
?>
