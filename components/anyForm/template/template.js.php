<script>
$(document).ready(anyform_init_<?=$arPar['id']?>);

function anyform_init_<?=$arPar['id']?>(){
	$('#<?=$arPar['id']?>').submit(function(event){
		event.preventDefault();

		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'anyForm_action' : '<?=(!empty($arPar['action']) ? $arPar['action'] : 'default')?>', 'anyForm_id' : '<?=$arPar['id']?>'},
			url: window.location,
			success: function(response) {
				
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}	
</script>