var max = $('.swiper-slide').length;
var nextButton = $('.right-arrow'), prevButton = $('.left-arrow');
var swiper = $('.swiper-slide');

var title = '距开始:';

function setHeight () {
	var height = $(window).height();
	if (height < 600) {
		height = 600;
	}
	$('#index').css({'height': height + 'px'});

}

setHeight();

$(window).on('reset', function () {
	setHeight();
});


/**
 * 检测是否能点击下一张，和上一张
 * @param  {string} activeIndex
 * */
function checkCanClick (activeIndex) {
	if (parseInt(activeIndex) + 1 >= max) {
		nextButton.removeClass('active');
		prevButton.addClass('active');
	}

	if (parseInt(activeIndex) + 1 < max) {
		nextButton.addClass('active');
		prevButton.addClass('active');
	}

	if (parseInt(activeIndex) + 1 === 1) {
		prevButton.removeClass('active');
	}

	$('#name').html(swiper.eq(activeIndex).attr('name'));
	$('#discount').html(swiper.eq(activeIndex).attr('discount'));
	$('#original').html('¥' + swiper.eq(activeIndex).attr('original'));
	$('#href').attr('href', swiper.eq(activeIndex).attr('href'));
}

var timer = null;
var timeLeft = 0;
var killTime = $('#killTime');
var sign = $('#sign');
var logo = $('#logo');

/**
 * 倒计时
 * */
function countDown () {
	clearTimeout(timer);
	if (timeLeft === 0) {
		clearTimeout(timer);
		sign.removeClass('kill-sign');
		logo.removeClass('kill-logo');
		killTime.css('opacity', '0');
		return false;
	}
	var days = parseInt(timeLeft / 1000 / 60 / 60 / 24, 10);
	var hours = parseInt(timeLeft / 1000 / 60 / 60 % 24, 10);
	var minutes = parseInt(timeLeft / 1000 / 60 % 60, 10);
	var seconds = parseInt(timeLeft / 1000 % 60, 10);
	timeLeft = timeLeft - 1000;
	var startDay = '';
	var startHour = '';
	var startMin = '';
	var startSec = '';

	if (days <= 0) {
		startDay = '';
	} else {
		var showDay = days > 9 ? days : '0' + days;
		startDay = '<span class="time-number">' + showDay + '</span><span class="f24 time-unit">天</span>';
	}

	if (hours <= 0) {
		startHour = '<span class="time-number">00</span><span class="f24 time-unit">时</span>';
	} else {
		var showHour = hours > 9 ? hours : '0' + hours;
		startHour = '<span class="time-number">' + showHour + '</span><span class="f24 time-unit">时</span>';
	}

	if (minutes <= 0) {
		startMin = '<span class="time-number">00</span><span class="f24 time-unit">分</span>';
	} else {
		var showMin = minutes > 9 ? minutes : '0' + minutes;
		startMin = '<span class="time-number">' + showMin + '</span><span class="f24 time-unit">分</span>';
	}

	if (seconds <= 0) {
		startSec = '<span class="time-number">00</span><span class="f24 time-unit">秒</span>';
	} else {
		var showSec = seconds > 9 ? seconds : '0' + seconds;
		startSec = '<span class="time-number">' + showSec + '</span><span class="f24 time-unit">秒</span>';
	}

	var timeVal = title;
	if (typeof timeVal === 'undefined') {
		timeVal = '距开始: ';
	}

	killTime.find('.time').html('<span class="f24 time-unit number-title">' + timeVal + '</span>' + startDay + startHour + startMin + startSec);

	sign.addClass('kill-sign');
	logo.addClass('kill-logo');
	killTime.css('opacity', '1');

	timer = setTimeout(countDown, 1000);
}

/**
 * 判断是否秒杀
 * @param  {string} activeIndex
 */
function checkKill (activeIndex) {
	var iskillsec = swiper.eq(activeIndex).attr('iskillsec');
	var countdown = parseInt(swiper.eq(activeIndex).attr('countdown'));
	var countdownEnd = parseInt(swiper.eq(activeIndex).attr('countdownend'));
	if (iskillsec === 'true' || iskillsec === true) {

		if (moment().valueOf() > countdown) {
			timeLeft = countdownEnd - moment().valueOf();
			title = '距结束:';
			countDown();
		} else {
			// 判断秒杀的开始时间离现在还有多远
			timeLeft = countdown - moment().valueOf();
			title = '距开始:';
			countDown();
		}


	} else {
		clearTimeout(timer);
		sign.removeClass('kill-sign');
		logo.removeClass('kill-logo');
		killTime.css('opacity', '0');
		timeLeft = 0;
	}

}

/**
 * 轮播的切换
 * */
function banner () {

	var mySwiper = new Swiper('.swiper-container', {
		direction: 'horizontal',
		loop: false,
		nextButton: '.right-arrow',
		prevButton: '.left-arrow',
		onSlideChangeEnd: function (swiper) {
			checkCanClick(swiper.activeIndex);
			checkKill(swiper.activeIndex);
		}
	});

	if (max > 1) {
		nextButton.addClass('active');
	}

	if (max === 1) {
		nextButton.removeClass('active');
		prevButton.removeClass('active');
	}


	$('#name').html(swiper.eq(0).attr('name'));
	$('#discount').html(swiper.eq(0).attr('discount'));
	$('#original').html('¥' + swiper.eq(0).attr('original'));
	$('#href').attr('href', swiper.eq(0).attr('href'));
}

function distribution () {
	$('#distribution').on('click', function () {
		$('.popup').show();
	});
}

checkKill(0);
distribution();
banner();


