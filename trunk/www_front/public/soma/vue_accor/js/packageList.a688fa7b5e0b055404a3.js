webpackJsonp([13],{151:function(t,e,s){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var i=s(8),o=a(i),n=s(419),r=a(n);e.default=function(){new o.default({el:"#app",template:"<App/>",components:{App:r.default}})}},165:function(t,e,s){!function(e,s){t.exports=s()}(0,function(){return function(t){function e(a){if(s[a])return s[a].exports;var i=s[a]={i:a,l:!1,exports:{}};return t[a].call(i.exports,i,i.exports,e),i.l=!0,i.exports}var s={};return e.m=t,e.c=s,e.i=function(t){return t},e.d=function(t,s,a){e.o(t,s)||Object.defineProperty(t,s,{configurable:!1,enumerable:!0,get:a})},e.n=function(t){var s=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(s,"a",s),s},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="/",e(e.s=167)}({167:function(t,e,s){"use strict";function a(t,e){for(var s=0,a=e.length;s<a;){var i=e[s];if(!r(i,t))return{passed:!1,message:i.message,index:s};s++}return{passed:!0}}Object.defineProperty(e,"__esModule",{value:!0}),e.default=a;var i=function(t){return"string"===t||"url"===t||"hex"===t||"email"===t||"pattern"===t},o=function(t,e){return void 0===t||null===t||!("array"!==e||!Array.isArray(t)||t.length)||!(!i(e)||"string"!=typeof t||t)},n={phone:function(t){return/1\d{10}/.test(t)},integer:function(t){return/^[0-9]+$/.test(t)},required:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"string";return!o(t,e)},range:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"string",s=arguments[2],a=arguments[3],i=arguments[4],o=t;return"string"!==e&&"array"!==e||(o=t.length),i?o===i:void 0!==s&&void 0===a?o>=s:void 0===s&&void 0!==a?o<=a:void 0===s||void 0===a||o>=s&&o<=a}},r=function(t,e){return t.required?n.required(e,t.type):t.type&&n[t.type]?n[t.type](e):t.length?n.range(e,t.type,t.min,t.max,t.len):t.validator?t.validator(e,t):void 0}}})})},166:function(t,e,s){"use strict";e.__esModule=!0;var a=s(168),i=function(t){return t&&t.__esModule?t:{default:t}}(a);e.default=function(t,e,s){return e in t?(0,i.default)(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}},168:function(t,e,s){t.exports={default:s(169),__esModule:!0}},169:function(t,e,s){s(170);var a=s(3).Object;t.exports=function(t,e,s){return a.defineProperty(t,e,s)}},170:function(t,e,s){var a=s(14);a(a.S+a.F*!s(6),"Object",{defineProperty:s(9).f})},363:function(t,e,s){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var i=s(166),o=a(i),n=s(52),r=a(n),l=s(28),f=a(l),c=s(165),u=a(c),d=s(26),_=s(27),m=_.default(location.href);e.default={components:{},computed:{},methods:{getCode:function(){var t=this,e=this.rules,s=!0;for(var a in e){var i=(0,u.default)(this.getFormItemVal(a),e[a]);this.validResult=(0,f.default)({},this.validResult,(0,o.default)({},a,(0,r.default)({},i,{show:!i.passed}))),i.passed||(s=!1)}if(s){this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0});var n={gift_id:this.giftId||"",inter_id:m.inter_id||"",saler_id:m.saler_id||"",saler_name:m.saler_name||"",gift_num:this.form.number||"",record_info:this.form.reservationInfo||"",orther_remark:this.form.other||""};(0,d.postGenerateGift)(n).then(function(e){window.location.href=e.web_data.page_resource.link.gift_detail,t.toast.close()}).catch(function(){t.toast.close()})}},getFormItemVal:function(t){switch(t){case"reservationInfo":return this.form.reservationInfo;case"number":return this.form.number;case"other":return this.form.other;default:return""}},handleHiddenError:function(t){this.validResult=(0,f.default)({},this.validResult,(0,o.default)({},t,{show:!1}))},generateGiftPackage:function(t,e){this.stock=parseInt(e),this.form.reservationInfo="",this.form.number="1",this.form.other="",this.giftId=t,this.salerName=m.saler_name||"",this.validResult.reservationInfo.show=!1,this.validResult.number.show=!1,this.validResult.other.show=!1,this.verificationVisible=!0},getData:function(){var t=this,e=function(){try{t.toast.close(),t.disableLoadPackage=!1,t.page+=1}catch(t){}};(0,d.getGiftPackageList)({saler_id:m.saler_id||"",inter_id:m.inter_id||"",page:this.page}).then(function(s){0===s.web_data.length&&(t.more=!1);for(var a=0;a<s.web_data.length;a++)t.packageList.push(s.web_data[a]);t.$nextTick(function(){e()})}).catch(function(){e()})},loadMore:function(){this.more&&(this.disableLoadPackage=!0,this.getData())}},created:function(){this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),this.$pageNamespace(m),this.getData()},data:function(){var t=this;return{verificationVisible:!1,packageList:[],giftId:"",disableLoadPackage:!1,salerName:"",more:!0,stock:0,page:1,form:{reservationInfo:"",number:"1",other:""},rules:{reservationInfo:[{required:!0,message:"请输入登记信息"}],number:[{required:!0,message:"请输入数量"},{type:"integer",message:"请输入正确的数量"},{validator:function(){return!(parseInt(t.form.number)>t.stock)},message:"请输入正确的库存"}]},validResult:{reservationInfo:{passed:!1,message:""},number:{passed:!1,message:""},other:{passed:!0,message:""}}}}}},419:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s(363),i=s.n(a),o=s(472),n=s(25),r=n(i.a,o.a,null,null,null);e.default=r.exports},472:function(t,e,s){"use strict";var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-pages jfk-pages__package-list"},[s("div",{staticClass:"jfk-pages__theme"}),t._v(" "),t.packageList.length>0?s("ul",{directives:[{name:"infinite-scroll",rawName:"v-infinite-scroll",value:t.loadMore,expression:"loadMore"}],staticClass:"package-list",attrs:{"infinite-scroll-disabled":"disableLoadPackage","infinite-scroll-distance":"0"}},t._l(t.packageList,function(e,a){return s("li",{key:a,staticClass:"package-list__item"},[s("div",{staticClass:"jfk-package-info jfk-pl-30 jfk-pr-30"},[s("div",{staticClass:"jfk-package-info__content"},[s("div",{staticClass:"jfk-package-info__base-info"},[t._m(0,!0),t._v(" "),s("div",{staticClass:"jfk-package-info__base-info--right"},[s("div",{staticClass:"jfk-package-info__base-info--content"},[s("p",{staticClass:"name font-size--32",domProps:{textContent:t._s(e.name)}}),t._v(" "),s("p",{staticClass:"validity font-size--24",domProps:{textContent:t._s("有效期至"+e.end_time)}}),t._v(" "),s("p",{staticClass:"number font-size--24"},[t._v("剩余"),s("span",{staticClass:"color-golden",domProps:{textContent:t._s(e.stock)}}),t._v("份")]),t._v(" "),s("button",{staticClass:"jfk-button jfk-button--primary is-plain font-size--30",on:{click:function(s){t.generateGiftPackage(e.gift_id,e.stock)}}},[s("span",[t._v("生成礼包")])])])])]),t._v(" "),t._l(e.child_product_info,function(a,i){return e&&e.child_product_info?s("ul",{key:i,staticClass:"jfk-package-info__more-info"},[i<=10?s("li",{staticClass:"jfk-flex"},[s("span",{staticClass:"jfk-ta-l font-size--28",domProps:{textContent:t._s(a.name)}}),t._v(" "),s("span",{staticClass:"jfk-ta-r font-size--28",domProps:{textContent:t._s(a.num)}})]):t._e()]):t._e()})],2)])])})):t._e(),t._v(" "),s("div",{staticClass:"package-list__loading font-size--24 jfk-ta-c",class:{"package-list__show":t.disableLoadPackage}},[t._m(1)]),t._v(" "),s("jfk-popup",{ref:"popupService",staticClass:"jfk-popup__service",attrs:{showCloseButton:!0},model:{value:t.verificationVisible,callback:function(e){t.verificationVisible=e},expression:"verificationVisible"}},[s("div",{staticClass:"popup-box"},[s("div",{staticClass:"title font-size--30 font-color-white jfk-ta-c"},[t._v("服务说明")]),t._v(" "),s("div",{staticClass:"package-form"},[s("form",{staticClass:"jfk-form font-size--28"},[s("div",{staticClass:"form-item"},[s("label",[s("span",{staticClass:"form-item__label  font-color-extra-light-gray"},[t._v("登记信息")]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.form.reservationInfo,expression:"form.reservationInfo"}],staticClass:"font-color-white",attrs:{type:"text",placeholder:"房间号/预订号"},domProps:{value:t.form.reservationInfo},on:{input:function(e){e.target.composing||(t.form.reservationInfo=e.target.value)}}}),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.validResult.reservationInfo.show,expression:"validResult.reservationInfo.show"}],staticClass:"form-item__status is-error",on:{click:function(e){t.handleHiddenError("reservationInfo")}}},[s("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),t._v(" "),s("span",{staticClass:"form-item__status-tip"},[s("i",{staticClass:"form-item__status-cont",domProps:{textContent:t._s(t.validResult.reservationInfo.message)}})])])])])]),t._v(" "),s("div",{staticClass:"form-item"},[s("label",[s("span",{staticClass:"form-item__label  font-color-extra-light-gray"},[s("i",[t._v("数")]),s("span",{staticClass:"form-item__label--word-4"}),s("i",[t._v("量")])]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.form.number,expression:"form.number"}],staticClass:"font-color-white",attrs:{type:"text",placeholder:"购买数量"},domProps:{value:t.form.number},on:{input:function(e){e.target.composing||(t.form.number=e.target.value)}}}),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.validResult.number.show,expression:"validResult.number.show"}],staticClass:"form-item__status is-error",on:{click:function(e){t.handleHiddenError("number")}}},[s("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),t._v(" "),s("span",{staticClass:"form-item__status-tip"},[s("i",{staticClass:"form-item__status-cont",domProps:{textContent:t._s(t.validResult.number.message)}})])])])])]),t._v(" "),s("div",{staticClass:"form-item"},[s("label",[s("span",{staticClass:"form-item__label  font-color-extra-light-gray"},[s("i",[t._v("其")]),s("span",{staticClass:"form-item__label--word-4"}),s("i",[t._v("他")])]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.form.other,expression:"form.other"}],staticClass:"font-color-white",attrs:{type:"text",placeholder:""},domProps:{value:t.form.other},on:{input:function(e){e.target.composing||(t.form.other=e.target.value)}}})])])]),t._v(" "),s("div",{staticClass:"form-item"},[s("label",[s("span",{staticClass:"form-item__label  font-color-extra-light-gray form-item__label--word-3"},[t._v("创建人")]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("p",{staticClass:"font-color-white",domProps:{textContent:t._s(t.salerName)}})])])])])]),t._v(" "),s("div",{staticClass:"btn"},[s("button",{staticClass:"jfk-button jfk-button--primary is-special jfk-button--free font-size--32",on:{click:t.getCode}},[s("span",[t._v("生成领取二维码")])])])])]),t._v(" "),t._m(2)],1)},i=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-package-info__base-info--left"},[s("div",{staticClass:"jfk-package-info__base-info--title jfk-flex is-align-middle is-justify-center"},[s("i",{staticClass:"jfk-font  icon-font_zh_li_1_qkbys"})])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("span",{staticClass:"jfk-loading__triple-bounce color-golden font-size--24"},[s("i",{staticClass:"jfk-loading__triple-bounce-item"}),t._v(" "),s("i",{staticClass:"jfk-loading__triple-bounce-item"}),t._v(" "),s("i",{staticClass:"jfk-loading__triple-bounce-item"})])},function(){var t=this,e=t.$createElement;return(t._self._c||e)("jfk-support")}],o={render:a,staticRenderFns:i};e.a=o}});