<?php
//controller file
namespace admin\users;

class Actions extends \Actions{
	function anyFormAction_add_user(){
		return json_encode(array('status'=>0));
	}
}

?>