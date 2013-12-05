<?ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start(0);
date_default_timezone_set('Europe/Kiev');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/main_lib_inc.php');

//print_r($db->messages);

$temp = new TEMP();

ob_clean();
?>
