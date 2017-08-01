(function($) {
$.fn.cusCalendar = function(options) {
	var MICRO_SECOND_DATE = 86400000;
    var __currentTime = new Date();
    var __currentHour=__currentTime.getHours();
    var preDay=0;
    if(__currentHour<6){
    	__currentTime.setTime(__currentTime.getTime() - MICRO_SECOND_DATE); 
    	preDay=-1;
    }
    var __endTime   = new Date();
    __endTime.setTime(__currentTime.getTime() + MICRO_SECOND_DATE);
    var defualts = {
		_parent			 : null,
	    beginTime        : __currentTime,
	    endTime          : __endTime,
	    maxDays          : 90,
	    maxDiffDays      : 15,
		select_day		 : 60,
	    selectedCallBack : function(){},
	    beginTimeElement : 'beginTime',
	    endTimeElement   : 'endTime',
	    bTimeValElement  : 'btime',
	    eTimeValElement  : 'etime',
	    bindByClass      : true,
	    preSpDate        : preDay,
		isclass			 : false,
		select_single	 : false
	    };
	var opts = $.extend({}, defualts, options);
	if($('#'+opts.bTimeValElement).val() != ''){
		opts.beginTime = new Date($('#'+opts.bTimeValElement).val());
	}
	if($('#'+opts.eTimeValElement).val() != ''){
		opts.endTime = new Date($('#'+opts.eTimeValElement).val());
	}
	opts.beginTime.setHours(0);
	opts.beginTime.setMinutes(0);
	opts.beginTime.setSeconds(0);
	opts.beginTime.setMilliseconds(0);
	opts.endTime.setHours(0);
	opts.endTime.setMinutes(0);
	opts.endTime.setSeconds(0);
	opts.endTime.setMilliseconds(0);
	var selectedDateBegin = opts.beginTime;
	var selectedDateEnd   = opts.endTime;
	var callBackElement   = null;
	//$('table').html();
	function generate(_selectedDateBegin,_selectedDateEnd,selectBeginDate){
		$('body').css('overflow','hidden');
		var currentDate      = new Date();
		var str = "<thead><tr><td>日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td>六</td></tr></thead>";
		str += "<tbody>";
		currentDate.setDate(1);
		for (var i=0; i < 4; i++) {
			var month = currentDate.getMonth() + 1;
			if(month < 10) month = '0' + month;
			str += "<tr style=\"border:0\"><td colspan=\"7\" class=\"timename\">"+currentDate.getFullYear() + '年' + month + '月'+"</td></tr>";
			str += createMonth(currentDate,_selectedDateBegin,_selectedDateEnd,selectBeginDate);
			currentDate.setMonth(currentDate.getMonth() + 1);
		}
		str += "</tbody>";
		if (selectBeginDate){
			str+="<div class='_tips'><span>请选择入住日期</span></div>";
		}
		else{
			str+="<div class='_tips'><span>请选择离店日期</span></div>";
		}
		return str;
	}
	function createMonth(_currentDate,_selectedDateBegin,_selectedDateEnd,selectBeginDate){
		monthDays = new Date(_currentDate.getFullYear() , (_currentDate.getMonth() +1) , 0).getDate();
		var tempDate = new Date(_currentDate.getFullYear() , (_currentDate.getMonth() +1) , _currentDate.getDate());
		tempDate.setTime(_currentDate.getTime());
		tempDate.setHours(0);
		tempDate.setMinutes(0);
		tempDate.setSeconds(0);
		tempDate.setMilliseconds(0);
		tempDate.setDate(1);
		var blank_count = tempDate.getDay() ;
		var str = "<tr rel=\""+tempDate.getFullYear() + '/' + (tempDate.getMonth() + 1) + '/'+"\">";
		for (var j = blank_count; j > 0; j--) {
			str += '<td d=\"-1\" class="unable"></td>';
		};
		var sysDate = new Date();
		sysDate.setHours(0);
		var tempDay = Math.round(tempDate.getTime()/MICRO_SECOND_DATE);
		var beginDay = Math.round(_selectedDateBegin.getTime()/MICRO_SECOND_DATE);
		var endDay = Math.round(_selectedDateEnd.getTime()/MICRO_SECOND_DATE);
		for (var i = 1; i <= monthDays; i++) {
			tempDay = Math.round(tempDate.getTime()/MICRO_SECOND_DATE);
			var cssClass = " class=\"";
			var _maxday =Math.round(sysDate.getTime()/MICRO_SECOND_DATE)+opts.maxDays+opts.preSpDate;
			var _minday =Math.round(sysDate.getTime()/MICRO_SECOND_DATE)+opts.preSpDate;
			if(tempDay < _minday || tempDay>_maxday){
				cssClass += "unable ";
			}
			var tDayStr = '';
			if(!opts.singleDate){
				if(beginDay == tempDay){
					cssClass += "begin";tDayStr = '<span>入住</span>';
				}
				if(endDay == tempDay&& !opts.select_single){
					cssClass += "current";tDayStr = '<span>离店</span>';
				}
			}
			if(endDay > tempDay && beginDay < tempDay && !opts.select_single)cssClass += "span";
			cssClass +="\"";
			if(blank_count % 7 == 0) str += "</tr><tr rel=\""+tempDate.getFullYear() + '/' + (tempDate.getMonth() + 1) + '/'+"\"><td" + cssClass + " d=\"" + i + "\">" + i + tDayStr  + "</td>";
			else str += "<td" + cssClass + " d=\"" + i + "\">" + i  + tDayStr + "</td>";
			blank_count++;
			tempDate.setDate(tempDate.getDate() + 1);
		};
		return str += "</tr>";
	}
	function tdClick(selectBeginDate){
		var weekNames = [ '星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六' ];
		$("td",$('#ncalendar')).click(function(){
			if($(this).hasClass('unable')) return;
			if($(this).hasClass('timename')) return;
			$('span',$(this)).remove();
			var self = $(this).html();
			var _this = $(this);
			var selectedDate = new Date(_this.parent().attr('rel') + self);
			if(selectBeginDate){
				var tds = $("tr[rel]>td[d!='-1']","#ncalendar");
				var begin_index = tds.index($(this));
				var end_index  = 0;
				if(selectedDate >= selectedDateEnd){
					selectedDateBegin = new Date(selectedDate.getFullYear() , selectedDate.getMonth() , selectedDate.getDate());
					selectedDateEnd=new Date(selectedDate.getFullYear() , selectedDate.getMonth() , selectedDate.getDate() + 1);
				}else{
					selectedDateBegin = selectedDate;
				}
				var oldEnd = $('td[class=current]',"#ncalendar");
				$('span',oldEnd).remove();
				oldEnd.removeClass('current');
				$('tr>td[class=span]',"#ncalendar").removeClass('span');
				var endTd = $("tr[rel='"+selectedDateEnd.getFullYear() + '/' + (selectedDateEnd.getMonth() + 1) + '/' +"']>td[d="+selectedDateEnd.getDate()+"]");
				$('span',endTd.parent()).remove();
				if ( opts._parent !=null && !opts.select_single){
					//endTd.addClass('current');
					//endTd.html(endTd.html() + '<span>离店</span>');
					//end_index = tds.index(endTd);
				}
				selectBeginDate = false;
				var oldBegin = $('td[class=begin]');
				$('span',oldBegin).remove();
				oldBegin.removeClass('begin');
				$('tr>td',"#ncalendar").addClass('unable');
				_this.removeClass('unable').addClass('begin');
				_this.html(self + '<span>入住</span>');
				//tds.slice(begin_index + 1,end_index).removeClass('unable').addClass('span');
				end_index=begin_index+opts.select_day+1;
				tds.slice(begin_index + 1,end_index).removeClass('unable');
				$('#' + opts.bTimeValElement).val(selectedDateBegin.getFullYear() + '/' + (selectedDateBegin.getMonth() + 1) + '/' + selectedDateBegin.getDate());
				if( opts._parent ==null || opts.select_single ){
					$("td",$('tbody')).unbind();
					tdClick(selectBeginDate);
					window.setTimeout(function(){
						history.back(-1);
					},600);
					opts.selectedCallBack({inDate:selectedDateBegin,outDate:selectedDateEnd,dateSpan:parseInt((selectedDateEnd - selectedDateBegin) / MICRO_SECOND_DATE)});
				}
				$('._tips span').html('请选择离店日期');
			}else {
				if(selectedDateBegin >= selectedDate){
					selectedDateEnd = new Date(selectedDate.getFullYear() , selectedDate.getMonth() , selectedDate.getDate() + 1);
					selectedDateBegin=new Date(selectedDate.getFullYear() , selectedDate.getMonth() , selectedDate.getDate());
				}else{
					selectedDateEnd  = selectedDate;
				}
				$('#' + opts.eTimeValElement).val(selectedDateEnd.getFullYear() + '/' + (selectedDateEnd.getMonth() + 1) + '/' + selectedDateEnd.getDate());
				opts.selectedCallBack({inDate:selectedDateBegin,outDate:selectedDateEnd,dateSpan:parseInt((selectedDateEnd - selectedDateBegin) / MICRO_SECOND_DATE)});

				var tds = $("tr>td[d!='-1']");
				var end_index = tds.index(_this);
				var beginEle = $("tr[rel='"+selectedDateBegin.getFullYear() + '/' + (selectedDateBegin.getMonth() + 1) + '/' +"']>td[d="+selectedDateBegin.getDate()+"]");
				var begin_index  = tds.index(beginEle);
				$('tr>td[class=span]',"#ncalendar").removeClass('span');
				_this.addClass('end');
				_this.html(self + '<span>离店</span>');
				tds.slice(begin_index + 1,end_index).addClass('span');
					window.setTimeout(function(){
						history.back(-1);
					},600);
			}
		});
	}
	if ( opts._parent !=null){
		$('#' +  opts._parent).click(function(){
			history.pushState({ path: this.path }, '', this.href);
			$('body').append('<div id="ncalendar"><div><table>' + generate(selectedDateBegin,selectedDateEnd,true) + '</table></div></div>');
			tdClick(true);
		});
	}
	else{
		$('#' + opts.beginTimeElement).click(function(){
			history.pushState({ path: this.path }, '', this.href);
			$('body').append('<div id="ncalendar"><div><table>' + generate(selectedDateBegin,selectedDateEnd,true) + '</table></div></div>');
			tdClick(true);
		});
		$('#' + opts.endTimeElement).click(function(){
			history.pushState({ path: this.path }, '', this.href);
			$('body').append('<div id="ncalendar"><div><table>' + generate(selectedDateBegin,selectedDateEnd,false) + '</table></div></div>');
			tdClick(false);
		});
	}

	$(window).bind('popstate', function() {
		setTimeout(function(){
			$('#ncalendar').hide();
			$('#ncalendar').remove();
			$('body').css('overflow','auto');
		},100);
	})
}})(jQuery);