// 选择日期的默认配置
var dateDefaultConfig = {
	skinCell: "jedateblue", // 日期风格样式，默认蓝色
	format: "YYYY-MM-DD hh:mm:ss",  // 日期格式
	minDate: "1900-01-01 00:00:00", // 最小日期
	maxDate: "2099-12-31 23:59:59", // 最大日期
	language: {                                  // 多语言设置
		name: "cn",
		month: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"],
		weeks: ["日", "一", "二", "三", "四", "五", "六"],
		times: ["小时", "分钟", "秒数"],
		clear: "清空",
		today: "今天",
		yes: "确定",
		close: "关闭"
	},
	choosefun: function () {
		return false;
	}
};


// 选择的状态值
var killState = {
	weekValue: [], // 星期的value
	durationLimit: 72, // 持续时间的限制
	activeTimeId: '1', // 活动的时间
	limitWayId: '1' // 选择的限制方式
};


/**
 * 选择展示日期
 */
function showTime () {
	$("#showTime").jeDate(dateDefaultConfig);
}

/**
 * 启动时间
 */
function startUpTime () {
	$('#startUpTime').jeDate({
		skinCell: 'jedateblue', // 日期风格样式，默认蓝色
		format: 'hh:mm',  // 日期格式
		language: dateDefaultConfig.language
	})
}

/**
 * 检测当前活动时间选择的状态，修改当前的选项
 * @param {string} name 活动时间的radio的name值
 */
function checkActiveStatus (name) {
	killState.activeTimeId = $('input[name=' + name + ']:checked').val(); // 选中的id
	var week = $('#week'), selectTime = $('#selectTime');

	// 选中了固定的时间
	if (killState.activeTimeId === '1') {
		week.hide();
		selectTime.hide();
	}

	// 选中了按周期循环
	if (killState.activeTimeId === '2') {
		week.show();
		selectTime.show();
		selectStartTime();
	}

	setPlaceholder();
}


/**
 * 选择按周循环
 */
function duration () {
	var checkbox = $('.week-checkbox');

	checkbox.on('click', function () {
		killState.weekValue = [];
		var checked = $('.week-checkbox:checked');

		checked.each(function (i) {
			killState.weekValue.push(checked.eq(i).val());
		});

		setPlaceholder();

	});
}

/**
 * 选择按周循环
 */
function checkDuration () {
	killState.weekValue = [];
	var checked = $('.week-checkbox:checked');
	if (checked)
		checked.each(function (i) {
			killState.weekValue.push(checked.eq(i).val());
		});
}

/**
 * 设置持续时间的placeholder
 * */
function setPlaceholder () {
	var duration = $('#duration');

	// 判断当前选择的是固定时间还是持续时间
	if (killState.activeTimeId === '1') {
		killState.durationLimit = 72;

	} else if (killState.activeTimeId === '2') {

		if (killState.weekValue.length >= 2) {
			killState.durationLimit = 23;
		} else {
			killState.durationLimit = 72;
		}

	}

	duration.attr('placeholder', '1-' + killState.durationLimit + '小时之内');
}

/**
 * 按周期循环 选择开始时间 和 选择结束时间
 */
function selectStartTime () {

	var startTime = $('#startTime'), endTime = $('#endTime');


	var start = {
		format: 'YYYY-MM-DD',
		minDate: $.nowDate({DD: 0}),
//		minDate: dateDefaultConfig.minDate, //设定最小日期为当前日期
		isinitVal: false,
		ishmsVal: false,
		maxDate: dateDefaultConfig.maxDate, //最大日期
//		maxDate: $.nowDate({DD: 0}), //最大日期
		language: dateDefaultConfig.language,
		choosefun: function (elem, val, date) {
			end.minDate = date; //开始日选好后，重置结束日的最小日期
			endDates();
		}
	};


	var end = {
		format: 'YYYY-MM-DD',
		minDate: $.nowDate({DD: 0}), //设定最小日期为当前日期
		maxDate: dateDefaultConfig.maxDate, //最大日期
		language: dateDefaultConfig.language,
		choosefun: function (elem, val, date) {
			start.maxDate = date; //将结束日的初始值设定为开始日的最大日期
		}
	};


	// 这里是日期联动的关键
	function endDates () {
		endTime.jeDate(end);
	}

	startTime.jeDate(start);
	endTime.jeDate(end);
}


