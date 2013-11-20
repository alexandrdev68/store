<script>
$(document).ready(main_init);

function userData(data, full){
	full = full || 'yes';
	if(userData.num === undefined) userData.num = 1;
	userData.getNum = function(){
		return userData.num++;
	};
	this.html = '<tr class="_uInfo' + (data.properties === null ? '' : data.properties.blackList == 'on' ? ' error _blackList' + data.id : '') + '"><td>' + userData.getNum() + '</td>' +
	<?if(USER::isAdmin()):?>'<td>' + (data.properties === null ? '' : data.properties.blackList == 'on' ? '(<i class="icon-thumbs-down"></i>) ' : '') + 
	data.login + '</td>' + <?endif?>'<td>' + data.name + 
	(data.surname === undefined ? '' : ' ' + data.surname) + (data.patronymic === undefined ? '' : ' ' + data.patronymic) + '</td>' +
	(full == 'yes' ? '<td>' + data.phone + '</td><td class="_level">' + data.user_level + '</td><td><i class="icon-remove _delUsr" data-userId="' + data.id + '"></i> <i class="icon-pencil _edUsr" data-userId="' + data.id + '"></i></td></tr>' : '<td>'+
			data.phone + '</td>' + 
			'<td><input class="_print' + data.id + '" type="checkbox" value="yes"> <?=TEMP::$Lang["print_contract"]?>' +
			'<br><input class="_seat' + data.id + '" type="checkbox" value="yes"> <?=TEMP::$Lang["seat"]?>' +
			'</td><td><input class="span1 _timecnt' + data.id + '" type="text" value="1"></td><td><input data-userId="' + 
			data.id + '" type="radio" name="uRent"></td></tr>');
}

function updateTimeOnRent(){
	//console.log(bike.rentList.length);
	try{
		if(bike.rentList.length == 0) return false;
		for(var i in bike.rentList){
			var now = $(bike.rentList[i]).data('now');
			var time_start = $(bike.rentList[i]).data('time_start');
			$(bike.rentList[i]).data('now', now + 1000).text(bike.getTimeString(new Date(now - time_start), ':'));
		}
	}catch(error){
		//console.log(error);
	}
	
}

function bikes_find_response_handler(response, table, filter){
	table = table || '_bkListTable';
	filter = filter || null;

	$('table.' + table + ' tr._bInfo').detach();

	if(response.find.length == 0){
		$('table.' + table).append('<tr class="_bInfo"><td><h4><p class="text-warning"><?=TEMP::$Lang["no_bikes_finded"]?></p></h4></td></tr>');
	}
	for(var bk in response.find){
		var bkRow = new bikeData(response.find[bk], filter);
		$('table.' + table).append(bkRow.html);
	}
	bikeData.num = 1;

	bikeEventInit();
	
	$('input._payInRent').click(function(){
		bike.currId = $(this).data('bikeid');
		$('div._payrentModal').modal('show');
		user.currId = null;

	});

	bike.rentList = {};
}

function userEventInit(){
	$('i._delUsr').on('click', function(event){
		event.stopPropagation();
		user.currId = $(this).data('userid');
		user.currentCoordinates = $('._usContnr').scrollTop();
		usersModal.show();
	});

	$('i._edUsr').on('click', function(event){
		event.stopPropagation();
		user.currId = $(this).data('userid');
		user.currentCoordinates = $('._usContnr').scrollTop();
		$('div._editUserModal').modal('show');
	});
}

function bikeEventInit(){
	$('i._delBike').on('click', function(){
		bike.currId = $(this).data('bikeid');
		bikesModal.show();
	});

	$('i._edBike').on('click', function(event){
		event.stopPropagation();
		bike.currId = $(this).data('bikeid');
		$('div._editBikeModal').modal('show');
	});
}

