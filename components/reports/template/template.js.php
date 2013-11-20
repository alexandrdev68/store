<script>
$(document).ready(reports_init);

function reportData(data, checked){
	checked = checked || '';
	if(reportData.num === undefined) reportData.num = 1;
	reportData.getNum = function(){
		return reportData.num++;
	};
	this.html = '<tr class="_rInfo"><td>' + reportData.getNum() + '</td>' +
	'<td>' + data.model + '</td>' +
	'<td>' + data.id + '</td>' +
	'<td>' + data.serial_id + '</td>' +
	'<td>' + bike.getTimeString(new Date((data.time_end - data.time_start) * 1000), ':') + '</td>' +
	'<td>' + bike.getTimeString(new Date(data.project_time * 1000), ':') + '</td>' +
	'<td class="_rentAmount" data-rent_amount="' + (parseFloat(data.amount) / 100) + '">' + 
	bike.numberFormat(parseFloat(data.amount) / 100) + '</td>' +
	'<?if(USER::isAdmin()):?><td class="_calcFlag"><input data-rent_id="' + data.rent_id + '" type="checkbox" ' + checked + '></td><?endif?></tr>';
}


function dayReport(){
	bike.dayReport($('li._storeReportSelect a').data('store_id'), function(response){
		$('table._reportList tr._rInfo').detach();
		for(var rep in response.rents){
			var rpRow = new reportData(response.rents[rep]);
			$('div._reportsView table._reportList').append(rpRow.html);
		};
		$('div._reportsView table._reportList').append('<tr class="_rInfo"><th><?=TEMP::$Lang['summ_text']?></th><th></th><th></th><th></th><th></th><th></th><th>' + bike.numberFormat(calc_report_summ()) + '</th><?if(USER::isAdmin()):?><th></th><?endif?></tr>')
		reportData.num = 1;
		$('input._mainReportChckBox').attr('checked', false);
	});
}

function calc_report_summ(){
	var table = $('table._reportList td._rentAmount');
	var summ = 0;
	for(var i = 0; i < table.length; i++){
		summ += parseFloat($(table[i]).data('rent_amount'));
	}
	return summ;
}

function get_canceled_rents(){
	var canceled = $('td._calcFlag input');
	var table = [];
	var c = 0;
	for(var i = 0; i < canceled.length; i++){
		if($(canceled[i]).prop('checked') !== false){
			table[c] = $(canceled[i]).data('rent_id');
			c++;
		}
	}
	return table.length == 0 ? false : table;
}

function periodReport(){
	var store_id = $('li._storeReportSelect a').data('store_id');
	bike.periodReport(date_from.valueOf() / 1000, date_to.valueOf() / 1000, store_id, function(response){
		$('table._reportList tr._rInfo').detach();
		for(var rep in response.rents){
			var rpRow = new reportData(response.rents[rep]);
			$('div._reportsView table._reportList').append(rpRow.html);
		};
		$('div._reportsView table._reportList').append('<tr class="_rInfo"><th><?=TEMP::$Lang['summ_text']?></th><th></th><th></th><th></th><th></th><th></th><th>' + bike.numberFormat(calc_report_summ()) + '</th><?if(USER::isAdmin()):?><th></th><?endif?></tr>')
		reportData.num = 1;
		$('input._mainReportChckBox').attr('checked', false);
	});
}

var date_now = new Date();
var date_from = new Date(date_now.getFullYear(), date_now.getMonth(), date_now.getDate());
var date_to = new Date(date_now.getFullYear(), date_now.getMonth(), date_now.getDate());

function reports_init(){
	$('ul._reportChange a').click(function(event){
		event.preventDefault();
		$('button._reportType').data('type', $(this).attr('href')).find('span._reportText').text($(this).data('report-text'));

		var select_type = $(this).attr('href');
		switch (select_type){
			case '#_dayReport':
				$('._reportFromPeriod').hide();
				$('table._reportList tr._rInfo').detach();
				break;
			case '#_periodReport':
				$('._reportFromPeriod').show();
				$('table._reportList tr._rInfo').detach();
				break;
		}
		
		$('div.btn-group.open').removeClass('open');
	});

	if(bike.storeId === null){
		$('li._storeReportSelect').show();
	}else $('li._storeReportSelect a').data('store_id', bike.storeId);

	$('li._storeReportSelect ul a').click(function(event){
		event.preventDefault();
		$('a span._storeReportText').text($(this).text()).parent().data('store_id', $(this).data('value'));

	});

	$('._reportdateFrom').datepicker('setValue', date_from);
	$('._reportdateTo').datepicker('setValue', date_to);
	var checkin = $('._reportdateFrom').datepicker({
		onRender: function(date){
			return date.valueOf() > date_to.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev){
		if (ev.date.valueOf() > checkout.date.valueOf() || ev.date.valueOf() >= date_now.valueOf()){
			checkin.setValue(date_from);
		}else{
			date_from = new Date(ev.date);
		}
		checkin.hide();
	}).data('datepicker');
	
	var checkout = $('._reportdateTo').datepicker({
		onRender: function(date){
			return date.valueOf() > date_now.valueOf() || date.valueOf() < date_from.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev){
		if (ev.date.valueOf() < checkin.date.valueOf() || ev.date.valueOf() >= date_now.valueOf()){
			checkout.setValue(date_to);
		}else{
			date_to = new Date(ev.date);
		}
		checkout.hide();
	}).data('datepicker');
	
	$('._reportFromPeriod').hide();
	
	$('button._reportType').click(function(){
		var action_type = $(this).data('type');
		switch (action_type){
			case '#_dayReport':
				dayReport();
				break;
			case '#_periodReport':
				periodReport();
				break;
		}
	});

	$('input._mainReportChckBox').click(function(){
		$('td._calcFlag input').prop('checked', $(this).prop('checked'));
	});

	$('li button._cancelRents').click(function(){
		var table = get_canceled_rents();
		if(table === false) return false;
		bike.cancelRents(table, function(response){
			dayReport();
		});
	});

}
</script>