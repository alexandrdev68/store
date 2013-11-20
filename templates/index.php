<?TEMP::component('localization', array('language'=>isset($_SESSION['user_lang']) ? $_SESSION['user_lang'] : 'ua'), false)?>
<?require_once ($_SERVER['DOCUMENT_ROOT'].'/'.TEMP::$header_path)?>
<?require_once ($_SERVER['DOCUMENT_ROOT'].'/'.TEMP::$index_path)?>
<?require_once ($_SERVER['DOCUMENT_ROOT'].'/'.TEMP::$footer_path)?>