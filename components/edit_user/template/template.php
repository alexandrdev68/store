<div class="modal hide fade _editUserModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?=TEMP::$Lang['edit_user_btn']?></h3>
    <div class="alert _eduserAlert alert-error alert-block fade in">
		<button class="close"  type="button">×</button>
		<strong><?=TEMP::$Lang['warning']?>!</strong>
		<span class="_messtext"></span>
	</div>
  </div>
  <div class="modal-body">
    <div class="_edUserFoto user_foto">
      <img src="" alt="no foto" width="380" height="285" class="img-polaroid">
    </div>
    <form class="form-inline _edUserForm">
	    <div class="control-group">
		    <div class="controls">
		    	<input required name="uLogin" type="text" id="editLogin" placeholder="Login">
		    </div>
	    </div>
	    <div class="control-group">
		    <div class="controls">
		    	<input name="uFirstname" type="text" id="editName" placeholder="<?=TEMP::$Lang['input_firstName']?>">
		    	<input name="uLastname" type="text" id="editFirst" placeholder="<?=TEMP::$Lang['input_patronymic']?>">
		    	<input name="uPatronymic" type="text" id="editPatronymic" placeholder="<?=TEMP::$Lang['input_lastName']?>">
		    	<input name="uPhone" type="text" placeholder="<?=TEMP::$Lang['input_phone']?>">
		    	<input name="uLivePlace" type="text" placeholder="<?=TEMP::$Lang['input_live_place']?>">
		    	<input type="hidden" name="uId" value="">
		    </div>
	    </div>
	    <div class="control-group">
		    
		    <div class="controls">
		    	<label class="control-label" for="editLevel"><?=TEMP::$Lang['input_level']?>:
		    	<select required name="uLevel" id="editLevel">
					<option value="552071">admin</option>
					<option value="1">reception</option>
					<option value="2">user</option>
					<option selected value="4">klient</option>
				</select>
				</label>
				<label class="control-label" for="editselectStore"><?=TEMP::$Lang['select_store']?>:
				<select name="resStore" id="editselectStore" disabled>
					<option></option>
					<?foreach($arRes as $store):?><option value="<?=$store['id']?>"><?=$store['adress']?></option><?endforeach?>
				</select>
				</label>
		    </div>
		    <div class="controls">
		    	<label class="control-label" for="editBlackList"><span class="text-warning"><?=TEMP::$Lang['add_black_list']?>:</span>
					<input id="editBlackList" type="checkbox" name="blackList">
				</label>
		    </div>
	    </div>
	    <div class="control-group">
	    	<div class="controls">
				<label class="control-label" for="edit_user_foto"><?=TEMP::$Lang['load_photo']?></label>
				<input id="edit_user_foto" name="foto" type="file" value="">
			</div>
	    </div>
	    <div class="control-group">
		    <div class="controls text-center">
			    <button type="submit" class="btn btn-primary btn-large"><?=TEMP::$Lang['accept_btn']?></button>
		    </div>
	    </div>
    </form>
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" class="btn"><?=TEMP::$Lang['exit_btn']?></a>
  </div>
</div>