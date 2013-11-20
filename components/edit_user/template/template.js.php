<script>
$(document).ready(eduser_init);

function eduser_init(){
	$('div._eduserAlert').hide();
	$('div._eduserAlert button.close').click(function(){
		$('div._eduserAlert').slideUp('fast');
	});

	$('div._editUserModal').on('show', function(){
		user.getById(user.currId, function(response){
			$('div._edUserFoto').html('<img alt="no foto" src="' + response['info'].photo + '" width="380" height="285" class="img-polaroid">');
			$('#editLogin').val(response.info.login);
			$('#editName').val(response.info.name);
			$('#editFirst').val(response.info.surname);
			$('#editPatronymic').val(response.info.patronymic);
			$('form._edUserForm input[name="uPhone"]').val(response.info.phone);
			$('form._edUserForm input[name="uId"]').val(user.currId);
			$('form._edUserForm input[name="uLivePlace"]').val(response['info'].properties === null ? '' : response['info'].properties.live_place === undefined ? '' : response['info'].properties.live_place);
			$('form._edUserForm select[name="uLevel"]').val(response.info.user_level);
			$('form._edUserForm select[name="resStore"]').val(response['info'].properties === null ? '' : response['info'].properties.store === undefined ? '' : response['info'].properties.store);
			$('#editBlackList').prop('checked', response['info'].properties === null ? false : response['info'].properties.blackList == 'on' ? true : false);
			if(response.info.user_level == 4) $('form._edUserForm select[name="resStore"]').attr('disabled', true);
			else $('form._edUserForm select[name="resStore"]').attr('disabled', false);
		});
	})
	
	$('div._editUserModal').on('hide', function(){
		$('div._edUserFoto img').attr('src', '');
	});
	
	$('form._edUserForm').submit(function(event){
		event.preventDefault();
		$(this).ajaxSubmit({
			type: 'post',
			dataType : 'json',
			data : {'action' : 'edit_user'},
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					$('div._eduserAlert span._messtext').text(response.message);
					$('div._eduserAlert strong').text("<?=TEMP::$Lang['congratulation']?>!");
					$('form._edUserForm').clearForm();
					$('#edit_user_foto').val('');
					$('div._eduserAlert').slideDown('fast').removeClass('alert-error').delay('3000').slideUp();
					$('div._editUserModal').delay('3000').modal('hide');
					//if(response.uploaded_photo == 'yes') users_fill(user.navChain.length > 0 ? user.navChain.curr : 0);//window.location.reload();
					users_fill(user.navChain.current !== undefined ? (user.navChain.current - 1) * 100 : 0);
				}else if(response.status == 'error'){
					$('div._eduserAlert strong').text("<?=TEMP::$Lang['warning']?>!");
					$('div._eduserAlert span._messtext').text(response.message);
					$('div._eduserAlert').addClass('alert-error').slideDown('fast');
				}else if(response.status == 'session_close'){
	            	bike.sessionStopped();
	            }
			},
			error : function(response){
				console.log('bad');
			}
		});
	});
}
</script>