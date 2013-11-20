<script>
$(document).ready(register_init);

function register_init(){
	$('div._registerAlert').hide();
	$('div._registerAlert button.close').click(function(){
		$('div._registerAlert').slideUp('fast');
	});
	$('form._registerForm').submit(function(event){
		event.preventDefault();

		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'registr_action'},
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					$('span._messtext').text(response.message);
					$('div._registerAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
					$('form._registerForm').clearForm();
					$('div._registerAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp();
					$('div._registerForm').delay('3000').fadeOut('fast');
				}else if(response.status == 'error'){
					$('div._registerAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('span._messtext').text(response.message);
					$('div._registerAlert').addClass('alert-error').slideDown('fast');
				}
				
			},
			error : function(response){
				console.log('bad');
			}
		});
	});

	$('#createLevel').change(function(){
		if($(this).val() == '4'){
			$('#createPassword').attr('disabled', true);
			$('#confirmPassword').attr('disabled', true);
			$('#selectStore').attr('disabled', true);
			$('#createLogin').attr('disabled', true);
			$('#createPhone').attr('required', true);
		}else if($(this).val() == '1'){
			$('#createPassword').attr('disabled', false);
			$('#confirmPassword').attr('disabled', false);
			$('#createLogin').attr('disabled', false);
			$('#selectStore').attr('disabled', false);
			$('#createPhone').attr('required', false);
		}else{
			$('#createPassword').attr('disabled', false);
			$('#confirmPassword').attr('disabled', false);
			$('#createLogin').attr('disabled', false);
			$('#selectStore').attr('disabled', true);
			$('#createPhone').attr('required', false);
		}
	});
}
</script>