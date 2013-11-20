<?php
if(!isset($_SESSION['CURRUSER'])) header('Location: /');
$db = new Dbase();
$sql = "SELECT `id`, adress FROM `store`";
$arRes = $db->getArray($sql);
$_SESSION['STORES'] = $arRes;
?>