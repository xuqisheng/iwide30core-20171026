webpackJsonp([17],{148:function(t,e,i){"use strict";function s(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var a=i(8),o=s(a),n=i(401),_=s(n);e.default=function(){new o.default({el:"#app",template:"<App/>",components:{App:_.default}})}},350:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=i(28);e.default={computed:{debug:function(){return!1}},methods:{changeTab:function(t){this.tabsItems.selected=t,this.operation="tab",this.orderList=[],this.getOrderData()},loadMore:function(){this.disableLoadList=!0,this.getOrderData()},setData:function(t){var e=this.cacheData[this.tabsItems.selected];if(this.operation="touch",4===e.type)e.more=!1,e.data=t.web_data.gift_info,this.disableLoadList=!1;else if(0===t.web_data.products.length)e.more=!1,this.disableLoadList=!1;else{for(var i=t.web_data.page_resource.link,s=0;s<t.web_data.products.length;s++){var a=t.web_data.products[s];a.order_detail_link=this.debug?"orderDetail?oid="+a.order_id:i.detail+a.order_id,a.order_product_link=this.debug?"detail?pid="+a.package[0].product_id:i.product_link+a.package[0].product_id,e.data.push(t.web_data.products[s]),a&&a.code&&a.code.use_num&&(a.code.use_num=parseInt(a.code.use_num))}this.disableLoadList=!1}this.orderList=e.data,this.$nextTick(function(){e.more&&(e.current+=1)})},getRenderData:function(){var t=this,e=this.cacheData[this.tabsItems.selected];if(this.disableLoadList=e.more,!1===e.more)return!1;if(4===parseInt(e.type))(0,s.getPresentsMineList)().then(function(e){t.setData(e)});else{var i={page:e.current,page_size:e.pageSize,type:e.type};(0,s.getOrderList)(i).then(function(e){t.setData(e)})}},getOrderData:function(){var t=this.cacheData[this.tabsItems.selected];"touch"===this.operation?(this.disableLoadList=!1,this.getRenderData()):"tab"===this.operation&&(this.disableLoadList=!1,this.operation="touch",t.more&&0===t.data.length?this.getRenderData():this.orderList=t.data)},locationHref:function(t){window.location.href=t},orderDelete:function(t,e){var i=this;this.$jfkConfirm("是否确定删除?").then(function(a){"confirm"===a&&(i.toast=i.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),(0,s.deleteOrder)(t).then(function(){i.disableLoadList=!1,i.orderList.splice(e,1),i.cacheData[i.tabsItems.selected].data=i.orderList,i.toast.close(),i.$jfkToast("删除成功"),0===i.cacheData[i.tabsItems.selected].data.length&&i.loadMore()}).catch(function(){i.toast.close()}))}).catch(function(){})},giftDelete:function(t,e){var i=this;this.$jfkConfirm("是否确定删除?").then(function(a){"confirm"===a&&(i.toast=i.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),(0,s.deletePresentsGiftOrder)(t).then(function(t){i.disableLoadList=!1,i.orderList.splice(e,1),i.cacheData[i.tabsItems.selected].data=i.orderList,i.toast.close(),i.$jfkToast("删除成功")}).catch(function(){i.toast.close()}))}).catch(function(){})}},data:function(){return{operation:"touch",tabsItems:{selected:1,list:[{text:"全部",menu_type:1},{text:"待使用",menu_type:2},{text:"已完成",menu_type:3},{text:"礼物",menu_type:4,icon:!0}]},disableLoadList:!1,orderList:[],cacheData:{1:{type:1,data:[],more:!0,pageSize:10,current:1},2:{type:2,data:[],more:!0,pageSize:10,current:1},3:{type:3,data:[],more:!0,pageSize:10,current:1},4:{type:4,data:[],more:!0,pageSize:10,current:1}}}}}},401:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=i(350),a=i.n(s),o=i(439),n=i(27),_=n(a.a,o.a,null,null,null);e.default=_.exports},439:function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"jfk-pages jfk-pages__order-center"},[i("div",{staticClass:"jfk-pages__theme"}),t._v(" "),i("ul",{staticClass:"order-center-tab jfk-flex is-align-middle jfk-pl-30 jfk-pr-30"},t._l(t.tabsItems.list,function(e,s){return i("li",{staticClass:"order-center-tab__item font-size--32 jfk-ta-c",class:{"order-center-tab__active":t.tabsItems.selected===e.menu_type},on:{click:function(i){t.changeTab(e.menu_type)}}},[e.icon?i("i",{staticClass:"jfk-d-ib jfk-font icon-user_icon_Polite_nor"}):t._e(),i("span",{domProps:{innerHTML:t._s(e.text)}})])})),t._v(" "),i("ul",{directives:[{name:"infinite-scroll",rawName:"v-infinite-scroll",value:t.loadMore,expression:"loadMore"}],staticClass:"order-list jfk-pr-30 jfk-pl-30",attrs:{"infinite-scroll-disabled":t.disableLoadList,"infinite-scroll-distance":0}},t._l(t.orderList,function(e,s){return i("li",{key:s},[4!==t.tabsItems.selected?["1"===e.package[0].expiration_status?[i("a",{staticClass:"order-list__disabled",attrs:{href:e.order_detail_link||"javascript:void(0)"}},[i("div",{staticClass:"jfk-flex is-align-middle order-list__order-info"},[i("div",{staticClass:"font-size--28 order-list__order-id",domProps:{innerHTML:t._s("订单号："+e.order_id)}}),t._v(" "),i("div",{staticClass:"font-size--30 jfk-ta-r order-list__status"},[t._v("已过期")])]),t._v(" "),i("div",{staticClass:"order-list__info jfk-flex"},[e.package[0].face_img?i("div",{directives:[{name:"lazy",rawName:"v-lazy:background-image",value:e.package[0].face_img,expression:"item.package[0].face_img",arg:"background-image"}],staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}):i("div",{staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}),t._v(" "),i("div",{staticClass:"order-list__base-info"},[i("p",{staticClass:"order-list__name font-size--38",domProps:{textContent:t._s(e.item_name)}}),t._v(" "),i("p",{staticClass:"order-list__number font-size--24",domProps:{innerHTML:t._s("<span>数量</span>"+e.row_qty)}})])])]),t._v(" "),i("div",{staticClass:"order-list__button jfk-ta-r"},[i("button",{staticClass:"jfk-button font-size--24 jfk-button--primary order-list__delete",on:{click:function(i){t.orderDelete(e.order_id,s)}}},[t._m(0,!0)]),t._v(" "),i("button",{staticClass:"jfk-button jfk-button--primary  font-size--30 is-plain",on:{click:function(i){t.locationHref(e.order_product_link)}}},[t._m(1,!0)])])]:"33"===e.refund_status?[i("a",{staticClass:"order-list__disabled",attrs:{href:e.order_detail_link||"javascript:void(0)"}},[i("div",{staticClass:"jfk-flex is-align-middle order-list__order-info"},[i("div",{staticClass:"font-size--28 order-list__order-id",domProps:{innerHTML:t._s("订单号："+e.order_id)}}),t._v(" "),i("div",{staticClass:"font-size--30 jfk-ta-r order-list__status"},[t._v("已退款")])]),t._v(" "),i("div",{staticClass:"order-list__info jfk-flex"},[e.package[0].face_img?i("div",{directives:[{name:"lazy",rawName:"v-lazy:background-image",value:e.package[0].face_img,expression:"item.package[0].face_img",arg:"background-image"}],staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}):i("div",{staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}),t._v(" "),i("div",{staticClass:"order-list__base-info"},[i("p",{staticClass:"order-list__name font-size--38",domProps:{textContent:t._s(e.item_name)}}),t._v(" "),i("p",{staticClass:"order-list__number font-size--24",domProps:{innerHTML:t._s("<span>数量</span>"+e.row_qty)}})])])]),t._v(" "),i("div",{staticClass:"order-list__button jfk-ta-r"},[i("button",{staticClass:"jfk-button jfk-button--primary  font-size--30 is-plain",on:{click:function(i){t.locationHref(e.order_product_link)}}},[t._m(2,!0)])])]:"21"===e.consume_status||"22"===e.consume_status?[i("a",{attrs:{href:e.order_detail_link||"javascript:void(0)"}},[i("div",{staticClass:"jfk-flex is-align-middle order-list__order-info"},[i("div",{staticClass:"font-size--28 order-list__order-id",domProps:{innerHTML:t._s("订单号："+e.order_id)}}),t._v(" "),i("div",{staticClass:"font-size--30 jfk-ta-r order-list__status"},[t._v("购买成功")])]),t._v(" "),i("div",{staticClass:"order-list__info jfk-flex"},[e.package[0].face_img?i("div",{directives:[{name:"lazy",rawName:"v-lazy:background-image",value:e.package[0].face_img,expression:"item.package[0].face_img",arg:"background-image"}],staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}):i("div",{staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}),t._v(" "),i("div",{staticClass:"order-list__base-info"},[i("p",{staticClass:"order-list__name font-size--38",domProps:{textContent:t._s(e.item_name)}}),t._v(" "),i("p",{staticClass:"order-list__number font-size--24",domProps:{innerHTML:t._s("<span>数量</span>"+e.row_qty)}})])])]),t._v(" "),i("div",{staticClass:"order-list__button jfk-ta-r"},[e.code&&e.code.use_num&&e.code.use_num>0?[i("button",{staticClass:"jfk-button jfk-button--primary  font-size--30",on:{click:function(i){t.locationHref(e.order_detail_link)}}},[t._m(3,!0)])]:[i("button",{staticClass:"jfk-button jfk-button--primary  font-size--30",on:{click:function(i){t.locationHref(e.order_detail_link)}}},[t._m(4,!0)])]],2)]:"23"===e.consume_status?[i("a",{staticClass:"order-list__disabled",attrs:{href:e.order_detail_link||"javascript:void(0)"}},[i("div",{staticClass:"jfk-flex is-align-middle order-list__order-info"},[i("div",{staticClass:"font-size--28 order-list__order-id",domProps:{innerHTML:t._s("订单号："+e.order_id)}}),t._v(" "),i("div",{staticClass:"font-size--30 jfk-ta-r order-list__status"},[t._v("已完成")])]),t._v(" "),i("div",{staticClass:"order-list__info jfk-flex"},[e.package[0].face_img?i("div",{directives:[{name:"lazy",rawName:"v-lazy:background-image",value:e.package[0].face_img,expression:"item.package[0].face_img",arg:"background-image"}],staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}):i("div",{staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}),t._v(" "),i("div",{staticClass:"order-list__base-info"},[i("p",{staticClass:"order-list__name font-size--38",domProps:{textContent:t._s(e.item_name)}}),t._v(" "),i("p",{staticClass:"order-list__number font-size--24",domProps:{innerHTML:t._s("<span>数量</span>"+e.row_qty)}})])])]),t._v(" "),i("div",{staticClass:"order-list__button jfk-ta-r"},[i("button",{staticClass:"jfk-button font-size--24 jfk-button--primary order-list__delete",on:{click:function(i){t.orderDelete(e.order_id,s)}}},[t._m(5,!0)]),t._v(" "),i("button",{staticClass:"jfk-button jfk-button--primary  font-size--30 is-plain",on:{click:function(i){t.locationHref(e.order_product_link)}}},[t._m(6,!0)])])]:t._e()]:[i("a",{attrs:{href:e.detail_url||"javascript:void(0)"}},[i("div",{staticClass:"jfk-flex is-align-middle order-list__order-info"},[i("div",{staticClass:"font-size--28 order-list__order-id",domProps:{innerHTML:t._s("赠送编号："+e.gift_id)}}),t._v(" "),t._m(7,!0)]),t._v(" "),i("div",{staticClass:"order-list__info jfk-flex"},[e.face_img?i("div",{directives:[{name:"lazy",rawName:"v-lazy:background-image",value:e.face_img,expression:"item.face_img",arg:"background-image"}],staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}):i("div",{staticClass:"order-list__goods-img jfk-image__lazy--3-3 jfk-image__lazy--background-image"}),t._v(" "),i("div",{staticClass:"order-list__base-info"},[i("p",{staticClass:"order-list__name font-size--38",domProps:{textContent:t._s(e.name)}}),t._v(" "),i("p",{staticClass:"order-list__number font-size--24",domProps:{innerHTML:t._s("<span>数量</span>"+e.per_give)}})])])]),t._v(" "),1===e.consume_status?i("div",{staticClass:"order-list__button jfk-ta-r"},[i("button",{staticClass:"jfk-button jfk-button--primary  font-size--30 is-plain",on:{click:function(i){t.locationHref(e.detail_url)}}},[t._m(8,!0)])]):2===e.consume_status?i("div",{staticClass:"order-list__button jfk-ta-r"},[i("button",{staticClass:"jfk-button font-size--24 jfk-button--primary order-list__delete",on:{click:function(i){t.giftDelete(e.gift_id,s)}}},[t._m(9,!0)]),t._v(" "),i("button",{staticClass:"jfk-button jfk-button--primary  font-size--30 is-plain",on:{click:function(i){t.locationHref(e.detail_url)}}},[t._m(10,!0)])]):t._e()]],2)})),t._v(" "),t.disableLoadList?i("div",{staticClass:"order-list__loading font-size--24 jfk-ta-c"},[t._m(11)]):t._e(),t._v(" "),t.orderList.length>0?i("jfk-support"):t._e(),t._v(" "),!1===t.disableLoadList&&0===t.orderList.length?[t._m(12)]:t._e()],2)},a=[function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_shan_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_chu_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_zai__qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ci_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_gou_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_mai_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_zai__qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ci_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_gou_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_mai_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_li_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ji_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_shi_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_yong_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_li_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ji_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_cha_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_kan_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_shan_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_chu_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_zai__qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ci_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_gou_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_mai_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"font-size--30 jfk-ta-r order-list__status"},[i("i",{staticClass:"jfk-font icon-user_icon_Polite_nor"}),t._v(" "),i("span",[t._v("收礼成功")])])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_dian_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ji__qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_shi_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_yong_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_shan_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_chu_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-button__text"},[i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_li_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ji_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_cha_qkbys"}),t._v(" "),i("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_kan_qkbys"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("span",{staticClass:"jfk-loading__triple-bounce color-golden font-size--24"},[i("i",{staticClass:"jfk-loading__triple-bounce-item"}),t._v(" "),i("i",{staticClass:"jfk-loading__triple-bounce-item"}),t._v(" "),i("i",{staticClass:"jfk-loading__triple-bounce-item"})])},function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"jfk-ta-c order-center__null"},[i("div",{staticClass:"order-center__null-content"},[i("div",{staticClass:"jfk-font icon-blankpage_icon_noorder_bg"}),t._v(" "),i("p",{staticClass:"jfk-ta-c font-size--28"},[t._v("暂无相关订单~")])])])}],o={render:s,staticRenderFns:a};e.a=o}});