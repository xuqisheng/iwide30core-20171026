// JavaScript Document

var timePickerCss = $('<style>'
+'.timePicker{position:fixed; width:100%; height:100%; background:#f8f8f8;top:0;-webkit-transition:top 350ms; z-index:99}'
+'.timePicker{ display:flex;display:-webkit-flex;flex-flow:column;justify-content:space-between;align-items:stretch;}'
+'.timePicker header,.timePicker footer{flex-shrink:0}'
+'.PickerBtn{text-align:center; padding:10px}'
+'.timePicker .flexgrow{flex-grow:1; -webkit-flex-grow:1}'
+'.DayTab{text-align:center;display:flex;display:-webkit-flex;align-items:center; background:#fff; padding:10px 10px 5px 10px;justify-content:space-between;}'
+'.DayTab>*{border-bottom:2px solid transparent; padding:0 2px 5px 2px; margin:0 10px}'
+'.DayTab p{color:#666}'
+'.DayTab .iscur p{color:#000}'
+'.DayTab .iscur {color:inherit;border-bottom:2px solid}'
+'#moreDay{padding-left:5px;  position:relative; padding-left:25px; margin-left:0;}'
+'#moreDay:before{content:" "; height:40%;border-left:0.5px solid #e4e4e4;position: absolute; left:0; top:30%;}'
+'.timeBox{ background:#fff;display:flex;display:-webkit-flex;flex-wrap:wrap;align-content:space-around; border-top:0.5px solid #e4e4e4; padding:0 2.5%}'
+'.BoxTitle{padding:15px 2.5%;width:100%; border-top:0.5px solid #e4e4e4}'
+'.BoxTitle:first-child{border-top:0}'
+'.TimeBtn{width:20%; padding:8px 0; line-height:1; border:0.5px solid #e4e4e4; margin:0 2.5%; margin-bottom:15px; display:inline-block;;  text-align:center}'
+'.TimeBtn.forbidden{background:#e4e4e4; border-color:transparent;}'
+'.TimeBtn.active{color:#ff9900;border:0.5px solid;background:#fff4e3}'
+'</style>');
var DatePickerCss =$('<style>'
+'.DateLayer{display:flex; position:fixed; width:100%;height:100%;top:0; background:rgba(0,0,0,0.5);align-items:flex-end;z-index:111}'
+'.DateBox{background:#fff;flex-grow:1; -webkit-flex-grow:1; padding-bottom:8px;}'
+'.handleBtn{ background:#e4e4e4;display:flex;display:-webkit-flex;;justify-content:space-between;}'
+'.handleBtn>*{padding:10px; display:inline-block}'
+'.CurDate{text-align:center;}.CurDate .noallow{ opacity:0.3}'
+'.CurDate>*{padding:15px; display:inline-block; line-height:1; padding-bottom:6px}'
+'.DateTable{width:100%; text-align:center; color:#6e6e6e;border-spacing:6px;border-collapse:separate}'
+'.DateTable th,.DateTable td{ padding:4px 0;}'
+'.DateTable .ableDay{ color:#111}'
+'.DateTable .SelectDay{background:#ff9900; color:#fff}'
+'</style>');

