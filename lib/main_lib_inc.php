<?
spl_autoload_register('class_autoload');

function class_autoload($class){
	require_once strtolower($class).'_lib_inc.php';
}

//ajax handler
require_once('ajax_engine_inc.php');
?>