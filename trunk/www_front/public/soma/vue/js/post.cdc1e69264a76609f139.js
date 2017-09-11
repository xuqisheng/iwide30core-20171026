webpackJsonp([6,34],{158:function(s,t,e){"use strict";function i(s){return s&&s.__esModule?s:{default:s}}Object.defineProperty(t,"__esModule",{value:!0});var a=e(8),d=i(a),r=e(425),n=i(r);t.default=function(){new d.default({el:"#app",template:"<App/>",components:{App:n.default}})}},171:function(s,t,e){!function(t,e){s.exports=e()}(0,function(){return function(s){function t(i){if(e[i])return e[i].exports;var a=e[i]={i:i,l:!1,exports:{}};return s[i].call(a.exports,a,a.exports,t),a.l=!0,a.exports}var e={};return t.m=s,t.c=e,t.i=function(s){return s},t.d=function(s,e,i){t.o(s,e)||Object.defineProperty(s,e,{configurable:!1,enumerable:!0,get:i})},t.n=function(s){var e=s&&s.__esModule?function(){return s.default}:function(){return s};return t.d(e,"a",e),e},t.o=function(s,t){return Object.prototype.hasOwnProperty.call(s,t)},t.p="/",t(t.s=171)}({171:function(s,t,e){"use strict";function i(s,t){for(var e=0,i=t.length;e<i;){var a=t[e];if(!n(a,s))return{passed:!1,message:a.message,index:e};e++}return{passed:!0}}Object.defineProperty(t,"__esModule",{value:!0}),t.default=i;var a=function(s){return"string"===s||"url"===s||"hex"===s||"email"===s||"pattern"===s},d=function(s,t){return void 0===s||null===s||!("array"!==t||!Array.isArray(s)||s.length)||!(!a(t)||"string"!=typeof s||s)},r={phone:function(s){return/1\d{10}/.test(s)},integer:function(s){return/^[0-9]+$/.test(s)},required:function(s){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"string";return!d(s,t)},range:function(s){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"string",e=arguments[2],i=arguments[3],a=arguments[4],d=s;return"string"!==t&&"array"!==t||(d=s.length),a?d===a:void 0!==e&&void 0===i?d>=e:void 0===e&&void 0!==i?d<=i:void 0===e||void 0===i||d>=e&&d<=i}},n=function(s,t){return s.required?r.required(t,s.type):s.type&&r[s.type]?r[s.type](t):s.length?r.range(t,s.type,s.min,s.max,s.len):s.validator?s.validator(t,s):void 0}}})})},172:function(s,t,e){"use strict";t.__esModule=!0;var i=e(174),a=function(s){return s&&s.__esModule?s:{default:s}}(i);t.default=function(s,t,e){return t in s?(0,a.default)(s,t,{value:e,enumerable:!0,configurable:!0,writable:!0}):s[t]=e,s}},174:function(s,t,e){s.exports={default:e(175),__esModule:!0}},175:function(s,t,e){e(176);var i=e(3).Object;s.exports=function(s,t,e){return i.defineProperty(s,t,e)}},176:function(s,t,e){var i=e(14);i(i.S+i.F*!e(6),"Object",{defineProperty:e(9).f})},179:function(s,t,e){"use strict";function i(s,t,e){for(var i=s.length,a=Math.min(e||0,i);a<i;){if(t(s[a]))return a;a++}return-1}function a(){var s=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:location.href,i=arguments[3],a=(0,r.default)({t:Date.now()},s);window.history.pushState(a,t,e),window.addEventListener("popstate",function(){setTimeout(function(){i&&i()},100)})}Object.defineProperty(t,"__esModule",{value:!0});var d=e(29),r=function(s){return s&&s.__esModule?s:{default:s}}(d);t.findIndex=i,t.showFullLayer=a},189:function(s,t,e){"use strict";function i(s){return s&&s.__esModule?s:{default:s}}Object.defineProperty(t,"__esModule",{value:!0});var a=e(172),d=i(a),r=e(52),n=i(r),o=e(29),c=i(o),l=e(27),u=e(171),f=i(u),_={province:"",city:"",region:"",contact:"",phone:"",address:""};t.default={name:"jfk-address",components:{"address-select":function(){return e.e(35).then(e.bind(null,325))}},data:function(){var s=this;return{aid:"-1",eaid:"-1",isEditing:!1,loading:!1,actionsheetVisible:!1,addressDataLoaded:!1,addressPicked:{},addressRegionIds:[],rules:{contact:[{required:!0,message:"收件人为空"},{max:10,length:!0,message:"收件人必须在10个字符内"}],phone:[{required:!0,message:"收件电话为空"},{type:"phone",message:"手机号码错误"}],area:[{validator:function(){return s.addressPicked.province},message:"收件地址为空"}],address:[{required:!0,message:"详细地址为空"}]},validResult:{contact:{passed:!1,message:""},phone:{passed:!1,message:""},area:{passed:!1,message:""},address:{passed:!1,message:""}}}},beforeCreate:function(){this.maxHeight=window.innerHeight-50-15+"px"},created:function(){this.aid=this.addressId,this.address.length||(this.isEditing=!0)},computed:{showAdd:function(){return this.addressDataLoaded||(this.loading=!0),!this.address.length||!!this.isEditing},addressPickedDetail:function(){var s=this.addressPicked,t=s.province_name,e=void 0===t?"":t,i=s.city_name,a=void 0===i?"":i,d=s.region_name;return e+a+(void 0===d?"":d)},addressItems:{get:function(){return this.address},set:function(s){this.$emit("update:address",s)}}},watch:{isEditing:function(s){if(s){var t=(0,c.default)({},_),e=this.eaid,i=this.address;if("-1"!==e&&i.length){var a=i.filter(function(s){return s.address_id===e});t=(0,c.default)(t,a[0])}this.addressPicked=t}},addressId:function(s){this.aid=s},showAddressList:function(s){s&&this.addressItems.length&&(this.eaid="-1",this.isEditing=!1)}},methods:{handleEditAddress:function(s){this.isEditing=!0,this.eaid=s},checkForm:function(){var s=this.addressPicked,t=this.rules,e=this,i=!0;for(var a in t){var r=(0,f.default)(s[a],t[a]);e.validResult=(0,c.default)({},e.validResult,(0,d.default)({},a,(0,n.default)({},r,{show:!r.passed}))),r.passed||(i=!1)}return i},handleSaveAddress:function(){if(this.checkForm()){var s=this,t=this.addressPicked,e=this.addressItems,i=t.address_id,a=(0,c.default)({},t),d=this.$jfkToast({isLoading:!0,duration:-1,iconClass:"jfk-loading__snake",zIndex:1e5});(0,l.postExpressSave)(a,{REJECTERRORCONFIG:{serveError:!0}}).then(function(a){if(d.close(),s.eaid="-1",s.isEditing=!1,t.address_id=a.web_data.address_id,i){for(var r=-1,n=e.length,o=0;o<n;){if(e[o].address_id===i){r=o;break}o++}e.splice(r,1,t)}else e.unshift(t);s.$emit("pick-address",a.web_data.address_id)}).catch(function(t){if(1001===t.status&&t.web_data.error){var e=t.web_data.error,i={};for(var a in e)i[a]={message:e[a],passed:!1,show:!0};s.validResult=(0,c.default)({},s.validResult,i)}d.close()})}},handlePickAddress:function(s){this.$emit("pick-address",s)},handleAddAddress:function(){this.eaid="-1",this.isEditing=!0},handleAddressLoaded:function(){this.loading=!1,this.addressDataLoaded=!0},handleAddressPicked:function(s,t){this.actionsheetVisible=!1,this.addressPicked=(0,c.default)({},this.addressPicked,{province:t[0].region_id,province_name:t[0].region_name,city:t[1].region_id,city_name:t[1].region_name,region:t[2]&&t[2].region_id,region_name:t[2]&&t[2].region_name})},handleShowAddressSelect:function(){var s=this.addressPicked;this.addressRegionIds=[s.province,s.city,s.region],this.actionsheetVisible=!0},handleHiddenError:function(s){this.validResult=(0,c.default)({},this.validResult,(0,d.default)({},s,{show:!1}))}},props:{address:{type:Array,required:!0,default:function(){return[]}},addressId:String,showAddressList:Number}}},198:function(s,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=e(189),a=e.n(i),d=e(207),r=e(26),n=r(a.a,d.a,null,null,null);t.default=n.exports},207:function(s,t,e){"use strict";var i=function(){var s=this,t=s.$createElement,e=s._self._c||t;return e("div",{staticClass:"jfk-address jfk-form font-size--28"},[e("div",{directives:[{name:"show",rawName:"v-show",value:s.showAdd,expression:"showAdd"}],staticClass:"jfk-address__add"},[e("form",{staticClass:"jfk-address-form  jfk-pl-30 jfk-pr-30"},[e("div",{staticClass:"form-item"},[e("label",[e("span",{staticClass:"form-item__label form-item__label--word-3 font-color-extra-light-gray"},[s._v("收件人")]),s._v(" "),e("div",{staticClass:"form-item__body"},[e("input",{directives:[{name:"model",rawName:"v-model",value:s.addressPicked.contact,expression:"addressPicked.contact"}],staticClass:"font-color-white",attrs:{type:"text",placeholder:"请输入收件人"},domProps:{value:s.addressPicked.contact},on:{input:function(t){t.target.composing||(s.addressPicked.contact=t.target.value)}}}),s._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:s.validResult.contact.show,expression:"validResult.contact.show"}],staticClass:"form-item__status is-error",on:{click:function(t){s.handleHiddenError("contact")}}},[e("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),s._v(" "),e("span",{staticClass:"form-item__status-tip"},[e("i",{staticClass:"form-item__status-cont"},[s._v(s._s(s.validResult.contact.message))]),s._v(" "),e("i",{staticClass:"form-item__status-trigger"},[s._v("重新输入")])])])])])]),s._v(" "),e("div",{staticClass:"form-item"},[e("label",[e("span",{staticClass:"form-item__label font-color-extra-light-gray"},[s._v("收件电话")]),s._v(" "),e("div",{staticClass:"form-item__body"},[e("input",{directives:[{name:"model",rawName:"v-model",value:s.addressPicked.phone,expression:"addressPicked.phone"}],staticClass:"font-color-white",attrs:{type:"text",placeholder:"请输入收件人手机号码"},domProps:{value:s.addressPicked.phone},on:{input:function(t){t.target.composing||(s.addressPicked.phone=t.target.value)}}}),s._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:s.validResult.phone.show,expression:"validResult.phone.show"}],staticClass:"form-item__status is-error",on:{click:function(t){s.handleHiddenError("phone")}}},[e("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),s._v(" "),e("span",{staticClass:"form-item__status-tip"},[e("i",{staticClass:"form-item__status-cont"},[s._v(s._s(s.validResult.phone.message))]),s._v(" "),e("i",{staticClass:"form-item__status-trigger"},[s._v("重新输入")])])])])])]),s._v(" "),e("div",{staticClass:"form-item form-item__select"},[e("span",{staticClass:"form-item__label  font-color-extra-light-gray"},[s._v("收件地址")]),s._v(" "),e("div",{staticClass:"form-item__body",on:{click:s.handleShowAddressSelect}},[e("p",{directives:[{name:"show",rawName:"v-show",value:!s.addressPickedDetail,expression:"!addressPickedDetail"}],staticClass:"tip font-color-light-gray"},[s._v("请选择收件区域")]),s._v(" "),e("p",{directives:[{name:"show",rawName:"v-show",value:s.addressPickedDetail,expression:"addressPickedDetail"}],staticClass:"tip font-color-white"},[s._v(s._s(s.addressPickedDetail))]),s._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:s.validResult.area.show,expression:"validResult.area.show"}],staticClass:"form-item__status is-error",on:{click:function(t){s.handleHiddenError("area")}}},[e("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),s._v(" "),e("span",{staticClass:"form-item__status-tip"},[e("i",{staticClass:"form-item__status-cont"},[s._v(s._s(s.validResult.area.message))]),s._v(" "),e("i",{staticClass:"form-item__status-trigger"},[s._v("重新输入")])])])]),s._v(" "),s._m(0)]),s._v(" "),e("div",{staticClass:"form-item"},[e("label",[e("span",{staticClass:"form-item__label font-color-extra-light-gray"},[s._v("详细地址")]),s._v(" "),e("div",{staticClass:"form-item__body"},[e("textarea",{directives:[{name:"model",rawName:"v-model",value:s.addressPicked.address,expression:"addressPicked.address"}],staticClass:"font-color-white",attrs:{rows:"2",placeholder:"如街道、楼层等"},domProps:{value:s.addressPicked.address},on:{input:function(t){t.target.composing||(s.addressPicked.address=t.target.value)}}}),s._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:s.validResult.address.show,expression:"validResult.address.show"}],staticClass:"form-item__status is-error",on:{click:function(t){s.handleHiddenError("address")}}},[e("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),s._v(" "),e("span",{staticClass:"form-item__status-tip"},[e("i",{staticClass:"form-item__status-cont"},[s._v(s._s(s.validResult.address.message))]),s._v(" "),e("i",{staticClass:"form-item__status-trigger"},[s._v("重新输入")])])])])])])]),s._v(" "),e("div",{staticClass:"jfk-address__add-control"},[e("a",{staticClass:"jfk-button--free jfk-button jfk-button--primary is-special",attrs:{href:"javascript:;"},on:{click:s.handleSaveAddress}},[s._v("保存")])])]),s._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:!s.showAdd,expression:"!showAdd"}],staticClass:"jfk-address__list jfk-pl-30 jfk-pr-30"},[e("ul",{staticClass:"jfk-address__list-box",style:{"max-height":s.maxHeight}},s._l(s.addressItems,function(t){return e("li",{key:t.address_id,staticClass:"jfk-address__list-item jfk-pt-30"},[e("div",{staticClass:"jfk-radio jfk-radio--shape-rect color-golden"},[e("label",{staticClass:"jfk-radio__label"},[e("input",{directives:[{name:"model",rawName:"v-model",value:s.aid,expression:"aid"}],attrs:{type:"radio"},domProps:{checked:t.address_id===s.aid,value:t.address_id,checked:s._q(s.aid,t.address_id)},on:{__c:function(e){s.aid=t.address_id}}}),s._v(" "),e("div",{staticClass:"jfk-radio__text",on:{click:function(e){s.handlePickAddress(t.address_id)}}},[e("div",{staticClass:"jfk-address__list-item-cont jfk-flex is-align-middle"},[e("div",{staticClass:"address-item-box"},[e("div",{staticClass:"address-item-cont font-color-white"},[e("span",{staticClass:"contact"},[s._v(s._s(t.contact))]),s._v(" "),e("span",{staticClass:"phone"},[s._v(s._s(t.phone))])]),s._v(" "),e("div",{staticClass:"address-item-cont font-color-extra-light-gray"},[s._v("\n                    "+s._s((t.provice_name||"")+(t.city_name||"")+(t.region_name||"")+(t.address||""))+"\n                  ")])]),s._v(" "),e("div",{staticClass:"address-item-edit jfk-flex is-align-middle is-justify-center",on:{click:function(e){e.preventDefault(),e.stopPropagation(),s.handleEditAddress(t.address_id)}}},[e("i",{staticClass:"edit-icon jfk-font icon-mall_icon_edit color-golden"})])])]),s._v(" "),s._m(1,!0)])])])})),s._v(" "),e("div",{staticClass:"jfk-address__list-control"},[e("a",{staticClass:"jfk-button jfk-button--suspension jfk-button-higher jfk-button--free",attrs:{href:"javascript:;"},on:{click:s.handleAddAddress}},[e("i",{staticClass:"jfk-address__list-icon jfk-d-ib"},[s._v("+")]),e("i",{staticClass:"jfk-d-ib"},[s._v("新增收货地址")])])])]),s._v(" "),e("jfk-popup",{staticClass:"jfk-actionsheet jfk-actionsheet__address",attrs:{closeOnClickModal:!1,position:"bottom"},model:{value:s.actionsheetVisible,callback:function(t){s.actionsheetVisible=t},expression:"actionsheetVisible"}},[e("address-select",{attrs:{ids:s.addressRegionIds},on:{"address-data-loaded":s.handleAddressLoaded,"address-picked":s.handleAddressPicked}})],1)],1)},a=[function(){var s=this,t=s.$createElement,e=s._self._c||t;return e("span",{staticClass:"form-item__foot"},[e("i",{staticClass:"jfk-font icon-user_icon_jump_normal font-color-extra-light-gray"})])},function(){var s=this,t=s.$createElement,e=s._self._c||t;return e("span",{staticClass:"jfk-radio__icon"},[e("i",{staticClass:"jfk-font icon-radio_icon_selected_default jfk-radio__icon-icon"})])}],d={render:i,staticRenderFns:a};t.a=d},369:function(s,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=e(27),a=e(198),d=function(s){return s&&s.__esModule?s:{default:s}}(a),r=e(179),n=e(28),o=n.default(location.href);t.default={components:{jfkAddress:d.default},computed:{},beforeCreate:function(){this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0})},methods:{deliver:function(){var s=this;if(this.product.arid){this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0});var t={aiid:this.aiid||"",num:this.count||1,arid:this.product.arid,product_id:this.product.product_id};(0,i.posExpressCommit)(t).then(function(t){var e=t.web_data.detail_url;window.location.href=e,s.toast.close()}).catch(function(){s.toast.close()})}},addAddress:function(){var s=this,t=function(){s.addressShow=!1};(0,r.showFullLayer)(null,"",location.href,t),this.addressShow=!0},getAddress:function(s){var t=null;if(this.address&&this.address.length>0)for(var e=0;e<this.address.length;e++)this.address[e].address_id===s&&(t=this.address[e]);return t},setProduct:function(s){null!==s&&(this.product.phone=s.phone,this.product.address=s.province_name+s.city_name+s.region_name+s.address,this.product.user_name=s.contact,this.product.address_show=!0)},handlePickedAddress:function(s){var t=this.getAddress(s);this.setProduct(t),this.product.arid=s,history.back(-1)},selectAddress:function(){var s=this,t=function(){var t=function(){s.addressShow=!1};(0,r.showFullLayer)(null,"",location.href,t),s.addressShow=!0,s.toast.close()};if(this.address&&this.address.length>0)return t(),!1;this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),(0,i.getExpressAddress)().then(function(e){s.address=e.web_data,t()})}},created:function(){var s=this;this.showAddressList=Date.now(),(0,i.getExpressIndex)({oid:o.oid||"",gid:o.gid||""}).then(function(t){s.toast.close();var e=t.web_data;s.max=parseInt(e.count),s.product={count:e.count||0,product_id:e.product.product_id||"",name:e.product.name||"",provider:"由"+e.wechat_name+"提供",address:e.address||"",arid:e.arid||"",user_name:e.contact||"",phone:e.phone||""},void 0===e.wechat_name&&(s.product.provider=""),s.aiid=e.aiid,e&&e.address&&e.arid?(s.product.address_show=!0,s.$store.commit("updateAddressId",s.product.arid)):s.product.address_show=!1}).catch(function(){s.toast.close()})},data:function(){return{count:1,min:1,max:1,aiid:"",product:{},addressLayerVisible:!1,address:[],showAddressList:0,addressShow:!1}}}},425:function(s,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=e(369),a=e.n(i),d=e(473),r=e(26),n=r(a.a,d.a,null,null,null);t.default=n.exports},473:function(s,t,e){"use strict";var i=function(){var s=this,t=s.$createElement,e=s._self._c||t;return e("div",[s.addressShow?s._e():e("div",{staticClass:"jfk-pages jfk-pages__post"},[e("div",{staticClass:"jfk-pages__theme"}),s._v(" "),s.product.address_show?e("div",{staticClass:"invoice-address jfk-pl-30 jfk-pr-30",on:{click:s.selectAddress}},[e("div",{staticClass:"invoice-address__content"},[e("ul",[e("li",{staticClass:"jfk-flex font-size--24"},[e("div",{staticClass:"invoice-address__title invoice-address__word"},[s._v("收件人")]),s._v(" "),e("div",{staticClass:"invoice-address__item-content font-size--28"},[e("i",{domProps:{textContent:s._s(s.product.user_name)}}),s._v(" "),e("small",{staticClass:"font-size--28",domProps:{textContent:s._s(s.product.phone)}})])]),s._v(" "),e("li",{staticClass:"jfk-flex font-size--24"},[e("div",{staticClass:"invoice-address__title"},[s._v("收件地址")]),s._v(" "),e("div",{staticClass:"invoice-address__item-content font-size--28",domProps:{textContent:s._s(s.product.address)}})])]),s._v(" "),e("span",{staticClass:"jfk-font icon-user_icon_jump_normal font-size--24"}),s._v(" "),e("div",{staticClass:"invoice-address__line"})])]):e("div",{staticClass:"invoice-add-address jfk-pl-30 jfk-pr-30",on:{click:s.addAddress}},[s._m(0)]),s._v(" "),e("div",{staticClass:"post-info jfk-pl-30 jfk-pr-30"},[e("div",{staticClass:"post-info__name"},[e("i",{staticClass:"post-info__name--mask"}),s._v(" "),e("span",{staticClass:"font-size--38",domProps:{textContent:s._s(s.product.name)}})]),s._v(" "),e("div",{staticClass:"post-info__hotel font-size--24",domProps:{textContent:s._s(s.product.provider)}}),s._v(" "),e("div",{staticClass:"post-info__number font-size--24"},[s._v("共拥有"),e("span",{domProps:{textContent:s._s(s.product.count)}}),s._v("份")])]),s._v(" "),e("div",{staticClass:"jfk-pl-30 jfk-pr-30 post-number-wrap"},[e("div",{staticClass:"post-number is-align-middle jfk-flex"},[e("div",{staticClass:"post-number__title font-size--28"},[s._v("邮寄数量")]),s._v(" "),e("div",{staticClass:"font-size--32 post-number__content jfk-ta-r"},[e("jfk-input-number",{staticClass:"jfk-d-ib",attrs:{min:s.min,max:s.max},model:{value:s.count,callback:function(t){s.count=t},expression:"count"}})],1)])]),s._v(" "),e("div",{staticClass:"post-btn"},[e("button",{staticClass:"jfk-button jfk-button--primary is-plain font-size--30 jfk-button--free",class:{"is-disabled":!s.product.arid},on:{click:s.deliver}},[e("span",[s._v("立即发货")])])]),s._v(" "),s._m(1)],1),s._v(" "),s.addressShow?e("div",{staticClass:"page-address"},[e("div",{staticClass:"jfk-pages__theme"}),s._v(" "),e("jfk-address",{attrs:{address:s.address,addressId:s.product.arid,"show-address-list":s.showAddressList},on:{"update:address":function(t){s.address=t},"pick-address":s.handlePickedAddress}})],1):s._e()])},a=[function(){var s=this,t=s.$createElement,e=s._self._c||t;return e("div",{staticClass:"invoice-add-address__content jfk-flex is-align-middle font-size--28"},[e("i",{staticClass:"jfk-d-ib"}),e("span",[s._v("新增收货地址")])])},function(){var s=this,t=s.$createElement;return(s._self._c||t)("jfk-support")}],d={render:i,staticRenderFns:a};t.a=d}});