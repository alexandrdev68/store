<?php
/*
 * $arPar = array(
	'action'=>'',
	'name'=>'',
	'class'=>'',
	'id'=>'',
	'type'=>'inline',
	'fields'=>array(
		'text'=>array(
			'label'=>'',
			'value'=>'',
			'name'=>'',
			'validate'=>'email'
		),
		'radio'=>array(
			'label'=>'',
			'name'=>'',
			'value'=>'',
		),
		'checkbox'=>array(
			'label'=>'',
			'name'=>'',
			'value'=>'',
		),
		'textarea'=>array(
			'cols'=>'',
			'rows'=>'',
			'label'=>'',
			'validate'=>'',
			'value'=>'',
			'name'=>''
		),
		'select'=>array(
			'label'=>'',
			'name'=>'',
			'multiple'=>'1',
			'option'=>array(
				0=>array('value'=>'', 'text'=>''),
				1=>array(),
			),
		),
		'hidden'=>array(
			'label'=>'',
			'name'=>'',
			'value'=>'',
		),
	),
	'buttons'=>array(
		'submit'=>array(
			'class'=>'',
			'name'=>'',
			'id'=>'',
			'value'=>''
		),
		'button'=>array(
			'class'=>'',
			'id'=>'',
			'value'=>''
		),
	),
);
 * 
 * */
if(!isset($arPar['class'])){
	$arPar['class'] = '';
}
if(!isset($arPar['id'])){
	$arPar['id'] = 'frm_id_gen_'.rand(1, 9999999);
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
	$arPar['buttons'] = array('submit'=>array('value'=>'OK'));
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
	'select'=>'form-control',
);
?>


