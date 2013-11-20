<div class="row-fluid disabled _registerForm">
	<div class="span10 offset0">
		<h4 id="myModalLabel1"><?=TEMP::$Lang['please_register']?></h4>
		<div class="alert _registerAlert alert-error alert-block fade in">
			<button class="close"  type="button">×</button>
			<strong><?=TEMP::$Lang['warning']?>!</strong>
			<span class="_messtext"></span>
		</div>
		    <form class="form-horizontal _registerForm">
			    <div class="control-group">
				    <div class="controls">
				    	<input required disabled name="uLogin" type="text" id="createLogin" placeholder="Login">
				    	<input required disabled name="uPassword" type="password" id="createPassword" placeholder="<?=TEMP::$Lang['password']?>">
				    	<input required disabled name="uConfirmPassword" type="password" id="confirmPassword" placeholder="<?=TEMP::$Lang['password_confirm']?>">
				    	<input required name="uPhone" id="createPhone" type="text" placeholder="<?=TEMP::$Lang['input_phone']?>">
				    </div>
			    </div>
			    <div class="control-group">
				    <div class="controls">
				    	<input name="uFirstname" type="text" id="createName" placeholder="<?=TEMP::$Lang['input_firstName']?>">
				    	<input name="uLastname" type="text" id="createFirst" placeholder="<?=TEMP::$Lang['input_patronymic']?>">
				    	<input name="uPatronymic" type="text" id="createPatronymic" placeholder="<?=TEMP::$Lang['input_lastName']?>">
				    </div>
			    </div>
			    <div class="control-group">
				    
				    <div class="controls">
				    	<label class="control-label" for="createLevel"><?=TEMP::$Lang['input_level']?>:
				    	<select required name="uLevel" id="createLevel">
							<option value="552071">admin</option>
							<option value="1">reception</option>
							<option value="2">user</option>
							<option selected value="4">klient</option>
						</select>
						</label>
						<label class="control-label offset1" for="selectStore"><?=TEMP::$Lang['select_store']?>:
						<select name="resStore" id="selectStore" disabled required>
							<option></option>
							<?foreach($arRes as $store):?><option value="<?=$store['id']?>"><?=$store['adress']?></option><?endforeach?>
						</select>
						</label>
				    </div>
			    </div>
			    <div class="control-group">
			    	<div class="controls">
						<label class="control-label" for="load_user_foto"><?=TEMP::$Lang['load_photo']?></label>
						<input required id="load_user_foto" name="foto" type="file" value="">
					</div>
			    </div>
			    <div class="control-group">
				    <div class="controls">
					    <button type="submit" class="btn"><?=TEMP::$Lang['registration']?></button>
				    </div>
			    </div>
		    </form>
	</div>
</div>