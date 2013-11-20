var bike = {
		currentList : {},
		currId : 0,
		rentList : {},
		storeId : null,
		stoppedFullInfo : {},
		logout : function(){
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'logout'},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                window.location.href = '/';
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		    });
		},
		getList : function(from){
			from = from || {
				from_bike_id : 0,
				filter : 'in_store',
				onListResponse : function(){
					
				}
			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_bikes_list_store', 'from_bike_id' : from.from_bike_id, 'filter' : from.filter},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                bike.currentList = response.bikes_list;
		                from.onListResponse();
		                
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		findInList : function(id, className, data_name, funct, clickElement){
			clickElement = clickElement || 'td:last-child i';
			id = id || bike.currId;
			funct = funct || function(){return false;};
			$('table.' + className + ' tr').each(function(num){
				//console.log(num + ' - ' + id + ' = ' + $(this).find('td:last-child i').data(String(data_name)));
				if($(this).find(clickElement).data(String(data_name)) == id){
					funct(num);
				}
			});
			//return false;
		},
		del : function(id){
			id = id || bike.currId;
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'bike_delete', 'bid' : id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                var userList = $('table._bkListTable tr');
		                user.findInList(id, '_bkListTable', 'bikeid', function(del_num){
			                $(userList[del_num]).fadeOut('slow', function(){
			                	$(userList[del_num]).detach();
			                });
		                });
		                bike.showMainAlert(response.mess);
		                
		            }else if(response.status == 'bad'){
		            	bike.showMainAlert(response.mess, 'error');
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
		},
		getDateString : function(date, divider){
			var month = date.getMonth() + 1;
			var day = date.getDate();
			var year = date.getFullYear();
			return month + divider + day + divider + year;
		},
		getDays : function(difference){
			return Math.floor(difference / (1000 * 60 * 60 * 24));
		},
		getTimeString : function(date, divider){
			var days = bike.getDays(date.getTime());
			var hours = date.getUTCHours();
			var minutes = date.getMinutes();
			var seconds = date.getSeconds();
			return (days == 0 ? ' ' : days + ' д. ') + hours + divider + (minutes < 10 ? '0' + minutes : minutes) + divider + (seconds < 10 ? '0' + seconds : seconds);
		},
		getLocalTimeNow : function(){
			var now = new Date();
			return now.getTime();
		},
		stopRent : function(){
			$('div._storeSelect').hide();
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'stop_rent', 'bike_id' : bike.currId, 'store_id' : bike.storeId, 'user_id' : user.currId},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                var bikeList = $('table._bkListTableRent tr');
		                bike.findInList(bike.currId, '_bkListTableRent', 'bikeid', function(del_num){
			                //console.log(del_num);
		                	$(bikeList[del_num]).fadeOut('slow', function(){
			                	$(bikeList[del_num]).detach();
			                });
		                }, '._closeRent');
		                bike.storeId = user_prop === null ? null : user_prop.store;


		                bike.stoppedFullInfo = response.fullInfo;
		                bike.stoppedFullInfo.stopTime = response.stopTime;
		                bike.stoppedFullInfo.rent_amount = response.rent_amount;
		                $('div._finalInfoModalWin').modal('show');
		                
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
		},
		sessionStopped : function(){
			window.location.href = '/';
		},
		showMainAlert : function(text, error, attent, wait){
			wait = wait || 3000;
			error = error || 'no';
			attent = attent || 'Warning!';
			if(error == 'error'){
				$('div._mainWinAlert').addClass('alert-error');
			}else{
				$('div._mainWinAlert').removeClass('alert-error');
			}
			$('div._mainWinAlert strong').text(attent);
			$('div._mainWinAlert span._messtext').text(text);
			$('div._mainWinAlert').slideDown('medium').delay(wait).slideUp('medium');
		},
		getStores : function(funct){
			funct = funct || function(data){

			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_stores'},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response.stores);                
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		editTable : function(table){
			var tdvalue = '';
			for(var num = 0; num < table.length; num++){
				var fields = $(table[num]).find('td');
				for(var n = 0; n < fields.length; n++){
					if($(fields[n]).data('input_type') !== undefined){
						tdvalue = $(fields[n]).text();
						$(fields[n]).html('<input type="' + $(fields[n]).data('input_type') + '" value="' + tdvalue + '">');
						$(fields[n]).find('input[type="text"]').focusout(function(){
							var value = $(this).find('input[type="text"]').val();
							$(this).find('input[type="text"]').parent().html(value);
						});
					}
				}
			}
		},
		acceptStore : function(table, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'accept_stores', 'accepted': table},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		deleteStore : function(table, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'delete_stores', 'deleted': table},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		getBikeById : function(id, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_bike_by_id', 'bike_id': id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		dayReport : function(storeId, funct){
			storeId = storeId || 'no';
			funct = funct || function(response){

			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'day_report', 'store_id' : storeId},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		periodReport : function(from, to, storeId, funct){
			storeId = storeId || 'no';
			funct = funct || function(response){

			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'period_report', 'store_id' : storeId, 'from' : from , 'to' : to},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		cancelRents : function(table, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'cancel_rents', 'cancel': table},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		numberFormat : function(number){
			number = number.toString();
			if(!/(^[0-9]{1,}[\.\,]{0,1}[0-9]{0,}$)/.test(number)) return false;
			number = number.split(',').join('.');
			var arNum = number.split('.');
			var numLen = arNum[0].length;
			if(numLen <= 1) return number;
			var numFormatted = [];
			var sep = 3;
			var inc = Math.ceil(numLen / 3 - 1);
			var g = numLen - 1 + inc;
			for(var i = g; i >= 0; i--){
				numFormatted[g] = arNum[0][i - inc];
				sep--;
				if(sep == 0){
					g--;
					numFormatted[g] = ' ';
					sep = 3;
				}
				g--;
			}
			numFormatted = numFormatted.join('');
			return numFormatted += arNum.length == 1 ? '.00' : '.' + arNum[1]; 
		},
		recalcFact : function(fact_time, rent_id, added, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'recalc_fact', 'fact_time' : fact_time, 'rent_id' : rent_id, 'added' : added},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		}
};

var user = {
		currentList : {},
		navChain : {},
		interval : 400,
		currId : 0,
		keypressflag : false,
		userInfoInterval : null,
		currentCoordinates : 0,
		keypressedInterval : 400,
		keyIntevalId : null,
		getUsersList : function (from){
			from = from || {
					from_user_id : 0,
					onListResponse : function(){

					}
				};	
			$.ajax({
				        url: window.location,
				        type:"POST",
				        data: {'action' : 'get_users_list', 'from_user_id' : from.from_user_id},
				        dataType: 'json',
				        success: function(response) {
				        	if(response.status == 'ok'){
				                user.currentList = response.users_list;
				                user.navChain = response.nav;
				                from.onListResponse();
				                
				            }else if(response.status == 'session_close'){
		            			bike.sessionStopped();
		            		}else{
				            	
				            }
				        },
				        error: function(response){
				        	
				        }
				});
		},
		findLoader : function(oper){
			var loader = $('div._findLoader');
			var container = $('div._findList');
			if(oper == 'show'){
				loader.css('top', container.height() / 2).css('left', container.width() / 2 - 102).show();
				$('div._findShadow').show();
			}else if(oper == 'hide'){
				loader.hide();
				$('div._findShadow').hide();
			}
		},
		findInList : function(id, className, data_name, funct){
			id = id || user.currId;
			funct = funct || function(){return false;};
			$('table.' + className + ' tr').each(function(num){
				//console.log(num + ' - ' + id + ' = ' + $(this).find('td:last-child i').data(String(data_name)));
				if($(this).find('td:last-child i').data(String(data_name)) == id){
					funct(num);
				}
			});
			//return false;
		},
		del : function(id){
			id = id || user.currId;
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'user_delete', 'uid' : id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                var userList = $('table._usListTable tr');
		                user.findInList(id, '_usListTable', 'userid', function(del_num){
			                $(userList[del_num]).fadeOut('slow', function(){
			                	$(userList[del_num]).detach();
			                });
		                });
		                bike.showMainAlert(response.mess);
		                
		            }else if(response.status == 'bad'){
		            	bike.showMainAlert(response.mess, 'error');
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
		},
		find : function(key){
			key = key || {
					word : '',
					onFind : function(finded){

					},
					maxLength : 3,
					responseEnd : function(){
						
					}
				};
			if(key.word.length >= key.maxLength){
				$.ajax({
					url: window.location,
					type:"POST",
					data: {'action' : 'find_user', 'key' : key.word},
					dataType: 'json',
					success: function(response) {
						try{
							key.responseEnd();
						}
						catch (err){
							//console.log(err);
						}
						if(response.status == 'ok'){
					        key.onFind(response.find);
					    }else if(response.status == 'session_close'){
		            		bike.sessionStopped();
		            	}
					},
					error: function(response){
						console.log('error');
					}
				});
			}else return false;
		},
		showInfo : function(klient_id){
			klient_id = klient_id || user.currId;
			//console.log(klient_id);
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_user_info', 'klient_id' : klient_id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                $('div._userFullName span').text(response['info'].name + ' ' + response['info'].surname + ' ' + response['info'].patronymic);
		                $('div._userFoto img').attr('src', response['info'].photo).show();
		                $('div._userLogin span').text(response['info'].login);
		                $('div._userLive span').text(response['info'].properties === null ? '---' : response['info'].properties.live_place === undefined ? '---' : response['info'].properties.live_place);
		                $('div._userRentBikeInfo span').text(response['info'].bike_id === null ? '---' : response['info'].model + ' Ser.No:' + response['info'].serial_id + ' No:' + response['info'].bike_id);
		                $('div._userRentBikeTime').data('now', response['info'].now * 1000).data('time_start', response['info'].bike_id === null ? 'no' : response['info'].time_start * 1000);
		                if(response['info'].properties !== null && response['info'].properties.blackList == 'on') $('div._userBlack').show();
		                else $('div._userBlack').hide();
		                $('div._userInfoWin').modal('show');
		                user.userInfoInterval = setInterval(updateTimeOnUserInfo, 1000);
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
			
		},
		getById : function(id, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_user_info', 'klient_id': id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		}
};