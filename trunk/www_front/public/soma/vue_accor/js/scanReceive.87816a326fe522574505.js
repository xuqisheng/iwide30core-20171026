webpackJsonp([9],{158:function(t,e,s){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var a=s(8),n=i(a),o=s(430),c=i(o);e.default=function(){new n.default({el:"#app",template:"<App/>",components:{App:c.default}})}},184:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:{title:{type:String,default:""},list:{type:Array,default:function(){return[]}},onlyShowTitle:{type:Boolean,default:!1}}}},188:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:{status:{type:Boolean,default:!1},info:{type:Object,default:function(){return{}}}},methods:{changeStatus:function(){this.$emit("changeStatus")}}}},193:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=s(184),a=s.n(i),n=s(196),o=s(25),c=o(a.a,n.a,null,null,null);e.default=c.exports},195:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=s(188),a=s.n(i),n=s(197),o=s(25),c=o(a.a,n.a,null,null,null);e.default=c.exports},196:function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{staticClass:"jfk-clause jfk-ta-c jfk-pl-30 jfk-pr-30"},[s("div",{staticClass:"jfk-clause__title font-size--28"},[s("span",{domProps:{textContent:t._s(t.title)}})])]),t._v(" "),t._l(t.list,function(e,i){return t.onlyShowTitle?t._e():s("ul",{key:i,staticClass:"jfk-clause__list"},[s("li",{staticClass:"font-size--24",domProps:{textContent:t._s(e)}})])})],2)},a=[],n={render:i,staticRenderFns:a};e.a=n},197:function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-package-info jfk-pl-30 jfk-pr-30"},[s("div",{staticClass:"jfk-package-info__content"},[s("div",{staticClass:"jfk-package-info__base-info"},[t._m(0),t._v(" "),s("div",{staticClass:"jfk-package-info__base-info--right"},[s("div",{staticClass:"jfk-package-info__base-info--content"},[t.info.name?s("p",{staticClass:"name font-size--32",domProps:{textContent:t._s(t.info.name)}}):t._e(),t._v(" "),t.info.time?s("p",{staticClass:"validity font-size--24",domProps:{textContent:t._s("有效期至"+t.info.time)}}):t._e(),t._v(" "),t.info&&t.info.products&&t.info.products.length>0?s("p",{staticClass:"more",on:{click:t.changeStatus}},[s("span",{staticClass:"font-size--24"},[t._v("详情")]),t._v(" "),s("span",{staticClass:"font-size--24 icon"},[t.status?t._e():s("i",{staticClass:"jfk-font icon-booking_icon_DN_norm"}),t._v(" "),t.status?s("i",{staticClass:"jfk-font icon-booking_icon_up_normal"}):t._e()])]):t._e()])])]),t._v(" "),t.status?s("ul",{staticClass:"jfk-package-info__more-info"},t._l(t.info.products,function(e,i){return t.info&&t.info.products&&t.info.products.length>0?s("li",{key:i,staticClass:"jfk-flex"},[s("span",{staticClass:"jfk-ta-l font-size--28",domProps:{textContent:t._s(e.name)}},[t._v("自助餐")]),t._v(" "),s("span",{staticClass:"jfk-ta-r font-size--28",domProps:{textContent:t._s(e.num)}})]):t._e()})):t._e()])])},a=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-package-info__base-info--left"},[s("div",{staticClass:"jfk-package-info__base-info--title jfk-flex is-align-middle is-justify-center"},[s("i",{staticClass:"jfk-font  icon-font_zh_li_1_qkbys"})])])}],n={render:i,staticRenderFns:a};e.a=n},374:function(t,e,s){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var a=s(193),n=i(a),o=s(195),c=i(o),r=s(26),l=s(56),f=s(27),_=f.default(location.href),u=l.v1.GET_GENERATE_GIFT_QRCODE;e.default={components:{clause:n.default,pack:c.default},computed:{size:function(){return{width:window.innerWidth*(443/750)+"px",height:window.innerWidth*(443/750)+"px"}}},methods:{generate:function(){window.history.go(-1)},changeStatus:function(){this.status=!this.status}},created:function(){var t=this;this.$pageNamespace(_),this.qrcode=window.location.host+u+"?gift_detail_id="+_.gift_detail_id+"&inter_id="+_.inter_id+"&request_token="+_.request_token,-1===this.qrcode.indexOf("http://")&&(this.qrcode="http://"+this.qrcode),this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),(0,r.getGiftPackageDetail)({gift_detail_id:_.gift_detail_id||"",inter_id:_.inter_id||"",request_token:_.request_token}).then(function(e){var s=e.web_data,i="";s.expiration_date&&(i="该商品有效期至"+s.expiration_date);var a="";s.price_market&&(a="礼包原价"+e.web_data.price_market+"元"),t.notice=[i,"请在规定时间内使用",a,"仅供住店客人使用，使用时请出示劵码"],t.info={name:s.name||"",time:s.expiration_date||"",products:s.child_product_info||[]},t.toast.close()})},data:function(){return{info:{},status:!1,notice:[],qrcode:""}}}},430:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=s(374),a=s.n(i),n=s(456),o=s(25),c=o(a.a,n.a,null,null,null);e.default=c.exports},456:function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-pages jfk-pages__scan-receive"},[s("div",{staticClass:"jfk-pages__theme"}),t._v(" "),s("div",{staticClass:"jfk-pl-30 jfk-pr-30 qrcode-wrap"},[s("div",{staticClass:"scan-receive__container"},[s("div",{staticClass:"scan-receive__qrcode jfk-image__lazy--preload  jfk-image__lazy--3-3 jfk-image__lazy--background-image",style:t.size},[t.qrcode?s("img",{attrs:{src:t.qrcode}}):t._e()]),t._v(" "),t._m(0),t._v(" "),s("div",{staticClass:"scan-receive__btn"},[s("button",{staticClass:"jfk-button jfk-button--primary is-special jfk-button--free font-size--32",on:{click:t.generate}},[s("span",[t._v("重新生成")])])])])]),t._v(" "),t.notice.length>0?s("div",{staticClass:"scan-receive__notice"},[s("clause",{attrs:{title:"注意事项",list:t.notice}})],1):t._e(),t._v(" "),s("div",{staticClass:"scan-receive__package"},[s("clause",{attrs:{title:"礼包内容"}}),t._v(" "),s("pack",{attrs:{status:t.status,info:t.info},on:{changeStatus:t.changeStatus}})],1)])},a=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"scan-receive__way font-size--24 jfk-ta-c"},[s("span",[t._v("使用方式")]),s("i",[t._v("（请在5分钟完成扫码，超时未领取请重新生成）")])])}],n={render:i,staticRenderFns:a};e.a=n}});