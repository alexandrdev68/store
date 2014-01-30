<?ini_set('display_errors', 1);

define("PROTECTED_DIR", $_SERVER['DOCUMENT_ROOT'].'/-protected-/');
define("CURRENT_TEMPLATE", 'store_grids');
define("LIB_DIR", $_SERVER['DOCUMENT_ROOT'].'/lib/');
define('ADMIN', 552071);

error_reporting(E_ALL);

session_start(0);
date_default_timezone_set('Europe/Kiev');

spl_autoload_register('class_autoload');

function class_autoload($class){
	$class = str_replace('\\', '/', $class);
	require_once LIB_DIR.strtolower($class).'_lib_inc.php';
}

require_once(LIB_DIR.'ajax_engine_inc.php');

TEMP::init(CURRENT_TEMPLATE);

require_once($_SERVER['DOCUMENT_ROOT'].'/php_interface/db_init_inc.php');

if(isset($_GET['page'])){
	define("CURRENT_PAGE", $_GET['page']);
	//echo 'Page : "'.$_GET['page'].'"';
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
		echo '404 Page not found: "'.$_GET['page'].'"';
	}
}else{
	//will be redirect to default page
	echo $_GET['page'];
	require_once('templates/index.php');
}

//print_r($db->messages);
?>
