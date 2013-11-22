<div class="row-fluid">
		<div class="span3 offset5 text-center"><h4>Store manager</h4></div>
</div>
<div class="row-fluid">
	<div class="span12 text-center">
		<div class="span3 offset5 text-center">
			<div class="alert _loginAlert alert-error alert-block fade in">
				<button class="close"  type="button">×</button>
				<strong><?=TEMP::$Lang['warning']?>!</strong>
				<span class="_messtext"></span>
			</div>
			<form class="_loginForm">
			    <div class="control-group">
				    <div class="controls">
				    	<input required name="uLogin" type="text" id="inputEmail" placeholder="Login">
				    </div>
			    </div>
			    <div class="control-group">
				    <div class="controls">
				    	<input required name="uPassw" type="password" id="inputPassword" placeholder="<?=TEMP::$Lang['password']?>">
				    </div>
			    </div>
			    <div class="control-group">
				    <div class="controls">
					    <button type="submit" class="btn btn-primary btn-large _loginBtn"><?=TEMP::$Lang['login']?></button>
				    </div>
			    </div>
		    </form>
	    </div>
    </div>
</div>
