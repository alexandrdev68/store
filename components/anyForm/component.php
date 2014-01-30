<?php
/*
 * $arParam = array(
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
if(!isset($arParam['class'])){
	$arParam['class'] = '';
}
if(!isset($arParam['id'])){
	$arParam['id'] = 'frm_id_gen_'.rand(1, 9999999);
}
if(!isset($arParam['name'])){
	$arParam['name'] = 'frm_name_gen_'.rand(1, 9999999);
}
if(!isset($arParam['type'])){
	$arParam['type'] = 'inline';
}
if(!isset($arParam['action'])){
	$arParam['action'] = '';
}
if(!isset($arParam['fields'])){
	$arParam['fields'] = array();
}
if(!isset($arParam['buttons'])){
	$arParam['buttons'] = array('submit'=>array('value'=>'OK'));
}

$arFieldType = array(
	'text'=>'form-group',
	'checkbox'=>'checkbox',
	'textarea'=>'form-group',
	'radio'=>'form-group',
	'hidden'=>'form-group',
);
?>