function user_find_response_handler(list){
	$('#_usListPage table._usListTable tr._uInfo').detach();
	for(var l in list){
		usList = new userData(list[l], 'yes');
		$('#_usListPage table._usListTable').append(usList.html);
	}

	$('tr._uInfo').click(function(){
		var user_id = $(this).find('td:last-child i._delUsr').data('userid') === null ? $(this).find('input[name="uRent"]').data('userid') : $(this).find('td:last-child i._delUsr').data('userid');
		
		user.showInfo(user_id);
	});

	userEventInit();

	bike.rentList = {};
}

function bikeData(data, fill){
	fill = fill || 'in_store';
	if(bikeData.num === undefined) bikeData.num = 1;
	bikeData.getNum = function(){
		return bikeData.num++;
	};
	if(fill == 'in_store'){
		this.html = '<tr class="_bInfo"><td>' + bikeData.getNum() + '</td>' +
		'<td class="_bkInfo">' + data.model + '</td>' +
		'<td class="hidden-phone">' + data.adress + '</td>' +
		'<td>' + data.id + '</td>' +
		'<td>' + data.serial_id + '</td>' +
		'<td class="hidden-phone"><img src="' + data.foto + 
		'" alt="bike foto" class="bkList"></td>' + 
		'<td><input class="btn btn-link _payInRent" type="button" data-bikeid="' + data.id + '" value=' + 
		'"<?=TEMP::$Lang["come_rent"]?>"></td>' +
		'<?if(USER::isAdmin()):?><td><i class="icon-remove _delBike" data-bikeid="' + data.id + '"></i> <i class="icon-pencil _edBike" data-bikeid="' + data.id + '"></i></td><?endif?></tr>';
	}else if(fill == 'on_rent'){
		var now = bike.getLocalTimeNow();
		this.html = '<tr class="_bInfo" data-klientid="' + data.klient_id + '"><td>' + bikeData.getNum() + '</td>' +
		'<td class="_bkInfo">' + data.model + '<br><small>(' + data.adress + ')</small></td>' +
		'<td class="_timeOnRent" data-now="' + data.now + '" data-time_start="' + data.time_start * 1000 + '">' + bike.getTimeString(new Date(data.now - data.time_start * 1000), ':') + '</td>' +
		'<td>' + bike.getTimeString(new Date(data.project_time * 1000), ':') + 
		' (' + data.project_amount + (data.rent_prop.added > 0 ? ' + ' + data.rent_prop.added / 100 : '') + ' грн.)' +
		(data.patronymic == '' ? '' : '<br>' + data.name + ' ' +
		data.surname + ' ' + data.patronymic + ' ') + '<br><?=TEMP::$Lang['phone_numb']?> ' + data.phone + '</td>' +
		'<td>' + data.bike_id + '</td>' +
		'<td>' + data.serial_id + '</td>' +
		'<td><input class="btn btn-link _closeRent" type="button" data-bikeid="' + data.bike_id + '" value=' + 
		'"<?=TEMP::$Lang["income_rent"]?>"></td></tr>';
	};
	
}

function tabs_responsitive(){
	var width = $(window).width();
	if(width < 640){
		$('#view_mn').removeClass('tabs-left');
	}else{
		$('#view_mn').addClass('tabs-left');
	}
}

var usersModal = {};
var bikesModal = {};
var bikeRentModal = {};
var user_prop = <?=isset($_SESSION['CURRUSER']['properties']) ? $_SESSION['CURRUSER']['properties'] : 'null'?>;
bike.storeId = user_prop === null || user_prop.length == 0 ? null : user_prop.store;


