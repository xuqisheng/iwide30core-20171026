function banner () {
	var swiper = new Swiper('.swiper-container', {
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
		pagination: '.swiper-pagination',
		paginationType: 'fraction'
	});

	if ($('.swiper-slide').length <= 1) {
		$('.swiper-pagination').remove();
	}
}

function progress () {
    try{
        var current = $('#currentNumber').html();
        var currentNumber = parseInt(current.substr(0, current.length - 1));
        var total = parseInt($('#total').html());
        $('#progress').css({'width': (currentNumber / total) * 100 + '%'})
    }
	catch(e){

    }
}

function tabs () {
	$('#tabs').off().on('click', 'a', function () {
		$('.pic-content').hide();
		$('#tabs a').removeClass('active');
		$('#tabs a').eq($(this).index()).addClass('active');
		$('.pic-content').eq($(this).index()).show();
	});
}

function getMoreService () {
	$('#more').on('click', function () {
		$('#servicePopup').show();
	});
}

function getSpec () {
	$('#specPopup').show();
	checkShowBtn();
	var specList = $('.spec-list');
	for (var i = 0; i < specList.length; i++) {
		specList.eq(i).attr('index', i);
		specList.eq(i).off().on('click', 'li', function () {
			var index = parseInt($(this).parent().attr('index'));
			specList.eq(index).find('li').removeClass('active');
			$(this).parent().attr('value', $(this).attr('value'));
			$(this).addClass('active');
			checkShowBtn();
		});
	}
}

function checkShowBtn () {
	var arr = [];
	var specList = $('.spec-list');
	for (var i = 0; i < specList.length; i++) {
		var val = specList.eq(i).attr('value');
		if (val) {
			arr.push(val);
		}
	}
	var btn = $('#buyNow');
	if (arr.length === specList.length) {
		btn.show();
		// 选中了所有的规格
		btn.off().on('click', function (ev) {
			ev.stopPropagation();
			console.log(arr);
		});
	} else {
		btn.hide();
	}
}

// 选择规格
function spec () {
	$('#selectSpec').off().on('click', function () {
		getSpec();
	});
}


// 点击购买
function buy () {
	//$('#buy').on('click', function () {
     //   getSpec();
	//});
}


buy();
getMoreService();
tabs();
spec();
progress();
banner();