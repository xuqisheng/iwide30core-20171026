
(function($) {
$.fn.cusCalendar = function(setting) {
	var MICRO_SECOND = 86400000;
    var TODAY = new Date();
    var MORROW= new Date();	
	TODAY.setHours(0,0,0,0);
    MORROW.setTime(TODAY.getTime() + MICRO_SECOND);
	var calendar=$('<div class="gradient_bg" id="ncalendar"></div>');
	var preBtn  =$('<div id="preDate" class="iconfont preDate">&#xe015;</div>');
	var nextBtn =$('<div id="nextDate" class="iconfont">&#xe015;</div>');
	var curDate =$('<div id="currentDate"></div>');
	var td		=$('<td><div></div></td>');
	var ___w 	=0;
	var CurMonth=new Date();
	CurMonth.setDate(1);
	CurMonth.setHours(0,0,0,0);
    var $default = {
	    beginTime        : TODAY,
	    endTime          : MORROW,
	    maxDays          : 90,   //最大可选的天数
		minDays			 : 1,	 //最少连续选择的天数
	    preDays        	 : 0,	 //提前选择日期
		single	 		 : false,//只选择一次日期
		limit			 : true, //限制过去日期为不可点
		select_begin	 : true,
	    success			 : function(date){console.log(date)},
	};
	var option = $.extend( $default, setting);
	option.beginTime.setHours(0,0,0,0);
	option.endTime.setHours(0,0,0,0);
	option.minDays = (option.minDays-1)*MICRO_SECOND;
	function init(){
		var htm = '<div class="calendar_head"></div><table id="cal_table" cellpadding="0" cellspacing="0" border="0"><thead>'
				+ '<tr><td>日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td>六</td></tr>'
				+ '</thead><tbody></tbody></table>';
		calendar.append(htm);
		$('body').append(calendar);
		calendar.find('.calendar_head').append(preBtn);
		calendar.find('.calendar_head').append(curDate);
		calendar.find('.calendar_head').append(nextBtn);
		FillMonth(TODAY);
		preBtn.get(0).onclick=function(e){
			e.stopPropagation();
			if($(this).hasClass("unable")) return false;
			CurMonth.setMonth(CurMonth.getMonth()-1);
			FillMonth(CurMonth);
			$("#nextDate").removeClass("unable");
		}
		nextBtn.get(0).onclick=function(e){
			e.stopPropagation();
			if($(this).hasClass("unable")) return false;
			CurMonth.setMonth(CurMonth.getMonth()+1);
			FillMonth(CurMonth);
			$("#preDate").removeClass("unable");
		}
		option.success({
			beginTime:option.beginTime,
			endTime  :option.endTime,
			days	 :(option.endTime.getTime()-option.beginTime.getTime())/MICRO_SECOND
		});
	}
	var _maxday =Math.round(TODAY.getTime()/MICRO_SECOND)+option.maxDays+option.preDays;
	var _minday =Math.round(TODAY.getTime()/MICRO_SECOND)+option.preDays;
	var start_index, end_index
	function FillMonth(date){	
		curDate.attr('date',date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate());
		curDate.html(date.getFullYear()+'年'+N(date.getMonth()+1)+'月');
		calendar.find('tbody').html('');
		date.setHours(0,0,0,0)
		var monthDays = new Date(date.getFullYear() , (date.getMonth()+1) , 0).getDate();
		var FillDate = new Date(date);
		FillDate.setDate(1);
		var blank = FillDate.getDay();
		var _t = TODAY.getMonth() + '/' +date.getFullYear(),
			_f = FillDate.getMonth() + '/' +FillDate.getFullYear();
		if(_t == _f ){$("#preDate").addClass("unable")}
		var tr 	  = $('<tr></tr>');
		for (var i = blank; i > 0; i--){
			tr.append('<td class="unable"></td>');
		}
		calendar.find('tbody').append(tr);
		var startDate;
		for (var i = 1; i <= monthDays; i++) {
			var td = $('<td date="' + i +'"><div>' + i +'</div></td>');
			if( TODAY.getTime() == FillDate.getTime()){
				td.find('div').html('今天').addClass("today");
			}
			if(blank % 7 == 0 && tr.html()!=''){
				tr  = $('<tr></tr>');
				calendar.find('tbody').append(tr);
			}
			blank++;
			tr.append(td);
			var tmpday = Math.round(FillDate.getTime()/MICRO_SECOND);
			if(tmpday > _maxday){$("#nextDate").addClass("unable")}
			if(option.limit&&(tmpday < _minday || tmpday > _maxday))td.addClass('unable');
			else td.addClass('able');
			var _S =option.beginTime.getTime(), _E =option.endTime.getTime();
			if( _S ==FillDate.getTime())td.addClass('S BG');
			if( _E ==FillDate.getTime() && _E > _S )td.addClass('E BG');
			if( FillDate.getTime()>_S&&FillDate.getTime()<=_E) td.addClass('BG');
			td.get(0).onclick=function(e){
				if($(this).hasClass('unable'))return;
				// e.stopPropagation();
				var curSelect = new Date(curDate.attr('date'));
				var tds = calendar.find('.able');
				
				curSelect.setDate($(this).attr('date'));
				if( curSelect.getTime() <= option.beginTime.getTime()+option.minDays){
					option.select_begin = true;
				}
				
				if(option.select_begin){
					option.beginTime = curSelect;
					option.endTime = curSelect;
					startDate = date
					start_index = tds.index($(this));
					calendar.find('.BG').removeClass('S E BG');
					$(this).addClass('S T BG');
					
					// console.log('select start date:'+option.beginTime);
				}else{
					option.endTime = curSelect;
					end_index = tds.index($(this));
					if(startDate != date){
						tds.slice(0,end_index).addClass('BG');
					}
					else{
						tds.slice(start_index + 1,end_index).addClass('BG');
					}
					$(this).addClass('E BG');
					calendar.find('.T').removeClass('T');
					closeView();
					
					// console.log('select start date:'+option.endTime);
				}
				if(option.single|| !option.select_begin){
					option.success({
						beginTime:option.beginTime,
						endTime  :option.endTime,
						days	 :(option.endTime.getTime()-option.beginTime.getTime())/MICRO_SECOND
					});
				}
				option.select_begin=!option.select_begin;
			}
			FillDate.setDate(FillDate.getDate() + 1);
		};
		if($(".BG").length == 1)$(".BG").addClass('T');
		BUG();
	}
	function closeView(){setTimeout(function(){
				window.history.back(-1);
			},300); }
	function showView(){
		calendar.css('top',0);
		history.pushState({ path: this.path }, '', this.href);
		$(window).bind('popstate', function() {
			setTimeout(function(){
				calendar.css('top','100%');
			},100);
		})
	}
	function BUG(){
		/*1px边距Bug*/
		// ___w = calendar.find('td').eq(0).width()+2;
		// calendar.find('td').width();
		// calendar.find('td').height();
		// calendar.find('td div').width(___w);
		// calendar.find('td div').height("2.437rem");
		// calendar.find('td div').css('line-height',2.437+'rem');
		/*1px边距Bug*/		
	}
	function N(num){
		if(num<10) return '0'+num;
		else return num;
	}
	var startPosition, endPosition, deltaX, deltaY, moveLength;  
	$("body").on({
        'touchstart': function(e){
        	 e.stopPropagation();
           var touch = e.touches[0];  
	       startPosition = {  
	            x: touch.pageX,  
	            y: touch.pageY  
	       }  
	       moveLength = 0;
        },
        'touchmove': function(e){
        	e.preventDefault();
        	e.stopPropagation();
            var touch = e.touches[0];  
	        endPosition = {  
	            x: touch.pageX,  
	            y: touch.pageY  
	        };  

	        deltaX = endPosition.x - startPosition.x;  
	        deltaY = endPosition.y - startPosition.y;  
	        moveLength = Math.sqrt(Math.pow(Math.abs(deltaX), 2) + Math.pow(Math.abs(deltaY), 2)); 
        },
        'touchend': function(e){
        	 e.stopPropagation();
            if(deltaX < 0 && moveLength > 100) { 
            	nextBtn.click();
	        } else if (deltaX > 0 && moveLength > 100) {  
	        	preBtn.click();
	        }  
        }
      },"#cal_table");
	$(this).click(function(){showView();});
	init();
}})(Zepto);