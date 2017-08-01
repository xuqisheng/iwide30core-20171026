if (FastClick) {
	FastClick.attach(document.body);
}

function popup () {
	$('.popup').off().on('click', function () {
		$('.popup').hide();
	});

	$('.popup .close').off().on('click', function () {
		$('.popup').hide();
	});

	$('.popup-content').off().on('click', function (ev) {
		ev.stopPropagation();
	});
}

popup();

