webpackJsonp([15],{137:function(t,o,e){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(o,"__esModule",{value:!0});var s=e(52),n=i(s),a=e(237),c=i(a);o.default=function(){new n.default({el:"#app",template:"<App/>",components:{App:c.default}})}},197:function(t,o,e){"use strict";Object.defineProperty(o,"__esModule",{value:!0});var i=e(28),s=e(53),n=s.default(location.href);o.default={created:function(){var t=this;this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),(0,i.getOrderPackageBooking)({aiid:n.aiid||"",code_id:n.code_id||""}).then(function(o){t.product=o.web_data.product,t.code=o.web_data.code.code,t.toast.close()}).catch(function(){t.toast.close()})},data:function(){return{message:"客房预约，您的订单将不能退款",product:{},code:""}},components:{}}},237:function(t,o,e){var i=e(27)(e(197),e(265),null,null);t.exports=i.exports},265:function(t,o){t.exports={render:function(){var t=this,o=t.$createElement,e=t._self._c||o;return e("div",{staticClass:"jfk-pages jfk-pages__booking"},[e("div",{staticClass:"jfk-pages__theme"}),t._v(" "),e("jfk-notification",{staticClass:"font-size--24",attrs:{message:t.message}}),t._v(" "),e("div",{staticClass:"booking-information jfk-pl-30 jfk-pr-30 is-align-middle"},[t.product.name?e("div",{staticClass:"booking-information__name font-size--38 is-align-middle jfk-flex"},[e("div",{staticClass:"booking-information__shadow"}),t._v(" "),e("span",{domProps:{textContent:t._s(t.product.name)}})]):t._e(),t._v(" "),e("div",{staticClass:"booking-information__hotel font-size--24",domProps:{textContent:t._s(t.product.hotel_name)}}),t._v(" "),t.product.price_package?e("div",{staticClass:"jfk-price font-size--54 booking-information__price"},[e("i",{staticClass:"jfk-font-number jfk-price__currency"},[t._v("¥")]),t._v(" "),e("i",{staticClass:"jfk-font-number jfk-price__number",domProps:{textContent:t._s(t.product.price_package)}})]):t._e()]),t._v(" "),e("div",{staticClass:"booking-coupon jfk-pl-30 jfk-pr-30"},[e("div",{staticClass:"booking-coupon-wrap"},[e("div",{staticClass:"booking-coupon-list"},[e("div",{staticClass:"booking-coupon-list__title font-size--28"},[t._v("请拨打预约电话进行预订客房")]),t._v(" "),e("div",{staticClass:"booking-coupon-list__content jfk-flex is-align-middle"},[t._m(0),t._v(" "),e("div",{staticClass:"font-size--32 booking-coupon-list__right"},[e("jfk-text-split",{attrs:{text:t.code,split:3}})],1)])]),t._v(" "),t.product.hotel_tel?e("div",{staticClass:"booking-coupon-phone jfk-flex is-align-middle"},[e("a",{staticClass:"jfk-flex is-align-middle",attrs:{href:"tel:"+t.product.hotel_tel}},[e("div",{staticClass:"booking-coupon-phone__number font-size--28",domProps:{textContent:t._s("预约电话："+t.product.hotel_tel)}}),t._v(" "),e("div",{staticClass:"booking-coupon-phone__line"}),t._v(" "),e("div",{staticClass:"booking-coupon-phone__icon jfk-font icon-mall_icon_reservation_contact"})])]):t._e()])]),t._v(" "),t._m(1)],1)},staticRenderFns:[function(){var t=this,o=t.$createElement,e=t._self._c||o;return e("span",{staticClass:"font-size--28 booking-coupon-list__left"},[t._v("劵"),e("i"),t._v("码")])},function(){var t=this,o=t.$createElement;return(t._self._c||o)("jfk-support")}]}}});