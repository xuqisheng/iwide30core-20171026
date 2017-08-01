//解决微信防盗的问题
function showImg(url, id) {
    var frameid = 'frameimg' + Math.random();
    window['img_' + id] = '<img id="img" src=\'' + url + '?' + Math.random() + '\' /><style>*{margin:0}img{width:100%}</style>';
    $("#" + id).html('<iframe id="' + frameid + '" src="javascript:parent[\'img_' + id + '\'];" frameBorder="0" scrolling="no" width="100%"></iframe>');
}

//时间戳转格式日期
function getLocalTime(nS) {
    return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/, ' ');
}

var materialGetCallbacks = $.Callbacks();

function getMaterialData(type, page, url) {
    page = page || 1;
    $.ajax({
        url: url,
        method: 'get',
        data: {
            page: page,
            type: type
        },
        beforeSend: function () {
            materialGetCallbacks.fire(type, 'beforeSend');
        },
        success: function (data) {
            data = $.parseJSON(data);
            materialGetCallbacks.fire(type, 'success', data);
        },
        error: function () {
            materialGetCallbacks.fire(type, 'error');
        }
    });
}

function _renderpage(curPage, total) {
    var pageSize = Math.ceil(total / 9);
    var i = 1;
    var strArr = [];
    curPage = Number(curPage);
    if (pageSize > 1) {
        strArr.push('<a href="javascript:;"  class="jfk-pagination__pages-trigger jfk-pagination__pages-button" data-type="prev"><</a>')
    }
    if(i+2<curPage){
        i= curPage - 2;
    }
    var k = 0;
    while (i <= pageSize && k<5) {
        strArr.push('<a href="javascript:;" data-page="' + i + '" class="jfk-pagination__pages-item jfk-pagination__pages-trigger ' + (curPage === i ? ' jfk-pagination__pages-item--active' : '') + '">' + i + '</a>');
        i++;
        k++;
    }
    if (pageSize > 1) {
        strArr.push('<a href="javascript:;"  class="jfk-pagination__pages-button jfk-pagination__pages-trigger " data-type="next">></a><i class="jfk-pagination__pages-text">第</i><span class="jfk-pagination__pages-input"><input type="text" data-total="' + pageSize + '"/></span><i class="jfk-pagination__pages-text">页</i><a href="javascript:;" class="jfk-pagination__pages-button jfk-pagination__pages-trigger " data-type="jump">GO</a>')
    }
    return strArr.join('');
}

function renderPage(options) {
    var cxt = options.cxt || $(body);
    var curPage = options.curPage || 1;
    var total = options.total || 0;
    var reset = options.reset || true;
    var callback = options.callback;
    var $page = cxt.find('.jfk-pagination');
    //重新渲染page
    if (!$page.length && total > 9) {
        var str = '<div class="jfk-pagination jfk-pagination--left"><div class="jfk-pagination__pages">' + _renderpage(curPage, total) + '</div></div>'
        $page = $(str).appendTo(cxt);
        $page.on('click', '.jfk-pagination__pages-trigger', function () {
            var $that = $(this);
            var type = $that.data('type');
            var page;
            if (!type && !$that.hasClass('jfk-pagination__pages-item--active')) {
                page = $that.data('page');
            }
            var $ipt = $page.find('.jfk-pagination__pages-input input');
            var max = Number($ipt.data('total'));
            if (type === 'jump') {
                var val = Number($ipt.val());
                if (val > 0) {
                    page = Math.round(val);
                    if (page > max) {
                        page = max;
                    }
                }
            }
            var $cur = $page.find(".jfk-pagination__pages-item--active");
            var curPage = Number($cur.data('page'));
            if (type === 'prev' && curPage !== 1) {
                page = curPage - 1;
            }
            if (type === 'next' && curPage !== max) {
                page = curPage + 1;
            }
            if (page) {
                callback(page);
            }
        })
    }
    if ($page) {
        if (total < 9) {
            $page.html('');
        } else if (reset) {
            var str = _renderpage(curPage, total);
            $page.find('.jfk-pagination__pages').html(str);
        } else {
            $page.find('.jfk-pagination__pages-item--active').removeClass('jfk-pagination__pages-item--active');
            $page.find('.jfk-pagination__pages-item[data-page="' + curPage + '"]').addClass('jfk-pagination__pages-item--active');
        }
    }
}
function renderNews(data, noLabel) {
    var strArr = [];
    var showImgs = [];
    strArr.push('<div class="jfk-media-box__head"><h4 class="jfk-media-box__title">' + getLocalTime(data.update_time) + ' 更新</h4></div><div class="jfk-media-box__body"><div class="jfk-media-box__thumb"><a style="position:absolute;top:0px;left:0px;width:100%;height:100%;z-index:999;" href="'+ data.content.news_item[0].url +'" target="_blank"></a><div class="jfk-img__iframe" id="' + data.media_id + '0' + '"></div><h5 class="jfk-media-box__thumb-desc">' + data.content.news_item[0].title + '</h5></div><div class="jfk-media-box__desc jfk-cells">');
    showImgs.push({ url: data.content.news_item[0].thumb_url, id: data.media_id + '0' });
    for (var j = 1; j < data.content.news_item.length; j++) {
        strArr.push('<a href="'+data.content.news_item[j].url+'" target="_blank"><div class="jfk-cell"><div class="jfk-cell__bd">' + data.content.news_item[j].title + '</div><div id="' + data.media_id + j + '" class="jfk-cell__hd jfk-img__iframe"></div></div></a>');
        showImgs.push({ url: data.content.news_item[j].thumb_url, id: data.media_id + j });
    };
    strArr.push('</div></div>')
    if (!noLabel) {
        strArr.push('<label><input type="radio" name="jfk-layer__choice-radio" value="' + data.media_id + '"><div class="media-item__status"></div></label>');
    }
    return {
        str: strArr.join(''),
        showImgs: showImgs,
        mediaId: data.media_id
    }
}
function renderImage(data, noLable) {
    var str = '<div class="jfk-media-box__body"><div class="jfk-media-box__thumb"><div class="jfk-img__iframe" id="' + data.media_id + '"></div></div></div><div class="jfk-media-box__control"><div class="jfk-cell__bd">' + data.name + '</div></div>';
    if (!noLable) {
        str += '<label><input type="radio" data-url="' + data.url + '" name="media-item" value="' + data.media_id + '"/><div class="media-item__status"></div></label>';
    }
    return {
        str: str,
        mediaId: data.media_id,
        showImgs: {
            url: data.url,
            id: data.media_id
        }
    }
}

