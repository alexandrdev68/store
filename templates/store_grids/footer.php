<?if(TEMP::$used_forms):?><script src="<?=TEMP::$js_dir?>/jquery.form.min.js"></script><?endif?>
<script src="<?=TEMP::$curr_temp_path?>/bootstrap/js/bootstrap.min.js"></script>
<?if(TEMP::$used_grid):?>
	<script src="<?=TEMP::$curr_temp_path?>/w2ui-1.3.1/w2ui-1.3.1.min.js"></script>
	<link rel="stylesheet" href="<?=TEMP::$curr_temp_path?>/w2ui-1.3.1/w2ui-1.3.1.min.css">
<?endif?>
<?if(TEMP::$used_calendar):?><script src="<?=TEMP::$curr_temp_path?>/datepicker/js/bootstrap-datepicker.js"></script><?endif?>
<?if(TEMP::$used_calendar):?><link rel="stylesheet" href="<?=TEMP::$curr_temp_path?>/datepicker/css/datepicker.css"><?endif?>
</section>
</body>
</html>