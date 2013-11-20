<?php
if(isset($arPar['language']))
	$langpath = $_SERVER['DOCUMENT_ROOT'].'/components/localization/template/lang/'.$arPar['language'].'/lang_'.$arPar['language'].'_inc.php';
else $langpath = $_SERVER['DOCUMENT_ROOT'].'/components/localization/template/lang/'.TEMP::$curr_lang.'/lang_'.TEMP::$curr_lang.'_inc.php';

require $langpath;
?>