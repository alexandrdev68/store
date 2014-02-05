<?
$arParams = array(
	'action'=>'add_user',
	'name'=>'testForm',
	'class'=>'form-inline',
	'id'=>'add_user_form',
	'method'=>'POST',
	'type'=>'inline',
	'fields'=>array(
		0=>array(
			'type'=>'email',
			'label'=>'test text field',
			'value'=>'',
			'name'=>'uEmail',
			'validate'=>'email'
		),
		3=>'divider',
		5=>array(
			'type'=>'file',
			'label'=>'test file field',
			'value'=>'',
			'name'=>'uFile',
		),
		10=>array(
			'type'=>'radio',
			'label'=>'test label field',
			'name'=>'uRadio',
			'value'=>'yes',
		),
		20=>array(
			'label'=>'test label1 field',
			'name'=>'uRadio',
			'value'=>'no',
			'type'=>'radio',
		),
		30=>array(
			'label'=>'test checkbox',
			'name'=>'uCheckbox',
			'value'=>'y',
			'type'=>'checkbox',
		),
		45=>'divider',
		40=>array(
			'cols'=>'50',
			'rows'=>'5',
			'label'=>'test textarea label',
			'value'=>'kokokokokoo',
			'name'=>'uTextarea',
			'type'=>'textarea',
		),
		50=>array(
			'type'=>'select',
			'label'=>'test select label',
			'name'=>'uSelect',
			'multiple'=>'0',
			'option'=>array(
				0=>array('value'=>'1', 'text'=>'one'),
				1=>array('value'=>'2', 'text'=>'two'),
			),
		),
		60=>array(
			'name'=>'uHidden',
			'value'=>'test',
			'type'=>'hidden',
		),
	),
	'buttons'=>array(
		0=>array(
			'class'=>'btn btn-primary"',
			'name'=>'uButton',
			'id'=>'',
			'value'=>'ok',
			'type'=>'button',
			'role'=>'submit'
		),
		1=>array(
			'class'=>'btn btn-default',
			'id'=>'',
			'value'=>'cancel',
			'type'=>'button',
		),
	),
);
TEMP::component('anyForm', $arParams)?>