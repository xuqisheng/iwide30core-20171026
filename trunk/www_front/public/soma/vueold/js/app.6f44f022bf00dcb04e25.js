webpackJsonp([1],{31:function(t,s,i){"use strict";s.a={name:"app"}},32:function(t,s,i){"use strict";var a=i(1),o=i(12),e=i.n(o);a.default.use(e.a);var n={1:"专属价",2:"秒杀价",3:"拼团价",4:"满减价",5:"组合价",6:"储值价",7:"积分价"};s.a={name:"rewardList",data:function(){return{msg:"Welcome to Your Vue.js App",page:1,sort:1,sortMsg:{1:"销量从高到低",2:"奖励从高到低"},showSelectlist:!1,goodsData:[],showPopWindow:!1,popQrcode:"",disableLoadProduct:!1,showFullLoading:!0,isLoadProduct:!1,noGoods:!1,popHotelname:"",mainColor:"",pricetag:n,attachQrcode:"",shownogoodsContent:!0}},beforeCreate:function(){this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0})},created:function(){},methods:{loadMore:function(){this.disableLoadProduct=!0,this.loadPages()},loadPages:function(t){var s=this;this.isLoadProduct=!0;var i={page:this.page,sort:this.sort};e.a.get("/index.php/iapi/soma/package/distribute_products",{params:i}).then(function(i){s.isLoadProduct=!1,s.toast.close();var a=i.data.web_data,o=a.page_resource,e=a.product_info,n=a.attach,c=o.count,l=o.page,d=o.size;1e3===i.data.status&&(s.goodsData=t?e:s.goodsData.concat(e),s.goodsData.length||(s.noGoods=!0,s.attachQrcode=n.qrcode_index),s.showFullLoading=!1),s.disableLoadProduct=l*d>=c,s.disableLoadProduct||(s.page=+l+1)}).catch(function(t){alert(t)})},handleFilter:function(){this.showSelectlist?this.showSelectlist&&(this.showSelectlist=!1):this.showSelectlist=!0},handleSelectbtn:function(t){parseInt(t)!==parseInt(this.sort)&&(this.sort=t,this.disableLoadProduct=!0,this.goodsData=[],this.page=1,this.showSelectlist=!1,this.loadPages(!0))},handleQrcode:function(t,s){this.popQrcode=t,this.popHotelname=s,this.showPopWindow=!0,this.noGoods&&(this.shownogoodsContent=!1)},handleClick:function(){this.noGoods&&(this.shownogoodsContent=!0),this.showPopWindow=!1}},mounted:function(){}}},33:function(t,s){},34:function(t,s){},35:function(t,s){},36:function(t,s){},37:function(t,s){},38:function(t,s){},43:function(t,s){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAa8AAABPCAYAAABcWEo3AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA39pVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo0M2ZiY2Y0OC0yMmY2LTRlNzAtODJmNC05MWQ3ZGUyMmNkYTkiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MjVFRjAxRjI4QTcyMTFFNzlCMjQ4RDFCQUM0N0I3QkIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MjVFRjAxRjE4QTcyMTFFNzlCMjQ4RDFCQUM0N0I3QkIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjhhZTdlNTA1LTBmYTAtMGE0Mi1hZjk2LTUzYzIzM2RkZjkyOSIgc3RSZWY6ZG9jdW1lbnRJRD0iYWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOmQxZTFmY2Y5LWM5MWUtMTE3YS04M2NhLWI5YzBl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"},44:function(t,s,i){"use strict";function a(t){i(37)}var o=i(31),e=i(46),n=i(9),c=a,l=n(o.a,e.a,c,null,null);s.a=l.exports},45:function(t,s,i){"use strict";function a(t){i(38)}var o=i(32),e=i(47),n=i(9),c=a,l=n(o.a,e.a,c,"data-v-cee3c344",null);s.a=l.exports},46:function(t,s,i){"use strict";var a=function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{attrs:{id:"app"}},[i("router-view")],1)},o=[],e={render:a,staticRenderFns:o};s.a=e},47:function(t,s,i){"use strict";var a=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"rewardList"},[a("div",{staticClass:"filter-menu-box",class:{showgraybg:t.showSelectlist},on:{click:function(s){t.handleFilter()}}},[a("div",{staticClass:"filter-menu Ltac font-size--26",class:{color_main:t.showSelectlist}},[t._v("\n        "+t._s(t.sortMsg[t.sort])+"\n          "),a("i",{staticClass:"iconfont icon-home_icon_Jump_norma arrow",class:{color_main:t.showSelectlist}})]),t._v(" "),t.showSelectlist?a("div",{staticClass:"selectlist "},t._l(t.sortMsg,function(s,i){return a("div",{staticClass:"item font-size--26",class:{color_main:t.sort==i},on:{click:function(s){s.stopPropagation(),t.handleSelectbtn(i)}}},[t._v(t._s(s)),a("span",{staticClass:"selectBtn ",class:{bg_main:t.sort==i}},[t.sort==i?a("i",{staticClass:"iconfont icon-duigou"}):t._e()])])})):t._e()]),t._v(" "),a("div",{directives:[{name:"infinite-scroll",rawName:"v-infinite-scroll",value:t.loadMore,expression:"loadMore"}],staticClass:"list-box",attrs:{"infinite-scroll-distance":"10","infinite-scroll-disabled":"disableLoadProduct"}},[t._l(t.goodsData,function(s){return a("div",{staticClass:"item"},[a("div",{staticClass:"goods-info"},[a("a",{attrs:{href:s.detail}},[a("img",{attrs:{src:s.face_img}}),t._v(" "),a("div",{staticClass:"intro"},[a("h3",{staticClass:"font-size--26"},[t._v(t._s(s.name))]),t._v(" "),a("p",{staticClass:"saleinfo font-size--24"},[t._v("已售"),a("span",{staticClass:"num"},[t._v(t._s(s.sales_cnt))]),a("span",{staticClass:"price"},[t._v(t._s(t.pricetag[s.tag]?t.pricetag[s.tag]:"惊喜价")+"：￥"+t._s(s.price_package))])])])])]),t._v(" "),a("div",{staticClass:"buy-info font-size--26"},[a("div",{staticClass:"rewardNum"},[t._v("奖励金额"),2==s.reward_type?a("span",[t._v(t._s(100*s.reward_percent+"%"))]):t._e(),2==s.reward_type?a("span",[t._v("("+t._s(s.reward_money)+")")]):a("span",[t._v(t._s(s.reward_money))])]),t._v(" "),a("div",{staticClass:"buybtn bg_main",on:{click:function(i){t.handleQrcode(s.qrcode_detail,s.name)}}},[a("i",{staticClass:"iconfont icon-mall_icon_pay_focus"}),a("span",[t._v("面对面购买")])])])])}),t._v(" "),a("p",{directives:[{name:"show",rawName:"v-show",value:!t.showFullLoading&&t.isLoadProduct,expression:"!showFullLoading && isLoadProduct"}],staticClass:"products-list__loading color_main"},[t._m(0)])],2),t._v(" "),t.showPopWindow?a("div",{staticClass:"popWindow"},[a("div",{staticClass:"mainbox"},[a("div",{staticClass:"cardmain"},[a("div",{staticClass:"close",on:{click:function(s){t.handleClick()}}},[a("i",{staticClass:"iconfont icon-icon_close "})]),t._v(" "),a("h2",{staticClass:"font-size--30"},[t._v(t._s(t.popHotelname))]),t._v(" "),a("img",{staticClass:"codetitle",attrs:{src:i(43)}}),t._v(" "),a("div",{staticStyle:{"min-height":"277px","margin-top":"0px"}},[a("img",{staticClass:"codeimg",attrs:{src:t.popQrcode}})])])])]):t._e(),t._v(" "),t.noGoods?a("div",{staticClass:"none "},[t.shownogoodsContent?a("div",{staticClass:"main"},[a("i",{staticClass:"iconfont icon-mall_icon_reward font-size--120"}),t._v(" "),a("p",{staticClass:"tip1 font-size--28"},[t._v("暂无奖励商品")]),t._v(" "),t._m(1),t._v(" "),a("div",{staticClass:"buybtn bg_main font-size--28",on:{click:function(s){t.handleQrcode(t.attachQrcode)}}},[t._v("生成二维码")])]):t._e()]):t._e()])},o=[function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("span",{staticClass:"jfk-loading__triple-bounce color-golden font-size--24"},[i("i",{staticClass:"jfk-loading__triple-bounce-item"}),t._v(" "),i("i",{staticClass:"jfk-loading__triple-bounce-item"}),t._v(" "),i("i",{staticClass:"jfk-loading__triple-bounce-item"})])},function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("p",{staticClass:"tip2 font-size--24"},[t._v("点击生成二维码，客人扫码购买后"),i("br"),t._v("\n也将记录您的推荐信息")])}],e={render:a,staticRenderFns:o};s.a=e}},[50]);