function main_init(){
	$('a[href="#exit_btn"]').click(function(event){
		event.preventDefault();
		bike.logout();
	});

	tabs_responsitive();

	$(window).resize(function(){
		tabs_responsitive();
		$('div._usContnr').height($(window).height() - 130);
	});

	$('div._usContnr').height($(window).height() - 130);

	$('div._usersPaging').hide();
	
	$('input._searchTop').popover({
		'content': '<?=TEMP::$Lang["input_search_text_popover"]?>',
		'placement' : 'bottom',
		'title' : '<?=TEMP::$Lang["input_search_title_popver"]?>',
		'trigger' : 'hover'
	});
	
	$('div._mnBar a').click(function(event){
		user.currentCoordinates = $('._usContnr').scrollTop();
		var cssSel = $(this).attr('href');
		var element = $(cssSel) === undefined ? null : $(cssSel);
		if(cssSel == '#view_mn'){
			$('form._searchForm').fadeIn('fast').find('input:first-child').attr('name', $('#view_mn ul li.active a').attr('href'));

		}
		else $('form._searchForm').fadeOut('fast');
		if($(element).hasClass('disabled')){
			
		}else{
			event.stopPropagation();
			return false;
		}
	});

	$('form._searchForm').submit(function(event){
		event.preventDefault();
		var value = $(this).find('input:first-child').val();
		if(value.length < 3){
			bike.showMainAlert('<?=TEMP::$Lang['search_word_small']?>', 'error');
			return false;
		}
		var what_search = $('form._searchForm input:first-child').attr('name');
		$(this).ajaxSubmit({
			dataType : 'json',
			url: window.location,
			success: function(response) {
				if(response.status == 'ok'){
					if(what_search == '#_bikesAllPage'){
						bikes_find_response_handler(response, '_bkListTable');
					}else if(what_search == '#_bikesRent'){
						bikes_find_response_handler(response, '_bkListTableRent', 'on_rent');
					}else if(what_search == '#_usListPage'){
						user_find_response_handler(response.find);
					}
					

				}else if(response.status == 'error'){
					//error handler
				}else if(response.status == 'session_close'){
		        	bike.sessionStopped();
		        }
				
			},
			error : function(response){
				console.log('bad');
			}
		})
	});

	var rentTimeUpdate = setInterval(updateTimeOnRent, 1000);

	usersModal = new Modal('<?=TEMP::$Lang['user_delete']?>', '<?=TEMP::$Lang['usr_delete_text']?>', 
			function(){
				user.del();
				usersModal.hide();
			});

	bikesModal = new Modal('<?=TEMP::$Lang['bike_delete']?>', '<?=TEMP::$Lang['bike_delete_text']?>', 
			function(){
				bike.del();
				bikesModal.hide();
			});

	bikeRentModal = new Modal('<?=TEMP::$Lang['rent_stop']?>', '<?=TEMP::$Lang['rent_stop_text']?>', 
			function(){
				if(bike.storeId == null){
					$('div._storeSelect').show();
					return false;
				}
				bike.stopRent();
				bikeRentModal.hide();
			});
	
	bikes_fill();
	
	$('ul._topPanel li').click(function(event){
		event.preventDefault();
		var activeBlockId = $('ul._topPanel li.active').find('a').attr('href');
		$('ul._topPanel li.active').removeClass('active');
		var clickBlockId = $(this).addClass('active').find('a').attr('href');
		$(clickBlockId).removeClass('disabled');
		$(activeBlockId).addClass('disabled');
	});

	<?if(USER::isAdmin()):?>
	$('button._regPage').click(function(){
		$('div._viewPort span div.disabled').hide();
		$('div._registerForm').fadeIn('fast');
	});
	<?endif?>

	$('button._addBike').click(function(){
		$('div._viewPort span div.disabled').hide();
		$('div._addBikeForm').fadeIn('fast');
	});

	<?if(USER::isAdmin()):?>
	$('a[href="#_usListPage"]').click(function(event){
		event.preventDefault();
		
		$('form._searchForm input:first-child').attr('name', $(this).attr('href'));
		$('i._delUsr').off('click');
		users_fill();
	});
	<?endif?>

	$('a[href="#_bikesAllPage"]').click(function(event){
		event.preventDefault();
		$('form._searchForm input:first-child').attr('name', $(this).attr('href'));
		bikes_fill();
	});

	$('a[href="#_bikesRent"]').click(function(event){
		event.preventDefault();
		$('form._searchForm input:first-child').attr('name', $(this).attr('href'));
		bikesRent_fill('on_rent');
	});
};

