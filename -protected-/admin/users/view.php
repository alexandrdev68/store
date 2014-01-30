<?
$arParams = array('action'=>'',
	'name'=>'testForm',
	'class'=>'inline',
	'id'=>'',
	'method'=>'POST',
	'type'=>'inline',
	'fields'=>array(
		'text'=>array(
			'label'=>'test text field',
			'value'=>'',
			'name'=>'uEmail',
			'validate'=>'email'
		),
		'radio'=>array(
			'label'=>'test label field',
			'name'=>'uRadio',
			'value'=>'yes',
		),
		'radio'=>array(
			'label'=>'test label1 field',
			'name'=>'uRadio',
			'value'=>'no',
		),
		'checkbox'=>array(
			'label'=>'test checkbox',
			'name'=>'uCheckbox',
			'value'=>'y',
		),
		'textarea'=>array(
			'cols'=>'50',
			'rows'=>'5',
			'label'=>'test textarea label',
			'validate'=>'',
			'value'=>'kokokokokoo',
			'name'=>'uTextarea'
		),
		'select'=>array(
			'label'=>'test select label',
			'name'=>'uSelect',
			'multiple'=>'0',
			'option'=>array(
				0=>array('value'=>'1', 'text'=>'one'),
				1=>array('value'=>'2', 'text'=>'two'),
			),
		),
		'hidden'=>array(
			'name'=>'uHidden',
			'value'=>'test',
		),
	),
	'buttons'=>array(
		'submit'=>array(
			'class'=>'btn btn-default',
			'name'=>'uButton',
			'id'=>'',
			'value'=>'ok'
		),
		'button'=>array(
			'class'=>'btn btn-default',
			'id'=>'',
			'value'=>'cancel'
		),
	),
);
TEMP::component('anyForm', $arParams)?>