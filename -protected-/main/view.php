<?php
//view file
TEMP::component('top_menu', array('home'=>'Store manager', 
											'menu'=>array(
													'link'=>array('view_mnitem'=>'Перегляд',
																	'edit_mnitem'=>'Редагування'),
													'dropdown'=>array(),

													
												),
											'search'=>array('enable'=>false)
									));
?>