function build_navchain(chain){
	$('div._usersNavChain ul li').detach();
	var elChain = '';
	for(var num in chain){
		switch(chain[num]){
			case 'curr':
				elChain = '<li class="active"><a data-page="' + chain['current'] + '" href="#">' + chain['current'] + '</a></li>';
				break;
			case '<':
				elChain = '<li class="disabled"><a data-page="' + (chain['current'] - 1) + '" href="#"> \< </a></li>';
				break;
			case '>':
				elChain = '<li class="disabled"><a data-page="' + (chain['current'] + 1) + '" href="#"> \> </a></li>';
				break;
			default :
				elChain = '<li class="disabled"><a data-page="' + (chain[num]) + '" href="#">' + chain[num] + '</a></li>';
				break;
		}
		if(num != 'current') $('div._usersNavChain ul').append(elChain);
	}
	$('div._usersNavChain ul li a').on('click', function(event){
		event.preventDefault();
		users_fill(($(this).data('page') - 1) * 100);
	});
}

function users_fill(offset){
	offset = offset || 0;
	$('#_usListPage table._usListTable tr._uInfo').detach();
	user.getUsersList({
		from_user_id : offset,
		onListResponse : function(){
			if(user.navChain[1] !== undefined){
				$('div._usersPaging').show();
				build_navchain(user.navChain);
			}
			userData.num = offset + 1;
			for(var us in user.currentList){
				var usRow = new userData(user.currentList[us]);
				$('#_usListPage table._usListTable').append(usRow.html);
			};

			$('._usContnr').scrollTop(user.currentCoordinates);
			//userData.num = offset + 1;
			userEventInit();

			$('tr._uInfo').click(function(){
						var user_id = $(this).find('i._delUsr').data('userid');
					
						user.showInfo(user_id);
					});

			bike.rentList = {};
		}
	});
}

function bikes_fill(filter){
	filter = filter || 'in_store';
	
	

	$('table._bkListTable tr._bInfo').detach();
	bike.getList({
		from_bike_id : 0,
		filter : filter,
		onListResponse : function(){
			if(bike.currentList.length == 0){
				$('table._bkListTable').append('<tr class="_bInfo"><td><h4><p class="text-warning"><?=TEMP::$Lang["no_bikes_in_store"]?></p></h4></td></tr>');
			}
			for(var bk in bike.currentList){
				var bkRow = new bikeData(bike.currentList[bk]);
				$('table._bkListTable').append(bkRow.html);
			}
			bikeData.num = 1;

			bikeEventInit();
			
			$('input._payInRent').click(function(){
				bike.currId = $(this).data('bikeid');
				$('div._payrentModal').modal('show');
				user.currId = null;

			});

			bike.rentList = {};
		}
	});
}

function bikesRent_fill(filter){
	filter = filter || 'on_rent';
	

	$('table._bkListTableRent tr._bInfo').detach();
	bike.getList({
		from_bike_id : 0,
		filter : filter,
		onListResponse : function(){
			if(bike.currentList.length == 0){
				$('table._bkListTableRent').append('<tr class="_bInfo"><td><h4><p class="text-warning"><?=TEMP::$Lang["no_bikes_in_list"]?></p></h4></td></tr>');
			}
			for(var bk in bike.currentList){
				var bkRow = new bikeData(bike.currentList[bk], filter);
				$('table._bkListTableRent').append(bkRow.html);
			}
			bikeData.num = 1;
			$('input._closeRent').click(function(){
				bike.currId = $(this).data('bikeid');
				user.currId = $(this).parent().parent().data('klientid');
				bikeRentModal.show();

			});

			bike.rentList = $('td._timeOnRent');
		}
	});
}
</script>