/**
 * 选择活动时间
 * @param  {string} name 活动时间的radio的name值
 */
function selectActiveTime (name) {
	$('input[name=' + name + ']').on('click', function () {
		checkActiveStatus(name);
	});
}

/**
 * 检测限制方式
 * */
function checkLimitWay (name) {
	killState.limitWayId = $('input[name=' + name + ']:checked').val();

	var limitStore = $('#limitStore'),
		storeTips = $('#storeTips'),
		limitPeopleNumber = $('#limitPeopleNumber'),
		peopleNumberTips = $('#peopleNumberTips');

	if (killState.limitWayId === '1') {
		limitStore.show();
		storeTips.show();
		limitPeopleNumber.hide();
		peopleNumberTips.hide();
	}

	if (killState.limitWayId === '2') {
		limitPeopleNumber.show();
		peopleNumberTips.show();
		limitStore.hide();
		storeTips.hide();
	}

}

/**
 * 选择限制的方式
 * @param {string} name 限制方式的radio的name值
 */
function selectLimitWay (name) {
	$('input[name=' + name + ']').on('click', function () {
		checkLimitWay(name);
	});
}

/**
 * 检测选择的状态
 */
function checkGoodsSelect () {
	var goodsList = $('#goodsList');
	var goodsInfo = $('#goodsInfo');
	var value = goodsList.val();
	if (value === '' || value === null) {
		goodsInfo.hide();
	} else {
		goodsInfo.show();
	}
}

/**
 * 选择商品
 */
function selectGoods () {
	var currentStore = $('#currentStore'),
		weChatPrice = $('#weChatPrice'),
		killPrice = $('#killPrice'),
		goodsList = $('#goodsList');

	goodsList.on('change', function () {
		console.log(goodsList.val());
		checkGoodsSelect();
		currentStore.html(productInfoArr[goodsList.val()].stock);
		weChatPrice.html(productInfoArr[goodsList.val()].price_package);
	});
}

/**
 * 显示错误提示
 * @param {string} msg 需要显示的错误信息
 * @param {boolean} bol 是否显示错误信息框
 */
function showErrorMessage (msg, bol) {
	var errorMsg = $('#errorMsg'), errorBox = $('#errorBox');
	errorMsg.html(msg);
	errorBox.show();

	$('#errorClose').off().on('click', function () {
		errorBox.hide();
	});

	if (bol) {
		errorBox.hide();
	}
}

function goBackAndCancel () {
	$('#cancelSave').on('click', function () {
		window.history.back(-1);
	});
}

/**
 * 保存秒杀
 */
