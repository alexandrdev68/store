<?class TEMP {
	/**
	 * название текущего шаблона (совпадает с названием папки в которой хранится шаблон)
	 * @var string
	 */
	static $current = 'store_grids';
    static $header_path;
    static $title = 'store';
    static $index_path;
    static $footer_path;
    static $styles_dir;
    static $js_dir;
    static $curr_temp_path;
    static $curr_lang;
    static $used_forms = true;
    static $used_calendar = true;
    static $used_grid = true;
    static $Lang = array();
	
    function __construct(){
		
        
    }
    
    static public function init($temp_name){
    	self::$curr_temp_path = 'templates/'.$temp_name;
    	self::$header_path = self::$curr_temp_path.'/header.php';
        self::$index_path = self::$curr_temp_path.'/index.php';
        self::$footer_path = self::$curr_temp_path.'/footer.php';
        self::$styles_dir = self::$curr_temp_path."/css";
        self::$js_dir = self::$curr_temp_path.'/js';
        self::$curr_lang = 'ua';
    }
    
    /**
	 * вставляет компонент с именем, совпадающим с названием папки в каталоге components
	 * в качестве входного параметра можно передававать массив
	 * Если в качестве 3-го параметра передать false будет грузится только component.php
	 * Пример: TEMP::component('myComponent', array('name'=>'Jon'));
	 * @var function
	 */
    static public function component($name, $arPar, $template = true){
            include($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/component.php');
            if($template){
            	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/template/template.js.php')) include($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/template/template.js.php');
            	include($_SERVER['DOCUMENT_ROOT'].'/components/'.$name.'/template/template.php');
            }     
    }
    
	/**
	 * Валидирует значение $value по переданому типу $type, возвращает true если соответствует типу
	 * Пример: TEMP::validate('i@ua', 'email');
	 * @var function
	 */
    static public function validate($value, $type){
	  switch($type){
	    case 'email':
	      return preg_match('/^[a-zA-Z0-9][-._a-zA-Z0-9]+@(?:[-a-zA-Z0-9]+\.)+[a-zA-Z]{2,6}$/', $value);
	      break;
	    case 'card':
	      return preg_match('/^[0-9]{14,19}$/', $value);
	      break;
	    case 'cvv':
	      return preg_match('/^[0-9]{3,3}$/', $value);
	      break;
	    case 'owner':
	      return preg_match('/^[A-Za-z\-\ ]{3,}$/', $value);
	      break;
	    case 'expiry':
	      return preg_match('/^[0-9]{4,4}$/', $value);
	      break;
	    case 'wallet_id':
	      return preg_match('/^[0-9]{14,14}$/', $value);
	      break;
	    case 'phone':
	      return preg_match('/^[0-9]{12,13}$/', $value);
	      break;
	    case 'summ':
	    	return preg_match('/^([0-9]{1,})$|^[0-9]{1,}\\.(?:[0-9]{1,2})$/', $value);
	    	break;
	  }
	}
    
    
}?>