$.extend({    
	getnumber:function(num){
		if(num<10) return '0'+num;
		else return num;
	}
});
$.fn.extend({
    timePicker:function(setting) {
		var $default ={
			range:['0:0-23:30'],
			text:['全天'],
			MenusTxt:['今天','明天','后天'],
			week:['周日','周一','周二','周三','周四','周五','周六'],
			showMore:true,
			MoreIcon:'&#xe62d;',
			MoreTxt:'更多',
			increment:60,  //时间间隔
			SelectDate:new Date(),
			SelectTime:'',
			callback:function(date){},
			DateSet:{
				callback:function(date){}
			}
		}
		var option = $.extend( $default, setting);
		option.SelectDate.setSeconds(0,0);
		var val = $(this).attr('value')? $(this).attr('value'):'';
		var timePickerHtml = $('<page class="timePicker"><header><div class="color_main DayTab h24"></div></header><section class="h24 flexgrow scroll" style="align-self:flex-start; width:100%;"><div class="timeBox"></div></section><footer></footer></page>');
		
		var fillDate = function(tmpday){
			timePickerHtml.find('.DayTab').html('');
			tmpday = tmpday?new Date(tmpday):new Date();
			var maxday= new Date();
			var today = new Date();
			tmpday.setHours(0,0,0,0);
			today.setHours(0,0,0,0);
			maxday.setHours(0,0,0,0);
			
			maxday.setDate(maxday.getDate()+option.MenusTxt.length);
			var over =false;
			if(tmpday.getTime()>=maxday.getTime()){
				over =true;
			}
			for(var i=0;i<option.MenusTxt.length;i++){
				var html= '';
				if(over){
					html=$('<div><p>'+option.week[tmpday.getDay()]+'</p><p>'+$.getnumber(tmpday.getMonth()+1)+'-'+$.getnumber(tmpday.getDate())+'</p></div>');
					if(i==0){
						html.addClass('iscur');
					}
					html.attr('date',tmpday.getFullYear()+'/'+(tmpday.getMonth()+1)+'/'+tmpday.getDate());
					tmpday.setMonth(tmpday.getMonth(),tmpday.getDate()+1);
				}else{
					html=$('<div><p>'+option.MenusTxt[i]+'</p><p>'+$.getnumber(today.getMonth()+1)+'-'+$.getnumber(today.getDate())+'</p></div>');
					if(today.getTime()==tmpday.getTime()){
						html.addClass('iscur');
					}
					html.attr('date',today.getFullYear()+'/'+(today.getMonth()+1)+'/'+today.getDate());
					today.setMonth(today.getMonth(),today.getDate()+1);
				}
				timePickerHtml.find('.DayTab').append(html);
				
				html.get(0).onclick=function() {
					$(this).addClass('iscur').siblings().removeClass('iscur');
					option.SelectDate=new Date($(this).attr('date'));
					moreDay.attr('date',option.SelectDate);
					fillTime(option.SelectDate);
				}
			}
			if(option.showMore){
				var moreDay = $('<div id="moreDay"><p class="iconfont h30">'+option.MoreIcon+'</p><p class="h20">'+option.MoreTxt+'</p></div>');
				timePickerHtml.find('.DayTab').append(moreDay);
				moreDay.attr('date',option.SelectDate);
				moreDay.datePicker({
					todayMaxTime: option.range[option.range.length-1].split('-')[1],
					SelectDate: option.SelectDate,
					callback:function(date){
						option.SelectDate=new Date(date);
						fillDate(option.SelectDate);
						fillTime(option.SelectDate);
					}
				});
			}
		}
		var fillTime = function(CurDate){
			CurDate = CurDate?CurDate:new Date();
			timePickerHtml.find('.timeBox').html('');
			var isfirst = true;
			for(var i=0;i<option.range.length;i++){
				var a = option.range[i].split('-')[0].split(':')[0];
				var b = option.range[i].split('-')[0].split(':')[1];
				var start   = new Date(CurDate);
				start.setHours(a,b,0,0);
				
				a = option.range[i].split('-')[1].split(':')[0];
				b = option.range[i].split('-')[1].split(':')[1];
				var end     = new Date(CurDate);
				end.setHours(a,b,0,0);
				
				var html    = '<div class="BoxTitle">'+option.text[i]+'</div>';
				timePickerHtml.find('.timeBox').append(html);
				while(start.getTime()<=end.getTime()){
					var TimeBtn = $('<div class="TimeBtn">'+$.getnumber(start.getHours())+':'+$.getnumber(start.getMinutes())+'</div>');
					timePickerHtml.find('.timeBox').append(TimeBtn);
					if( start.getTime()<CurDate.getTime()||start.getTime()<new Date().getTime()){TimeBtn.addClass('forbidden');}
					else{
						if(start.getTime()==CurDate.getTime() || isfirst){ 
							option.SelectTime=start.getHours()+':'+start.getMinutes();
							TimeBtn.addClass('active');isfirst=false;
						}
						if(option.SelectTime!=''&&option.SelectTime.split(':')[0]==start.getHours()&&option.SelectTime.split(':')[1]==start.getMinutes()){
							TimeBtn.addClass('active').siblings().removeClass('active');;
						}
						TimeBtn.get(0).onclick=function(){
							$(this).addClass('active').siblings().removeClass('active');
							option.SelectTime=$(this).html();
						}
					}
					start.setMinutes(start.getMinutes()+option.increment);
				}
			}
			if(timePickerHtml.find('.forbidden').length==timePickerHtml.find('.TimeBtn').length){
				timePickerHtml.find('.DayTab .iscur').get(0).onclick=function(){
					$.MsgBox.Alert('客官！今天已经打烊了');
				}
				timePickerHtml.find('.DayTab .iscur').next().trigger('click');
			}
		}
		var PickerBtn =$('<div class="bg_main PickerBtn">确认</div>');
			timePickerHtml.find('footer').append(PickerBtn);
			
		PickerBtn.get(0).onclick=function(){
			console.log(option.SelectTime)
			if(option.SelectTime!=''){
				var date = timePickerHtml.find('.DayTab .iscur').attr('date')+' '+option.SelectTime;
				option.SelectDate =new Date(date);
				option.callback(option.SelectDate);
			}
			timePickerHtml.remove();
			timePickerCss.remove();
		}
		$(this).click(function(){
			fillDate(option.SelectDate);
			fillTime(option.SelectDate);
			$('body').append(timePickerCss);
			$('body').append(timePickerHtml);
		})
		
    },
	datePicker:function(setting) {
		var $default ={
			todayMaxTime:'23:59',
			ableDay:180,
			showHandleBtn:true,
			SelectDate:new Date(),
			limit:false,
			callback:function(date){}
		}
		var option = $.extend( $default, setting);
		option.SelectDate.setHours(0,0,0,0);
		var cancel = $('<span cancel>取消</span>');
		var sure   = $('<span class="color_main" sure>确认</span>');
		var pre    = $('<span>&lt;</span>');
		var CurDate= $('<span></span>');
		var next   = $('<span>&gt;</span>');
		var DatePickeHtml =$('<div class="DateLayer"><div class="DateBox"><div class="handleBtn h22"></div><div class="CurDate h24"></div><table class="DateTable h22"><td class="ableDay">9</td><td class="ableDay SelectDay">10</td><td class="ableDay">11</td></tr></table></div></div>');
		var closePicker = function(){DatePickeHtml.remove();}
		DatePickeHtml.get(0).onclick=closePicker;
		
		DatePickeHtml.find('.handleBtn').append(cancel);
		DatePickeHtml.find('.handleBtn').append(sure);
		/*确认选择*/
		sure.get(0).onclick=function() {
			closePicker();
			if($('.SelectDay',DatePickeHtml).attr('date')!=undefined){
				option.SelectDate=new Date($('.SelectDay',DatePickeHtml).attr('date'));
				option.callback(option.SelectDate);
			}
		}
		var today = new Date();
		var MaxDay= new Date();
		var sysDay= new Date();
		var todayMaxTime=new Date();
			today.setHours(0,0,0,0);
			MaxDay.setHours(0,0,0,0);
			todayMaxTime.setHours(option.todayMaxTime.split(':')[0],option.todayMaxTime.split(':')[1],0,0);
			MaxDay.setDate(MaxDay.getDate()+option.ableDay);
		DatePickeHtml.find('.CurDate').append(pre);		
		DatePickeHtml.find('.CurDate').append(CurDate);	
		DatePickeHtml.find('.CurDate').append(next);
		CurDate.attr('CurDate',option.SelectDate);
			
		var Next_Pre = function(num){
			var date = new Date(CurDate.attr('date'));
			date.setHours(0,0,0,0);
			if(num!=undefined){
				date.setMonth(date.getMonth()+1*num);
				CurDate.attr('date',date);
				FillMonth(date);
				//console.log(date)
			}
			/*//限制点击*/
			date.setDate(31);
			if( date.getTime()> MaxDay.getTime()) next.addClass('noallow');
			else next.removeClass('noallow');
			date.setDate(today.getDate());
			if( date.getTime()<= today.getTime()) pre.addClass('noallow');
			else pre.removeClass('noallow');
		}
		
		pre.get(0).onclick=function(e){
			e.stopPropagation();
			if($(this).hasClass('noallow')&&option.limit)return;
			Next_Pre(-1);
		}
		next.get(0).onclick=function(e){
			e.stopPropagation();
			if($(this).hasClass('noallow')&&option.limit)return;
			Next_Pre(1);
		}
		Next_Pre();
			
		var FillMonth = function (curDate){
			curDate = curDate?new Date(curDate):new Date();	
			CurDate.attr('date',curDate);
			CurDate.html(curDate.getFullYear()+'年'+(curDate.getMonth()+1)+'月');
				
			curDate.setHours(0,0,0,0)
			var monthDays = new Date(curDate.getFullYear() , (curDate.getMonth()+1) , 0).getDate();
			var tempDate = new Date(curDate);
			tempDate.setHours(0,0,0,0);
			tempDate.setDate(1);
			var blank = tempDate.getDay();
			var html  = '<tr><td>周日</td><td>周一</td><td>周二</td><td>周三</td><td>周四</td><td>周五</td><td>周六</td></tr>';
			DatePickeHtml.find('.DateTable').html(html);
			var tr 	  = $('<tr></tr>');
			for (var i = blank; i > 0; i--) {
				tr.append('<td></td>');
			};		
			DatePickeHtml.find('.DateTable').append(tr);
			for (var i = 1; i <= monthDays; i++) {
				var td = $('<td>' + i +'</td>');
				if(blank % 7 == 0 && tr.html()!=''){
					tr  = $('<tr></tr>');
					DatePickeHtml.find('.DateTable').append(tr);
				}
				blank++;
				tr.append(td);
				td.get(0).onclick=function(e){
					e.stopPropagation();
				}
				if(tempDate.getTime() >= today.getTime() && tempDate.getTime() < MaxDay.getTime()){
					
					if(tempDate.getTime() == today.getTime()&&sysDay.getTime()>todayMaxTime.getTime()){
						tempDate.setDate(tempDate.getDate() + 1);
						continue;
					}else if(tempDate.getTime() == curDate.getTime()){
						td.addClass('SelectDay');
					}
					td.addClass('ableDay');
					td.attr('date',tempDate);
					td.get(0).onclick=function(e){
						e.stopPropagation();
						$('.ableDay').removeClass('SelectDay');
						$(this).addClass('SelectDay');
						if(!option.showHandleBtn)closePicker();
					}
				}
				tempDate.setDate(tempDate.getDate() + 1);
			};
		}
		
		$(this).click(function(){
			$('body').append(DatePickerCss);
			$('body').append(DatePickeHtml);
			option.SelectDate=new Date($(this).attr('date'));
			FillMonth(option.SelectDate);
		})
    }
}); 

