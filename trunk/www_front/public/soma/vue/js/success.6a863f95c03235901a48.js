webpackJsonp([14],{167:function(t,e,s){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var n=s(8),i=a(n),o=s(441),r=a(o);e.default=function(){new i.default({el:"#app",data:{name:"购买成功"},template:"<App/>",components:{App:r.default}})}},173:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={name:"deadline",props:["headTitleMsg"]}},177:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s(173),n=s.n(a),i=s(178),o=s(26),r=o(n.a,i.a,null,null,null);e.default=r.exports},178:function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement;return(t._self._c||e)("div",{staticClass:"headtitle_tips"},[t._v("\n    "+t._s(t.headTitleMsg)+"\n")])},n=[],i={render:a,staticRenderFns:n};e.a=i},197:function(t,e,s){t.exports=s.p+"soma/vue/img/shengguang.2338fd8.png"},385:function(t,e,s){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var n=s(28),i=a(n),o=s(177),r=a(o),c=s(27);e.default={name:"success",components:{headTitle:r.default},data:function(){return{headTitleMsg:"",recommendations:[],visible:!1,qrCode:"",productDetail:"",orderDetail:""}},beforeCreate:function(){this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0});var t=(0,i.default)(location.href);this.oid=t.oid,this.$pageNamespace(t)},created:function(){var t=this;(0,c.getSuccessPay)({oid:this.oid}).then(function(e){t.toast.close();var s=e.web_data;t.productDetail=s.page_resource.link.product_detail+s.product_id,t.orderDetail=s.page_resource.link.order_detail,t.headTitleMsg="本商品由"+s.hotel_name+"提供",0===s.subscribe_status&&(t.qrCode=s.qr_code,t.visible=!0)}),(0,c.getPackageRecommendation)({page:1,page_size:100}).then(function(e){var s=e.web_data,a=s.products,n=s.page_resource;t.recommendations=a;var i=n.link,o=i.detail,r=i.home;t.detailUrl=o,t.indexUrl=r})},methods:{handleQrcode:function(){this.visible=!0}}}},441:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s(385),n=s.n(a),i=s(485),o=s(26),r=o(n.a,i.a,null,null,null);e.default=r.exports},485:function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-pages jfk-pages__success"},[s("div",{staticClass:"jfk-pages__theme"}),t._v(" "),t._m(0),t._v(" "),s("headTitle",{attrs:{headTitleMsg:t.headTitleMsg}}),t._v(" "),s("div",{staticClass:"success_main color-golden"},[t._m(1),t._v(" "),t._m(2),t._v(" "),s("div",{staticClass:"actionbtns"},[s("a",{staticClass:"jfk-button jfk-button--primary is-plain font-size--30 product-button jfk-button--lower",attrs:{href:t.productDetail}},[s("span",[t._v("\n            再次购买\n          ")])]),t._v(" "),s("a",{staticClass:"jfk-button jfk-button--primary font-size--30 product-button jfk-button--lower",attrs:{href:t.orderDetail}},[s("span",[t._v("\n           查看订单\n          ")])])])]),t._v(" "),t.recommendations.length?s("div",{staticClass:"recommendation jfk-pl-30"},[s("p",{staticClass:"font-size--24 font-color-light-gray-common tip"},[t._v("其他用户还看了")]),t._v(" "),s("div",{staticClass:"recommendations-list ",class:{"jfk-pr-30":1==t.recommendations.length}},[s("jfk-recommendation",{attrs:{items:t.recommendations,linkPrefix:t.detailUrl,emptyLink:t.indexUrl}})],1)]):t._e(),t._v(" "),s("jfk-popup",{staticClass:"jfk-ta-c success_qrcode",attrs:{showCloseButton:!0},model:{value:t.visible,callback:function(e){t.visible=e},expression:"visible"}},[s("img",{attrs:{src:t.qrCode}}),t._v(" "),s("p",{staticClass:"font-color-extra-light-gray font-size--28 content"},[t._v("你还未关注公众号\n      "),s("br"),t._v("长按识别关注随时查看订单")])]),t._v(" "),t._m(3)],1)},n=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"shengguang"},[a("img",{attrs:{src:s(197)}})])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"gou"},[s("span",{staticClass:"mainbox"},[s("i",{staticClass:"jfk-font icon-radio_icon_selected_default"})])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"wenan font-size--46 "},[s("i",{staticClass:"jfk-font icon-font_zh_gong_1_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font icon-font_zh_xi_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font icon-font_zh_ni_qkbys",staticStyle:{"margin-right":"10px"}}),t._v(" "),s("i",{staticClass:"jfk-font icon-font_zh_gou_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font icon-font_zh_mai_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font icon-font_zh_cheng_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font icon-font_zh_gong_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font icon-font_zh_emark_qkbys"})])},function(){var t=this,e=t.$createElement;return(t._self._c||e)("JfkSupport")}],i={render:a,staticRenderFns:n};e.a=i}});