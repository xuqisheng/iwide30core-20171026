


$(function(){	
	var overmonth = 0;
	var weekNames = [ '日', '一', '二', '三', '四', '五', '六' ];
	var today=new Date();
	var morrow=new Date((today/1000+86400)*1000);
//	$('#startdate').val((today.getMonth() + 1) + '-' + today.getDate());
//	$('#enddate').val((today.getMonth() + 1) + '-' + today.getDate());
	
	// $('.checkin .date').html((today.getMonth() + 1) + '月' + today.getDate() + '日');
	// $('.checkin .week').html(weekNames[today.getDay()]);
	
	// $('.checkout .date').html((morrow.getMonth() + 1) + '月' + morrow.getDate() + '日');
	// $('.checkout .week').html(weekNames[morrow.getDay()]);
	
	$('#checkdate').cusCalendar({
		_parent			:'checkdate',
		beginTimeElement:'checkin',
		endTimeElement  :'checkout',
		bTimeValElement :'startdate',
		eTimeValElement :'enddate',
		selectedCallBack:function(data){
			$('.checkin .week').html(weekNames[data.inDate.getDay()] );
			$('.checkin .date').html( (data.inDate.getMonth() + 1) + '月' + data.inDate.getDate() + '日');
			
			$('.checkout .week').html(weekNames[data.outDate.getDay()]);
			$('.checkout .date').html( (data.outDate.getMonth() + 1) + '月' + data.outDate.getDate() + '日');
			
			$('.checkin_time').html(data.dateSpan);
		}
	});
});