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

function toCenter(element){
    if(element instanceof Object) {
        var elPosX = ($(document).width() + $(document).scrollLeft()) / 2 - $(element).width() / 2;
        var elPosY = ($(document).height() + $(document).scrollTop()) / 2 - $(element).height() / 2;
        $(element).css({top : elPosY, left : elPosX});
    };
};

function tableFromData(params){
    params = params || {};
    if(params.head !== undefined) this.head = params.head;
    if(params.classes !== undefined) this.classes = params.classes;
    else this.classes = '';
    this.counter = false;
    if(params.counter !== undefined) this.counter = params.counter;
    this.rowNum = 0;
    this.table = '';
    
    this.fill = function(data){
        data = data || {};
        this.table = '';
        if(data.length == 0) return false;
        tableFromData.createHead(this);
        for(var d in data){
            this.table += '<tr>';
            if(this.counter){
                this.rowNum++;
                this.table += '<td>' + this.rowNum + '</td>';
            }
            for(var v in this.head){
                this.table += '<td>' + (data[d][v] === undefined ? '' : data[d][v]) + '</td>';
            }
            this.table += '</tr>';
        }
        this.table += '</tbody></table>';
        this.rowNum = 0;
    };
    
    tableFromData.createHead = function(me){
        me.table = '<table class="' + me.classes + '"><tbody><tr>';
        if(me.counter) me.table += '<th>№</th>';
        for(var v in me.head){
            me.table += '<th>' + me.head[v] + '</th>';
        }
        me.table += '</tr>'
    };
}
