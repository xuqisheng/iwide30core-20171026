var player;
var element = document.getElementsByName('videoElement')[0];
console.log($('.mainContainer').width());
console.log(290 / 320);

//$('.centeredVideo').eq(0).attr({'height': $(window).height() * (164 / 568), 'width': $(window).width() - 30});

if (typeof player !== "undefined") {
    if (player != null) {
        player.unload();
        player.detachMediaElement();
        player.destroy();
        player = null;
    }
}

player = flvjs.createPlayer({
    type: 'mp4',
    url: 'http://7n.cdn.iwide.cn/app/zb/40001c.mp4'
});
player.attachMediaElement(element);
player.load();

function flv_start() {
    player.play();
}

function flv_pause() {
    player.pause();
}

function flv_destroy() {
    player.pause();
    player.unload();
    player.detachMediaElement();
    player.destroy();
    player = null;
}

function flv_seekto() {
    var input = document.getElementsByName('seekpoint')[0];
    player.currentTime = parseFloat(input.value);
}

function getUrlParam(key, defaultValue) {
    var pageUrl = window.location.search.substring(1);
    var pairs = pageUrl.split('&');
    for (var i = 0; i < pairs.length; i++) {
        var keyAndValue = pairs[i].split('=');
        if (keyAndValue[0] === key) {
            return keyAndValue[1];
        }
    }
    return defaultValue;
}