function saveSeckill () {

	$('#killSave').on('click', function () {

		var activeName = $('#activeName'), // 活动时间
			keyWord = $('#keyWord'),  // 关键字
			showTime = $('#showTime'), // 展示时间
			startUpTime = $('#startUpTime'), // 启动时间
			duration = $('#duration'),  // 持续时间
			startTime = $('#startTime'), // 开始时间
			endTime = $('#endTime'), // 结束时间
			storeTotal = $('#storeTotal'), // 总库存
			homebuying = $('#homebuying'), // 每份的限制
			peopleNumber = $('#peopleNumber'), // 多少人限购1份
			limitNumber = $('#limitNumber'), // 限购多少份
			killPrice = $('#killPrice'),  // 秒杀价
			goodsList = $('#goodsList');  // 用户选择的商品


		// 判断活动名称是否为空
		if ($.trim(activeName.val()).length === 0) {
			showErrorMessage('请输入活动名称!', false);
			return false;
		}

		// 判断关键字描述是否为空
		if ($.trim(showTime.val()).length === 0) {
			showErrorMessage('请选择展示时间!', false);
			return false;
		}

		// 判断活动时间

		var durationValue = $.trim(duration.val());

		// 选中了固定的时间
		if (killState.activeTimeId === '1') {

			// 判断启动时间是否为空
			if ($.trim(startUpTime.val()).length === 0) {
				showErrorMessage('请选择启动时间!', false);
				return false;
			}

			// 判断持续时间是否为空
			if (durationValue.length === 0) {
				showErrorMessage('请输入持续时间!', false);
				return false;
			}

			// 判断持续时间是否符合要求
			if (parseFloat(durationValue) < 1 || parseFloat(durationValue) > 72) {
				showErrorMessage('持续时间请输入1-72小时内!', false);
				return false;
			}

		}

		// 选中了按周期循环
		if (killState.activeTimeId === '2') {

			// 判断开始时间是否为空
			if ($.trim(startTime.val()).length === 0) {
				showErrorMessage('请选择开始时间!', false);
				return false;
			}

			// 判断结束时间是否为空
			if ($.trim(endTime.val()).length === 0) {
				showErrorMessage('请选择结束时间!', false);
				return false;
			}

			// 判断是否选择了时间段
			if (killState.weekValue.length === 0) {
				showErrorMessage('请选择时间段!', false);
				return false;
			}

			// 判断是否选择启动时间
			if ($.trim(startUpTime.val()).length === 0) {
				showErrorMessage('请选择启动时间!', false);
				return false;
			}

			// 判断持续时间是否为空
			if (durationValue.length === 0) {
				showErrorMessage('请输入持续时间!', false);
				return false;
			}

			// 根据规则判断持续是否输入正确
			if (parseFloat(durationValue) < 1 || parseFloat(durationValue) > killState.durationLimit) {
				showErrorMessage('请输入1-' + killState.durationLimit + '小时内!', false);
				return false;
			}

			if (moment(startTime.val()).valueOf() > moment(endTime.val()).valueOf()) {
				showErrorMessage('结束时间必须小于开始时间!', false);
				return false
			}

		}

		var prefix = moment(showTime.val()).format('YYYY-MM-DD')
		var startUpValue = prefix + ' ' + startUpTime.val();
		if (moment(startUpValue).valueOf() - moment(showTime.val()).valueOf() < 60000) {
			showErrorMessage('展示时间要比启动时间早10分钟以上!', false);
			return false
		}


		// 判断限制方式

		// 限制库存
		if (killState.limitWayId === '1') {

			// 判断秒杀总库存
			if ($.trim(storeTotal.val()).length === 0) {
				showErrorMessage('请输入秒杀总库存!', false);
				return false;
			}

			// 判断每人限购的数量是否为空
			if ($.trim(homebuying.val()).length === 0) {
				showErrorMessage('每人限购的数量!', false);
				return false;
			}
		}

		// 限制名额
		if (killState.limitWayId === '2') {

			// 限制多少人购买一次
			if ($.trim(peopleNumber.val()).length === 0) {
				showErrorMessage('请输入限制多少人购买一次!', false);
				return false;
			}

			// 限购的数量
			if ($.trim(limitNumber.val()).length === 0) {
				showErrorMessage('请输入限购的数量!', false);
				return false;
			}
		}


		// 判断是否选择了其他
		var otherValue = [];
		var otherChecked = $('.kill-other:checked');
		otherChecked.each(function (i) {
			otherValue.push(otherChecked.eq(i).val());
		});

		if (otherValue.length === 0) {
			showErrorMessage('请选择其他中的一项!', false);
			return false;
		}

		// 判断用户是否选择了商品
		if (goodsList.val() === null || $.trim(goodsList.val()).length === 0 || goodsList.val() === '') {
			showErrorMessage('请选择商品!', false);
			return false;
		}

		// 判断用户是否输入了秒杀价
		if ($.trim(killPrice.val()).length === 0) {
			showErrorMessage('请输入秒杀价!', false);
			return false;
		}


		showErrorMessage('', true);

		var layer = $('#saveLayer');
		layer.show();

		layer.find('.btn').off().on('click', function () {
			$('#killSave').closest("form").submit();
			layer.hide();
		});

		layer.find('.close').off().on('click', function () {
			layer.hide();
		});
	});
}

$(function () {
	checkGoodsSelect();
	startUpTime();
	showTime();
	duration();
	checkActiveStatus('schedule_type');
	selectActiveTime('schedule_type');
	checkLimitWay('limit');
	selectLimitWay('limit');
	selectGoods();
	saveSeckill();
//    goBackAndCancel();
	checkDuration();
});
