<?php
$db = new Dbase();
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/php_interface/local_init.php')) include($_SERVER['DOCUMENT_ROOT'].'/php_interface/local_init.php');
else{
	$db->user = 'u980257691_store';
	$db->host = 'mysql.hostinger.com.ua';
	$db->passw = 'kupidon10';
	$db->base = 'u980257691_store';
	$db->m_connect();
}
?>