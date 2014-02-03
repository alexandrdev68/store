<?php
/*
 * $arParams = array('action'=>'',
	'name'=>'testForm',
	'class'=>'inline',
	'id'=>'',
	'method'=>'POST',
	'type'=>'inline',//form-horizontal form-inline
	'fields'=>array(
		0=>array(
			'type'=>'text',
			'label'=>'test text field',
			'value'=>'',
			'name'=>'uEmail',
			'validate'=>'email'
		),
		1=>array(
			'type'=>'radio',
			'label'=>'test label field',
			'name'=>'uRadio',
			'value'=>'yes',
		),
		2=>array(
			'label'=>'test label1 field',
			'name'=>'uRadio',
			'value'=>'no',
			'type'=>'radio',
		),
		3=>array(
			'label'=>'test checkbox',
			'name'=>'uCheckbox',
			'value'=>'y',
			'type'=>'checkbox',
		),
		4=>array(
			'cols'=>'50',
			'rows'=>'5',
			'label'=>'test textarea label',
			'validate'=>'',
			'value'=>'kokokokokoo',
			'name'=>'uTextarea'
			'type'=>'textarea',
		),
		5=>array(
			'type'=>'select',
			'label'=>'test select label',
			'name'=>'uSelect',
			'multiple'=>'0',
			'option'=>array(
				0=>array('value'=>'1', 'text'=>'one'),
				1=>array('value'=>'2', 'text'=>'two'),
			),
		),
		6=>array(
			'name'=>'uHidden',
			'value'=>'test',
			'type'=>'hidden',
		),
	),
	'buttons'=>array(
		0=>array(
			'class'=>'btn btn-default',
			'name'=>'uButton',
			'id'=>'',
			'value'=>'ok',
			'type'=>'submit',
		),
		1=>array(
			'class'=>'btn btn-default',
			'id'=>'',
			'value'=>'cancel',
			'type'=>'button',
		),
	),
);
 * 
 * */
if(!isset($arPar['class'])){
	$arPar['class'] = '';
}
if(empty($arPar['id'])){
	exit;
}
if(!isset($arPar['name'])){
	$arPar['name'] = 'frm_name_gen_'.rand(1, 9999999);
}
if(!isset($arPar['type'])){
	$arPar['type'] = 'inline';
}
if(!isset($arPar['action'])){
	$arPar['action'] = '';
}
if(!isset($arPar['fields'])){
	$arPar['fields'] = array();
}
if(!isset($arPar['buttons'])){
	$arPar['buttons'] = array('0'=>array('value'=>'OK', 'type'=>'submit'));
}
if(!isset($arPar['method'])){
	$arPar['method'] = 'POST';
}

$arFieldType = array(
	'text'=>'form-group',
	'checkbox'=>'checkbox',
	'textarea'=>'form-group',
	'radio'=>'radio',
	'hidden'=>'form-group',
	'select'=>'form-group',
	'email'=>'form-group',
	'password'=>'form-group',
	'file'=>'form-group',
);

if(!empty($_POST['anyForm_id']) && $_POST['anyForm_id'] == $arPar['id']){
	
	foreach($_POST as $name=>$value){
		$_POST[$name] = Dbase::dataFilter($_POST[$name]);
	}
	foreach($arPar['fields'] as $index=>$value){
		if(is_array($value)){
			if(isset($value['validate'])){
				
			}
		}
	}
	ob_clean();
	echo json_encode(array('status'=>'ok', 'id'=>$_POST['anyForm_id'], 'POST'=>$_POST));
	exit;
}
?>