function changeTab(select, callback) {
    var $tab = $(select);
    var $titles = $tab.find('.jfk-tab__title-item');
    var $bodys = $tab.find('.jfk-tab__body-item');
    $tab.on('click', '.jfk-tab__title-item', function () {
        var $that = $(this);
        var type = $that.data('type');
        if (!$that.hasClass('jfk-tab__title-item--selected')) {
            $titles.filter('.jfk-tab__title-item--selected').removeClass('jfk-tab__title-item--selected');
            $that.addClass('jfk-tab__title-item--selected');
            $bodys.filter('.jfk-tab__body-item--selected').removeClass('jfk-tab__body-item--selected');
            $bodys.filter('[data-type="' + type + '"]').addClass('jfk-tab__body-item--selected');
            callback(type);
        }
    })
}

function getMaterialCallback(type, status, data) {
    var $loading = $('.loading-' + type);
    var $list = $loading.prev();
    var $box = $loading.parents('.jfk-material-box');
    var f1 = window.renderNewsCustom || renderNews;
    var f2 = window.renderImageCustom || renderImage;
    if (status === 'beforeSend') {
        $loading.removeClass('Ldn');
        $list.hide();
    } else {
        $loading.addClass('Ldn');
        var htmlArr1 = [];
        var htmlArr2 = [];
        var htmlArr3 = [];
        var showImgs = [];
        var isNews = type === 'news'
        if (data.item) {
            var fn = isNews ? f1 : f2;
            $.each(data.item, function (i, _data) {
                var html = fn(_data);
                (i % 3 === 1 ? htmlArr2 : i % 3 === 2 ? htmlArr3 : htmlArr1).push('<div class="jfk-list__item-box">' + html.str + '</div>');
                showImgs = showImgs.concat(html.showImgs)
            });
        }
        var k = 0;
        if (htmlArr1.length) {
            k++
            htmlArr1.unshift('<li class="jfk-list__item jfk-media-box jfk-media-box__material' + (isNews ? '  jfk-media-box__iframe-img' : '') + '">');
            htmlArr1.push('</li>')
        }
        if (htmlArr2.length) {
            k++
            htmlArr2.unshift('<li class="jfk-list__item jfk-media-box jfk-media-box__material' + (isNews ? '  jfk-media-box__iframe-img' : '') + '">');
            htmlArr2.push('</li>')
        }
        if (htmlArr3.length) {
            k++
            htmlArr3.unshift('<li class="jfk-list__item jfk-media-box jfk-media-box__material' + (isNews ? '  jfk-media-box__iframe-img' : '') + '">');
            htmlArr3.push('</li>')
        }
        if (!k) {
            $list.html('暂无相关素材')
        } else {
            $list.html(htmlArr1.join('') + htmlArr2.join('') + htmlArr3.join(''));
            $.each(showImgs, function (idx, prop) {
                showImg(prop.url, prop.id);
            });
            renderPage({
                curPage: data.page, total: data.total_count, cxt: $box, callback: function (page) {
                    return getMaterialData(type, page, materialUrl);
                }, reset: window.resetPage
            });
        }
        $list.show();
    }
}

function ueditor(option){
    this.initUEditor(option);
}

ueditor.prototype = {
    initUEditor: function(options){
        var e = [];
        var n=["undo","redo","|","fontsize","|","blockquote","horizontal","|","removeformat","formatmatch"];
        var s=["bold","italic","underline","forecolor","backcolor","|","indent","|","justifyleft","justifycenter","justifyright","justifyjustify","|","rowspacingtop","rowspacingbottom","lineheight","|","insertorderedlist","insertunorderedlist","|","imagenone","imageleft","imageright","imagecenter"];
        e.push()
    }
}
