<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
		     
			<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			     
			<!-- Be sure to leave the brand out there if you want it shown -->
			<a class="brand" href="#"><?=TEMP::$Lang['main_brand']?></a>
			
			    
			<!-- Everything you want hidden at 940px or less, place within here -->
			<div class="nav-collapse collapse _mnBar">
				<!-- .nav, .navbar-search, .navbar-form, etc -->
				<ul class="nav">
					<li class="divider-vertical"></li>
				</ul>
				<ul class="nav _topPanel">
					<li class="active"><a href="#view_mn"><?=TEMP::$Lang['view_mn']?></a></li>
					<?if(USER::isAdmin()):?><li><a href="#operations_mn"><?=TEMP::$Lang['operations_mn']?></a></li><?endif?>
				</ul>
				<ul class="nav">
					<li class="divider-vertical"></li>
				</ul>
				<ul class="nav">
					<li class="active">
						<form class="navbar-search pull-left _searchForm" method="post">
							<input type="text" class="search-query _searchTop" name="#_bikesAllPage" placeholder="Search" value="">
							<input type="hidden" name="action" value="search_main">
						</form>
					</li>
				</ul>
				<ul class="nav pull-right">
					<li class="divider-vertical"></li>
					<li><a href="#exit_btn"><?=TEMP::$Lang['exit_btn']?></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="alert _mainWinAlert alert-error alert-block fade in disabled">
	<button class="close"  type="button">×</button>
	<strong><?=TEMP::$Lang['warning']?>!</strong>
	<span class="_messtext"></span>
</div>
<div class="tabbable tabs-left" id="view_mn">
  <ul class="nav nav-tabs">
    <?if(USER::isAdmin()):?><li><a href="#_usListPage" data-toggle="tab"><?=TEMP::$Lang['users_list']?></a></li><?endif?>
    <li class="active"><a href="#_bikesAllPage" data-toggle="tab"><?=TEMP::$Lang['bikes_in_store']?></a></li>
    <li><a href="#_bikesRent" data-toggle="tab"><?=TEMP::$Lang['bikes_in_rent']?></a></li>
    <?if($_SESSION['CURRUSER']['user_level'] != '2'):?><li><a href="#_reportView" data-toggle="tab"><?=TEMP::$Lang['reports_view']?></a></li><?endif?>
  </ul>
  <div class="tab-content">
	<?if(USER::isAdmin()):?>
	<div class="tab-pane" id="_usListPage">
		<div class="navbar _usersPaging">
			<div class="navbar-inner text-center">
				<div class="pagination _usersNavChain" style="margin:5px 0 0;">
				  <ul>
				    <li><a href="#">Prev</a></li>
				    <li class="disabled"><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
				    <li><a href="#">4</a></li>
				    <li><a href="#">5</a></li>
				    <li><a href="#">Next</a></li>
				  </ul>
				</div>
			</div>
		</div>
		<div class="usListContainer _usContnr">
			<table class="table table-striped _usListTable">
				<tr>
					<th>№</th>
					<th>Login</th>
					<th><?=TEMP::$Lang['pib_table']?></th>
					<th><?=TEMP::$Lang['input_phone']?></th>
					<th><?=TEMP::$Lang['input_level']?></th>
					<th></th>
				</tr>
			</table>
		</div>
	</div>
	<?endif?>
	<div class="tab-pane active" id="_bikesAllPage">
		<table class="table table-striped _bkListTable">
			<tr>
				<th><?=TEMP::$Lang['pos_number']?></th>
				<th><?=TEMP::$Lang['model_col']?></th>
				<th class="hidden-phone"><?=TEMP::$Lang['store_adress']?></th>
				<th><?=TEMP::$Lang['bike_number']?></th>
				<th><?=TEMP::$Lang['serial_id']?></th>
				<th class="hidden-phone"><?=TEMP::$Lang['foto']?></th>
				<th></th>
				<th></th>
			</tr>
		</table>
	</div>
	<div class="tab-pane" id="_bikesRent">
		<table class="table table-striped _bkListTableRent">
			<tr>
				<th><?=TEMP::$Lang['pos_number']?></th>
				<th><?=TEMP::$Lang['model_col']?></th>
				<th><?=TEMP::$Lang['time_on_rent']?></th>
				<th><?=TEMP::$Lang['payment_time']?></th>
				<th><?=TEMP::$Lang['bike_number']?></th>
				<th><?=TEMP::$Lang['serial_id']?></th>
				<th></th>
			</tr>
		</table>
	</div>
	<?if($_SESSION['CURRUSER']['user_level'] != '2'):?>
	<div class="tab-pane" id="_reportView">
		<?=TEMP::component('reports', array())?>
	</div>
	<?endif?>
  </div>
</div>

<?=TEMP::component('modal_win', array())?>
<?=TEMP::component('pay_rent', array())?>
<?=TEMP::component('user_info', array())?>
<?=TEMP::component('final_info_win', array())?>

<?if(USER::isAdmin()):?>
<?=TEMP::component('edit_bike', array())?>
<?=TEMP::component('edit_user', array())?>
<div class="container-fluid disabled" id="operations_mn">
	<div class="row-fluid">
		<div class="span2">
			
			<div class="btn-group btn-block btn-group-vertical">
			  <button class="btn btn-block _regPage"><?=TEMP::$Lang['registration']?></button>
			  <button class="btn btn-block _addBike"><?=TEMP::$Lang['add_bike_btn']?></button>
			  <button class="btn btn-block _storesManage"><?=TEMP::$Lang['stores_manage']?></button>
			</div>
		</div>
		<div class="span10 _viewPort">
			<span><?TEMP::component('register', array())?></span>
			<span><?TEMP::component('bike_add', array())?></span>
			<span><?TEMP::component('store_manage', array())?></span>
		</div>
	</div>
</div>
<?endif?>