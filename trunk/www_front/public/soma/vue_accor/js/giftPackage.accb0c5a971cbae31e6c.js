webpackJsonp([10],{144:function(t,e,i){"use strict";function s(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var a=i(8),n=s(a),o=i(408),c=s(o);e.default=function(){new n.default({el:"#app",template:"<App/>",components:{App:c.default}})}},185:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:{title:{type:String,default:""},list:{type:Array,default:function(){return[]}},onlyShowTitle:{type:Boolean,default:!1}}}},189:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:{status:{type:Boolean,default:!1},info:{type:Object,default:function(){return{}}}},methods:{changeStatus:function(){this.$emit("changeStatus")}}}},194:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=i(185),a=i.n(s),n=i(197),o=i(26),c=o(a.a,n.a,null,null,null);e.default=c.exports},196:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=i(189),a=i.n(s),n=i(198),o=i(26),c=o(a.a,n.a,null,null,null);e.default=c.exports},197:function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("div",{staticClass:"jfk-clause jfk-ta-c jfk-pl-30 jfk-pr-30"},[i("div",{staticClass:"jfk-clause__title font-size--28"},[i("span",{domProps:{textContent:t._s(t.title)}})])]),t._v(" "),t._l(t.list,function(e,s){return t.onlyShowTitle?t._e():i("ul",{key:s,staticClass:"jfk-clause__list"},[i("li",{staticClass:"font-size--24",domProps:{textContent:t._s(e)}})])})],2)},a=[],n={render:s,staticRenderFns:a};e.a=n},198:function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"jfk-package-info jfk-pl-30 jfk-pr-30"},[i("div",{staticClass:"jfk-package-info__content"},[i("div",{staticClass:"jfk-package-info__base-info"},[t._m(0),t._v(" "),i("div",{staticClass:"jfk-package-info__base-info--right"},[i("div",{staticClass:"jfk-package-info__base-info--content"},[t.info.name?i("p",{staticClass:"name font-size--32",domProps:{textContent:t._s(t.info.name)}}):t._e(),t._v(" "),t.info.time?i("p",{staticClass:"validity font-size--24",domProps:{textContent:t._s("有效期至"+t.info.time)}}):t._e(),t._v(" "),t.info&&t.info.products&&t.info.products.length>0?i("p",{staticClass:"more",on:{click:t.changeStatus}},[i("span",{staticClass:"font-size--24"},[t._v("详情")]),t._v(" "),i("span",{staticClass:"font-size--24 icon"},[t.status?t._e():i("i",{staticClass:"jfk-font icon-booking_icon_DN_norm"}),t._v(" "),t.status?i("i",{staticClass:"jfk-font icon-booking_icon_up_normal"}):t._e()])]):t._e()])])]),t._v(" "),t.status?i("ul",{staticClass:"jfk-package-info__more-info"},t._l(t.info.products,function(e,s){return t.info&&t.info.products&&t.info.products.length>0?i("li",{key:s,staticClass:"jfk-flex"},[i("span",{staticClass:"jfk-ta-l font-size--28",domProps:{textContent:t._s(e.name)}},[t._v("自助餐")]),t._v(" "),i("span",{staticClass:"jfk-ta-r font-size--28",domProps:{textContent:t._s(e.num)}})]):t._e()})):t._e()])])},a=[function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"jfk-package-info__base-info--left"},[i("div",{staticClass:"jfk-package-info__base-info--title jfk-flex is-align-middle is-justify-center"},[i("i",{staticClass:"jfk-font  icon-font_zh_li_1_qkbys"})])])}],n={render:s,staticRenderFns:a};e.a=n},352:function(t,e,i){"use strict";function s(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var a=i(194),n=s(a),o=i(196),c=s(o),f=i(27),r=i(28),l=r.default(location.href);e.default={components:{clause:n.default,pack:c.default},computed:{},created:function(){var t=this;this.$pageNamespace(l),this.loading(),(0,f.getGiftPackageQrcodeDetail)({gift_detail_id:l.gift_detail_id||"",inter_id:l.inter_id||"",gift_id:l.gift_id||"",saler_id:l.saler_id||"",request_token:l.request_token}).then(function(e){var i=e.web_data;if(i.gift_record_info){var s="",a="";i.gift_record_info.record_info&&(s="登记信息："+i.gift_record_info.record_info),a=i.gift_record_info.orther_remark?"其他："+i.gift_record_info.orther_remark:"无",t.info=[s,a]}var n="",o="";i.expiration_date&&(n="该商品有效期至"+i.expiration_date),i.price_market&&(o="礼包原价"+i.price_market+"元"),t.notice=[n,"请在规定时间内使用",o,"仅供住店客人使用，使用时请出示劵码"],t.toast.close(),t.products={name:i.name||"",time:i.expiration_date||"",products:i.child_product_info||[]}}).catch(function(){t.toast.close()})},methods:{loading:function(){this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0})},receive:function(){var t=this;this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),(0,f.postGenerateGiftOrder)({gift_detail_id:l.gift_detail_id||"",inter_id:l.inter_id||"",request_token:l.request_token}).then(function(e){t.toast.close(),window.location.href=e.web_data.page_resource.link.gift_detail}).catch(function(){t.toast.close()})},changeStatus:function(){this.status=!this.status}},data:function(){return{status:!1,info:[],products:{},notice:[]}}}},408:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=i(352),a=i.n(s),n=i(450),o=i(26),c=o(a.a,n.a,null,null,null);e.default=c.exports},450:function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"jfk-pages jfk-pages__gift-package"},[i("div",{staticClass:"jfk-pages__theme"}),t._v(" "),t.products?i("pack",{attrs:{info:t.products,status:t.status},on:{changeStatus:t.changeStatus}}):t._e(),t._v(" "),i("div",{staticClass:"package-box"},[t.info.length>0?i("clause",{attrs:{title:"领取信息",list:t.info}}):t._e()],1),t._v(" "),i("div",{staticClass:"package-box"},[t.notice.length>0?i("clause",{attrs:{title:"注意事项",list:t.notice}}):t._e()],1),t._v(" "),i("div",{staticClass:"package-btn"},[i("button",{staticClass:"jfk-button jfk-button--primary is-special jfk-button--free font-size--32",on:{click:t.receive}},[i("span",[t._v("\n        立即领取\n      ")])])])],1)},a=[],n={render:s,staticRenderFns:a};e.a=n}});