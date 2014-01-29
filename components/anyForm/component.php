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
			'validate'=>'email'
		),
		'radio'=>array(
			'label'=>'',
			'value'=>'',
		),
		'checkbox'=>array(
			'label'=>'',
			'value'=>'',
		),
		'textarea'=>array(
			'width'=>'',
			'height'=>'',
			'label'=>'',
			'validate'=>''
		),
		'hidden'=>array(
			'label'=>'',
			'value'=>'',
		),
	),
	'buttons'=>array(
		'submit'=>array(
			'class'=>'',
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
}elseif(!isset($arParam['id'])){
	$arParam['id'] = 'frm_id_gen_'.rand(1, 9999999);
}elseif(!isset($arParam['name'])){
	$arParam['name'] = 'frm_name_gen_'.rand(1, 9999999);
}elseif(!isset($arParam['type'])){
	$arParam['type'] = 'inline';
}elseif(!isset($arParam['action'])){
	$arParam['action'] = '';
}elseif(!isset($arParam['fields'])){
	$arParam['fields'] = array();
}elseif(!isset($arParam['buttons'])){
	$arParam['buttons'] = array('submit'=>array('value'=>'OK'));
}
?>


