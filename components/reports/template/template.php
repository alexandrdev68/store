<style type="text/css">
	.row-fluid .myspan2 {width:100%}
	.input-append, .input-prepend {margin:5px 0}
	.input-append {margin-right:20px;}
	.date_text {padding:10px 15px; display:inline-block}
</style>
<div class="row-fluid _reportsView" style="min-height:200px;">
	<div class="navbar">
		<div class="navbar-inner">
			<ul class="nav">
				<li class="">
					<div class="btn-group">
						<button data-type="#_dayReport" class="btn btn-primary _reportType"><span class="_reportText"><?=TEMP::$Lang['report_from_day']?></span> <i class="icon-refresh icon-white"></i></button>
						<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							<span class="caret white" style="border-bottom-color: #FFFFFF; border-top-color: #FFFFFF;"></span>
						</button>
						<ul class="dropdown-menu _reportChange">
							<li><a data-report-text="<?=TEMP::$Lang['report_from_day']?>" href="#_dayReport"><?=TEMP::$Lang['report_from_day']?></a></li>
							<li><a data-report-text="<?=TEMP::$Lang['report_from_period']?>" href="#_periodReport"><?=TEMP::$Lang['report_from_period']?></a></li>
						</ul>
					</div>
				</li>
				<ul class="nav _reportFromPeriod">
					<li class="divider-vertical"></li>
					<li><span class="date_text"><?=TEMP::$Lang['text_from']?></span></li>
					<li>
						<div class="input-append date _reportdateFrom" data-date="" data-date-format="dd-mm-yyyy" data-date-weekStart="1">
						  <input class="span2 myspan2" size="5" type="text" value="">
						  <span class="add-on"><i class="icon-th"></i></span>
						</div>
					</li>
					<li><span class="date_text"><?=TEMP::$Lang['text_to']?></span></li>
					<li>
						<div class="input-append date _reportdateTo" data-date="" data-date-format="dd-mm-yyyy" data-date-weekStart="1">
						  <input class="span2 myspan2" size="5" type="text" value="">
						  <span class="add-on"><i class="icon-th"></i></span>
						</div>
					</li>
				</ul>
				<li class="divider-vertical"></li>
				<li class="dropdown _storeReportSelect disabled">
					<a class="dropdown-toggle" data-toggle="dropdown" data-store_id="no" href="#"><span class="_storeReportText"><?=TEMP::$Lang['store_address']?></span> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?foreach($_SESSION['STORES'] as $value):?><li><a href="#" data-value="<?=$value['id']?>"><?=$value['adress']?></a></li><?endforeach?>
						</ul>
				</li>
				
			</ul>
			<?if(USER::isAdmin()):?><ul class="nav pull-right"><li class="divider-vertical"></li><li><button class="btn btn-danger _cancelRents"><?=TEMP::$Lang['clear_rent_btn']?></button></li></ul><?endif?>
		</div>
	</div>
	<table class="table table-striped _reportList">
		<tr>
			<th><?=TEMP::$Lang['pos_number']?></th>
			<th><?=TEMP::$Lang['model_col']?></th>
			<th><?=TEMP::$Lang['bike_number']?></th>
			<th><?=TEMP::$Lang['serial_id']?></th>
			<th><?=TEMP::$Lang['time_on_rent']?></th>
			<th><?=TEMP::$Lang['payment_time']?></th>
			<th><?=TEMP::$Lang['real_amount']?></th>
			<?if(USER::isAdmin()):?><th><input type="checkbox" class="_mainReportChckBox"></th><?endif?>
		</tr>
	</table>
</div>