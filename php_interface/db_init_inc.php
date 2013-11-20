<?php
$db = new Dbase();
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/php_interface/local_init.php')) include($_SERVER['DOCUMENT_ROOT'].'/php_interface/local_init.php');
else{
	$db->user = '190763';
	$db->passw = 'gthtdjl';
	$db->base = '190763';
	$db->m_connect();
}
?>