/*
<page class="timePicker"><header><div class="color_main DayTab h24">
        	<div class="iscur">
            	<p>今天</p>
                <p>01-16</p>
            </div>
        	<div>
            	<p>今天</p>
                <p>01-16</p>
            </div>
        	<div>
            	<p>今天</p>
                <p>01-16</p>
            </div>
            <div class="break"></div>
        	<div id="moreDay">
                <p class="iconfont">&#xe62d;</p>
            	<p class="h20">更多</p>
            </div>
        </div>
    </header>
    <section class="h24 flexgrow" style="align-self:flex-start; width:100%;">
    	<div class="timeBox">
            <div class="BoxTitle">午餐</div>
            <div class="TimeBtn forbidden">11:30</div>
            <div class="TimeBtn active">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="BoxTitle">午餐</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
            <div class="TimeBtn">11:30</div>
        </div>
    </section>
    <footer><div class="bg_main PickerBtn">确认</div></footer>
</page>
<div class="DateLayer">
	<div class="DateBox">
    	<div class="handleBtn h22"><span cancel>取消</span><span class="color_main" sure>确认</span></div>
        <div class="CurDate h24"><span pre>&lt;</span><span CurDate>2017年12月</span><span next>&gt;</span></div>
        <table class="DateTable h22">
        	<tr>
            	<th>周日</th>
            	<th>周日</th>
            	<th>周日</th>
            	<th>周日</th>
            	<th>周日</th>
            	<th>周日</th>
            	<th>周日</th>
            </tr>
            <tr>
            	<td>1</td>
            	<td>2</td>
            	<td>3</td>
            	<td>4</td>
            	<td>5</td>
            	<td>6</td>
            	<td>7</td>
            </tr>
            <tr>
            	<td>8</td>
            	<td class="ableDay">9</td>
            	<td class="ableDay SelectDay">10</td>
            	<td class="ableDay">11</td>
            	<td>12</td>
            	<td>13</td>
            	<td>14</td>
            </tr>
        </table>
    </div>
</div>*/