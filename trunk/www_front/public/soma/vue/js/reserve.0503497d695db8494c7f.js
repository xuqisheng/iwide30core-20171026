webpackJsonp([5],{162:function(t,e,s){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var o=s(8),a=i(o),n=s(431),r=i(n);e.default=function(){new a.default({el:"#app",template:"<App/>",components:{App:r.default}})}},170:function(t,e,s){!function(e,s){t.exports=s()}(0,function(){return function(t){function e(i){if(s[i])return s[i].exports;var o=s[i]={i:i,l:!1,exports:{}};return t[i].call(o.exports,o,o.exports,e),o.l=!0,o.exports}var s={};return e.m=t,e.c=s,e.i=function(t){return t},e.d=function(t,s,i){e.o(t,s)||Object.defineProperty(t,s,{configurable:!1,enumerable:!0,get:i})},e.n=function(t){var s=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(s,"a",s),s},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="/",e(e.s=166)}({166:function(t,e,s){"use strict";function i(t,e){for(var s=0,i=e.length;s<i;){var o=e[s];if(!r(o,t))return{passed:!1,message:o.message,index:s};s++}return{passed:!0}}Object.defineProperty(e,"__esModule",{value:!0}),e.default=i;var o=function(t){return"string"===t||"url"===t||"hex"===t||"email"===t||"pattern"===t},a=function(t,e){return void 0===t||null===t||!("array"!==e||!Array.isArray(t)||t.length)||!(!o(e)||"string"!=typeof t||t)},n={phone:function(t){return/1\d{10}/.test(t)},integer:function(t){return/^[0-9]+$/.test(t)},required:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"string";return!a(t,e)},range:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"string",s=arguments[2],i=arguments[3],o=arguments[4],a=t;return"string"!==e&&"array"!==e||(a=t.length),o?a===o:void 0!==s&&void 0===i?a>=s:void 0===s&&void 0!==i?a<=i:void 0===s||void 0===i||a>=s&&a<=i}},r=function(t,e){return t.required?n.required(e,t.type):t.type&&n[t.type]?n[t.type](e):t.length?n.range(e,t.type,t.min,t.max,t.len):t.validator?t.validator(e,t):void 0}}})})},171:function(t,e,s){"use strict";e.__esModule=!0;var i=s(173),o=function(t){return t&&t.__esModule?t:{default:t}}(i);e.default=function(t,e,s){return e in t?(0,o.default)(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}},173:function(t,e,s){t.exports={default:s(174),__esModule:!0}},174:function(t,e,s){s(175);var i=s(3).Object;t.exports=function(t,e,s){return i.defineProperty(t,e,s)}},175:function(t,e,s){var i=s(14);i(i.S+i.F*!s(6),"Object",{defineProperty:s(9).f})},178:function(t,e,s){"use strict";function i(t,e,s){for(var i=t.length,o=Math.min(s||0,i);o<i;){if(e(t[o]))return o;o++}return-1}function o(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",s=arguments.length>2&&void 0!==arguments[2]?arguments[2]:location.href,i=arguments[3],o=(0,n.default)({t:Date.now()},t);window.history.pushState(o,e,s),window.addEventListener("popstate",function(){setTimeout(function(){i&&i()},100)})}Object.defineProperty(e,"__esModule",{value:!0});var a=s(28),n=function(t){return t&&t.__esModule?t:{default:t}}(a);e.findIndex=i,e.showFullLayer=o},375:function(t,e,s){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var o=s(171),a=i(o),n=s(52),r=i(n),c=s(28),l=i(c),d=s(27),u=i(d),f=s(170),p=i(f),_=s(178),h=s(56),m=i(h),v=s(54),k=i(v),g=s(26),y=s(432),C=i(y),b=function(t){var e=t.activity,s=void 0===e?{}:e,i=t.asset,o=void 0===i?{}:i;return s.auto_rule.rule_type?s.auto_rule:o.cal_rule.rule_type?o.cal_rule:null},j=k.default.CancelToken,w=void 0;e.default={name:"reverse",components:{"reverse-killsec-time":function(){return s.e(30).then(s.bind(null,433))},"jfk-address":function(){return s.e(34).then(s.bind(null,197))},JfkCoupons:C.default},beforeCreate:function(){var t=(0,u.default)(location.href);this.common=t.common||"",this.tokenId=t.token||"",this.productId=t.pid,this.settingId=t.psp_id||"",this.act_id=t.act_id||"",this.inid=t.inid||"",this.grid=t.grid||"",this.gridType=t.grid_type||"",this.toast=this.$jfkToast({duration:-1,iconClass:"jfk-loading__snake",isLoading:!0}),this.$pageNamespace(t)},created:function(){var t=this;(0,g.getOrderPay)({pid:this.productId,btype:this.orderParams.business,psp_id:this.settingId,token:this.tokenId,common:this.common}).then(function(e){t.toast.close();var s=e.web_data,i=s.count,o=s.psp_setting,a=s.product,n=s.countdown,r=s.address,c=s.public_info,d=s.customer_info,u=void 0===d?{}:d,f=s.create_order_params,p=void 0===f?{}:f,_=s.point,h=s.balance;t.count=i.default,t.max=i.limit,t.pspSetting=o,t.point=_,t.balance=(0,l.default)({},t.balance,h),t.countdown=1e3*n,t.orderParams=(0,l.default)({},t.orderParams,p),r.length&&(t.address=r,t.addressId=r[0].address_id),t.productInfo=(0,l.default)({},t.productInfo,a),t.publicInfo=(0,l.default)({},t.publicInfo,c),t.customerInfo=(0,l.default)({},t.customerInfo,{name:u.name||"",phone:u.mobile||""})})},data:function(){var t=this,e=function(){return 1!==t.addressPosition&&(3!==t.addressPosition||"1"!==t.useType)||"-1"!==t.addressId},s=function(){if(1===t.addressPosition||3===t.addressPosition&&"1"===t.useType){var e=t.addressPicked;return e.province&&e.city&&e.address&&e.contact&&e.phone&&e.address}return!0},i=function(){return t.count>=t.min&&t.count<=t.max};return{isIntegral:!1,useType:"1",productInfo:{},publicInfo:{},customerInfo:{name:"",phone:""},pspSetting:[],address:[],addressId:"-1",addressPosition:0,showAddressList:0,addressLayerVisible:!1,min:1,max:200,count:0,countdown:0,killsecFinished:!1,point:0,balance:{},coupons:[],couponId:"-1",couponDisabled:!1,couponLayerVisible:!1,orderRule:{},orderRulePicked:!1,orderRuleDisabled:!1,lastGetDiscountTimestamp:Date.now(),packageActivityChecked:!0,priceOrderVisible:!1,orderParams:{},rules:{qty:[{required:!0,type:"number",message:"请选择购买数量"},{type:"number",validator:i,message:"购买数量有误"}],name:[{required:!0,message:"购买人为空"},{max:10,length:!0,message:"购买人必须在10个字符内"}],phone:[{required:!0,message:"联系方式为空"},{type:"phone",message:"手机号码错误"}],area:[{validator:e,message:"收件地址为空"},{validator:s,message:"收件地址信息错误"}]},validResult:{name:{passed:!1,message:""},phone:{passed:!1,message:""},area:{passed:!1,message:""}}}},computed:{payTypeText:function(){var t=this.productInfo.tag;return 7===t?"积分支付":6===t?"储值支付":"微信支付"},addressPicked:function(){var t=this.address,e=this.addressId;return t[(0,_.findIndex)(t,function(t){return t.address_id===e})]||{}},addressPickedDetail:function(){var t=this.addressPicked,e=t.provice_name,s=void 0===e?"":e,i=t.city_name,o=void 0===i?"":i,a=t.region_name,n=void 0===a?"":a,r=t.address;return s+o+n+(void 0===r?"":r)},packageInfoHtml:function(){var t="",e="";return 200!==this.max&&(t='<span class="jfk-d-ib color-golden limit-tag font-size--22"><i>限购'+this.max+"份</i></span>"),this.publicInfo.name&&(e='<div class="provide'+(t&&" limit"||"")+'"><span class="jfk-d-ib font-color-light-gray-common">'+this.publicInfo.name+"提供</span>"+(t||"")+"</div>"),this.pspSetting.length&&(e+='<div class="spec'+(!e&&t&&" limit"||"")+'"><span class="font-color-light-gray-common jfk-d-ib">'+this.pspSetting[0].spec_name.join('<i class="line">|</i>')+"</span>"+(!e&&t||"")+"</div>"),!e&&t&&(e+='<div class="limit">'+t+"</div>"),e},couponsClass:function(){return{"font-color-light-gray":"-1"===this.couponId,"font-color-white":"-1"!==this.couponId}},couponsText:function(){return this.couponDisabled?"暂无可用优惠券":this.orderRulePicked&&this.orderRule.rule_type?"优惠活动与优惠券不能同时使用":this.coupons.length?"-1"!==this.couponId?this.couponPicked.title:this.coupons[0].usable?"请选择优惠券":"暂无可用优惠券":"暂无可用优惠券"},couponPicked:function(){var t=this.couponId,e=this.coupons;if("-1"!==t){return e[(0,_.findIndex)(e,function(e){return e.member_card_id===t})]||{}}return{}},activityClass:function(){return{"font-color-light-gray":!this.orderRulePicked,"font-color-white":this.orderRulePicked}},activityText:function(){var t=this.orderRule;return t.rule_type?this.orderRulePicked?t.name:"请选择优惠活动":"暂无优惠活动"},packageActivityShowSwitch:function(){var t=this.orderRule.rule_type;return"30"===t||"40"===t},pricePackage:function(){return Math.ceil(this.productInfo.price_package*this.count*100)/100},priceWithDiscount:function(){var t=Math.ceil(100*this.pricePackage-100*this.priceDiscountItem.price)/100;return t<0?this.priceDiscountItem.canZero?0:.01:t},priceDiscountItem:function(){var t=0,e="",s=!0,i=this.orderRule,o=this.couponPicked;return i.rule_type&&this.packageActivityChecked?(t=i.reduce_cost,e=i.name):o.member_card_id&&("1"===o.card_type?t=o.reduce_cost:"2"===o.card_type?(t=Math.floor(this.productInfo.price_package*this.count*100*(10-o.discount)/10)/100,s=!1):"3"===o.card_type&&(t=this.productInfo.price_package),e=o.title),{name:e,price:t,canZero:s}}},methods:{handleChangeUseType:function(t){this.useType=t},handleKillsecFinish:function(){this.killsecFinished=!0},handleChangeAddress:function(){var t=this;this.addressLayerVisible=!0,this.showAddressList=Date.now();var e=function(){t.addressLayerVisible=!1};(0,_.showFullLayer)(null,"立即购买",location.href,e)},handlePickedAddress:function(t){this.addressId=t,history.back(-1)},handleShowGiftTip:function(){this.$jfkAlert("下单后，购买成功，将礼物打包赠转发给好友，好友点击即可成功领取")},handleShowCoupons:function(){var t=this;if(!this.couponDisabled&&!this.orderRule.rule_type&&this.coupons.length){this.couponLayerVisible=!0;var e=function(){t.couponLayerVisible=!1};(0,_.showFullLayer)(null,"立即购买",location.href,e)}},handlePickedCoupon:function(t){this.couponId=t,history.back(-1)},handleShowOrderDetail:function(){this.priceDiscountItem.name&&(this.priceOrderVisible=!this.priceOrderVisible)},getCoupons:function(){var t=this;t.coupons=[],t.couponId="-1",(0,g.getPackageCoupons)({pid:this.productId,qty:this.count,card_type:-1},{cancelToken:new j(function(t){w=t})}).then(function(e){t.lastGetDiscountTimestamp=Date.now(),t.coupons=e.web_data||[]}).catch(function(){t.lastGetDiscountTimestamp=Date.now(),t.coupons=[]})},getActivities:function(){var t=this;t.$set(t,"orderRule",{}),t.orderRulePicked=!1,(0,g.getPackageRule)({pid:this.productId,qty:this.count,stl:this.orderParams.settlement,psp_sid:this.settingId},{cancelToken:new j(function(t){w=t})}).then(function(e){t.lastGetDiscountTimestamp=Date.now();var s=b(e.web_data);s?t.orderRule=(0,l.default)({},t.orderRule,s):t.$set(t,"orderRule",{})}).catch(function(){t.lastGetDiscountTimestamp=Date.now(),t.$set(t,"orderRule",{})})},getPackageDiscount:function(){var t=this,e=new j(function(t){w=t});t.coupons=[],t.$set(t,"orderRule",{}),t.orderRulePicked=!1,t.couponId="-1",k.default.all([(0,g.getPackageCoupons)({pid:this.productId,qty:this.count,card_type:-1},{cancelToken:e}),(0,g.getPackageRule)({pid:this.productId,qty:this.count,stl:"default",psp_sid:this.settingId},{cancelToken:e})]).then(k.default.spread(function(e,s){if(t.lastGetDiscountTimestamp=Date.now(),1e3===e.status?t.coupons=e.web_data:t.coupons=[],1e3===s.status){var i=b(s.web_data);if(i)return void(t.orderRule=(0,l.default)({},t.orderRule,i))}t.$set(t,"orderRule",{})})).catch(function(e){return t.lastGetDiscountTimestamp=Date.now(),t.coupons=[],t.$set(t,"orderRule",{}),(0,m.default)(e)})},getFormItemVal:function(t){switch(t){case"qty":return this.count;case"name":return this.customerInfo.name;case"phone":return this.customerInfo.phone;case"area":return this.addressId;default:return""}},getOrderParams:function(){var t=this.productInfo.product_id,e={product_id:t,qty:this.count,act_id:this.act_id,inid:this.inid,token:this.tokenId,grid:this.grid,type:this.gridType,password:"",bpay_passwd:"",address_id:"",u_type:"-1",scope_product_link_id:"",quote:"",quote_type:"",mcid:"-1"===this.couponId?"":this.couponId,psp_setting:this.settingId};return"1"===this.useType&&"-1"!==this.addressId&&(e.address_id=this.addressId),this.packageActivityShowSwitch&&this.packageActivityChecked&&(e.quote=this.orderRule.quote,e.quote_type=this.orderRule.rule_type),(0,l.default)({},this.orderParams,e,this.customerInfo)},checkForm:function(){var t=!0,e=this.rules,s=this;for(var i in e){var o=(0,p.default)(s.getFormItemVal(i),e[i]);s.validResult=(0,l.default)({},s.validResult,(0,a.default)({},i,(0,r.default)({},o,{show:!o.passed}))),o.passed||(t=!1,"area"===i&&s.$jfkAlert("邮寄地址错误","",{iconType:"error"}))}return t},handleHiddenError:function(t){this.validResult=(0,l.default)({},this.validResult,(0,a.default)({},t,{show:!1}))},handleSubmitOrder:function(){if(this.priceOrderVisible&&(this.priceOrderVisible=!1),this.killsecFinished)return void this.$jfkAlert("已超过支付时间，请重新购买","",{iconType:"error"});if(6===this.productInfo.tag&&this.priceWithDiscount>Number(this.balance.money))return void this.$jfkAlert("储值不足","",{iconType:"error"});if(7===this.productInfo.tag&&this.priceWithDiscount>Number(this.point))return void this.$jfkAlert("积分不足","",{iconType:"error"});if(this.checkForm()){var t=this.$jfkToast({isLoading:!0,iconClass:"jfk-loading__snake",duration:-1}),e=this.getOrderParams();(0,g.postOrderCreate)(e).then(function(e){t.close();var s=e.web_data.page_resource.link.wx_pay;location.href=s}).catch(function(){t.close()})}}},watch:{productInfo:function(t){var e=t.tag,s=t.can_gift,i=t.can_mail;1!==e&&2!==e&&7!==e||(this.orderRuleDisabled=!0,this.couponDisabled=!0),6===e&&(this.orderRuleDisabled=!0),7===e&&(this.isIntegral=!0);var o=0;"1"===i&&(o=1),"1"===s&&(o+=2),1===o?this.useType="1":2===o&&(this.useType="2"),this.addressPosition=o},count:function(t){if(t){var e=this.orderRuleDisabled,s=this.couponDisabled;if(w&&w(),e&&s)return;s?this.getActivities():e?this.getCoupons():this.getPackageDiscount()}},lastGetDiscountTimestamp:function(t){if(this.orderRule.rule_type)this.orderRulePicked=!0,this.couponId="-1";else if(this.coupons.length){var e=this.coupons[0];e.usable&&(this.couponId=e.member_card_id),this.orderRulePicked=!1}else this.couponId="-1",this.orderRulePicked=!1}}}},376:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={name:"jfk-coupons",beforeCreate:function(){this.maxHeight=window.innerHeight-50+"px"},data:function(){return{cid:this.couponId}},props:{items:{type:Array,required:!0,default:function(){return[]}},couponId:String},watch:{couponId:function(t){this.cid=t}},methods:{handlePickCoupon:function(t,e){e&&(this.cid===t?this.cid="-1":this.cid=t)},handlePickedCoupon:function(){this.$emit("coupon-picked",this.cid)}}}},431:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=s(375),o=s.n(i),a=s(446),n=s(25),r=n(o.a,a.a,null,null,null);e.default=r.exports},432:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=s(376),o=s.n(i),a=s(473),n=s(25),r=n(o.a,a.a,null,null,null);e.default=r.exports},446:function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-pages jfk-pages__reserve"},[s("div",{staticClass:"jfk-pages__theme"}),t._v(" "),t.productInfo.product_id?s("div",{staticClass:"reserve-box"},[2===t.productInfo.tag&&t.tokenId?s("reverse-killsec-time",{attrs:{countdown:t.countdown},on:{"killsec-finish":t.handleKillsecFinish}}):t._e(),t._v(" "),1===t.addressPosition?s("div",{staticClass:"mail-only jfk-pt-30 jfk-pl-30 jfk-pr-30",on:{click:t.handleChangeAddress}},[s("div",{staticClass:"address card"},["-1"===t.addressId?s("div",{staticClass:"add jfk-flex is-align-middle is-justify-center font-size--28 font-color-extra-light-gray"},[t._m(0)]):s("div",{staticClass:"list jfk-flex is-align-middle"},[s("div",{staticClass:"cont"},[s("div",{staticClass:"list-item"},[s("span",{staticClass:"label font-color-extra-light-gray font-size--24 label--word-3"},[t._v("收件人")]),t._v(" "),s("div",{staticClass:"item-cont font-size--28 font-color-white"},[s("span",{staticClass:"contact"},[t._v(t._s(t.addressPicked.contact))]),t._v(" "),s("span",{staticClass:"phone"},[t._v(t._s(t.addressPicked.phone))])])]),t._v(" "),s("div",{staticClass:"list-item"},[s("span",{staticClass:"label font-color-extra-light-gray font-size--24"},[t._v("收件地址")]),t._v(" "),s("div",{staticClass:"item-cont font-color-white font-size--28"},[t._v(t._s(t.addressPickedDetail))])]),t._v(" "),s("i",{staticClass:"jfk-font icon-user_icon_jump_normal font-color-extra-light-gray font-size--24 list-icon"})]),t._v(" "),s("div",{staticClass:"lace"})])])]):t._e(),t._v(" "),s("div",{staticClass:"product-info jfk-ml-30 jfk-mr-30"},[s("i",{staticClass:"color-golden gap-line"}),t._v(" "),s("div",{staticClass:"name font-color-white font-size--38"},[t._v(t._s(t.productInfo.name))]),t._v(" "),t._m(1),t._v(" "),s("div",{staticClass:"price"},[s("span",{staticClass:"jfk-price product-price-package color-golden-price font-size--54"},[t.isIntegral?t._e():s("i",{staticClass:"jfk-font-number jfk-price__currency"},[t._v("￥")]),t._v(" "),s("i",{staticClass:"jfk-font-number jfk-price__number"},[t._v(t._s(t.productInfo.price_package))])])])]),t._v(" "),3===t.addressPosition?s("div",{staticClass:"mail-gift jfk-ml-30 jfk-mr-30 font-size--28"},[s("p",{staticClass:"use-type-tip font-size--24 font-color-light-gray-common"},[t._v("使用方式")]),t._v(" "),s("div",{staticClass:"item item-address card jfk-mb-30",class:{"is-checked":"1"===t.useType,"font-color-light-gray-common no-checked":"2"===t.useType}},[s("div",{staticClass:"title jfk-flex is-align-middle",class:{"font-color-white":"1"===t.useType},on:{click:function(e){t.handleChangeUseType("1")}}},[s("div",{staticClass:"jfk-flex cont is-justify-space-between"},[s("span",[s("i",{staticClass:"jfk-font icon-mall_icon_orderDetail_post icon",class:{"color-golden":"1"===t.useType}}),t._v("直接邮寄")]),t._v(" "),s("span",{staticClass:"jfk-radio jfk-radio--shape-circle color-golden"},[s("label",{staticClass:"jfk-radio__label"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.useType,expression:"useType"}],attrs:{type:"radio",name:"type",value:"1"},domProps:{checked:"1"===t.useType,checked:t._q(t.useType,"1")},on:{__c:function(e){t.useType="1"}}}),t._v(" "),t._m(2)])])])]),t._v(" "),s("transition",{attrs:{name:"fade"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:"1"===t.useType,expression:"useType === '1'"}],staticClass:"address body tip",on:{click:t.handleChangeAddress}},["-1"===t.addressId?s("div",{staticClass:"add-box"},[s("div",{staticClass:"add font-color-extra-light-gray font-size--28 jfk-flex is-align-middle is-justify-center"},[s("div",{staticClass:"cont"},[s("span",{staticClass:"icon color-golden"},[s("i",{staticClass:"jfk-font icon-booking_icon_addpictures_normal"})]),t._v("新增收货地址\n                ")])])]):s("div",{staticClass:"list jfk-flex is-align-middle"},[s("div",{staticClass:"cont"},[s("div",{staticClass:"list-item"},[s("span",{staticClass:"label font-size--24 font-color-extra-light-gray label--word-3"},[t._v("收件人")]),t._v(" "),s("div",{staticClass:"item-cont font-size--28 font-color-white"},[s("span",{staticClass:"contact"},[t._v(t._s(t.addressPicked.contact))]),t._v(" "),s("span",{staticClass:"phone"},[t._v(t._s(t.addressPicked.phone))])])]),t._v(" "),s("div",{staticClass:"list-item"},[s("span",{staticClass:"label font-size--24 font-color-extra-light-gray"},[t._v("收件地址")]),t._v(" "),s("div",{staticClass:"item-cont font-size--28 font-color-white"},[t._v(t._s(t.addressPickedDetail))])]),t._v(" "),s("i",{staticClass:"jfk-font icon-user_icon_jump_normal font-color-extra-light-gray font-size--24 list-icon"})])])])])],1),t._v(" "),s("div",{staticClass:"item item-gift card",class:{"is-checked":"2"===t.useType,"font-color-light-gray-common  no-checked":"1"===t.useType}},[s("div",{staticClass:"title jfk-flex is-align-middle",class:{"font-color-white":"2"===t.useType},on:{click:function(e){t.handleChangeUseType("2")}}},[s("div",{staticClass:"jfk-flex cont is-justify-space-between"},[s("span",[s("i",{staticClass:"jfk-font icon-mall_icon_orderDetai_gift icon",class:{"color-golden":"2"===t.useType}}),t._v("赠送他人")]),t._v(" "),s("span",{staticClass:"jfk-radio jfk-radio--shape-circle color-golden"},[s("label",{staticClass:"jfk-radio__label"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.useType,expression:"useType"}],attrs:{type:"radio",name:"type",value:"2"},domProps:{checked:"2"===t.useType,checked:t._q(t.useType,"2")},on:{__c:function(e){t.useType="2"}}}),t._v(" "),t._m(3)])])])]),t._v(" "),s("transition",{attrs:{name:"fade"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:"2"===t.useType,expression:"useType === '2'"}],staticClass:"tip jfk-flex is-align-middle jfk-pr-30 font-color-light-gray-common body font-size--24"},[s("div",{staticClass:"box jfk-pos-r"},[s("i",{staticClass:"jfk-font font-size--28 tip-icon icon-booking_icon_question_normal"}),t._v("下单后，购买成功，将礼物打包赠转发给好友，好友点击即可成功领取\n            ")])])])],1)]):t._e(),t._v(" "),s("div",{staticClass:"order-info jfk-pl-30 jfk-pr-30"},[s("form",{staticClass:"jfk-form font-size--28"},[s("div",{staticClass:"form-item"},[s("span",{staticClass:"form-item__label font-color-extra-light-gray"},[t._v("购买数量")]),t._v(" "),s("div",{staticClass:"form-item__body jfk-ta-r"},[s("div",{staticClass:"count jfk-d-ib font-size--32"},[s("jfk-input-number",{attrs:{min:t.min,max:t.max},model:{value:t.count,callback:function(e){t.count=e},expression:"count"}})],1)])]),t._v(" "),s("div",{staticClass:"form-item"},[s("label",[s("span",{staticClass:"form-item__label  font-color-extra-light-gray form-item__label--word-3"},[t._v("购买人")]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.customerInfo.name,expression:"customerInfo.name"}],staticClass:"font-color-white",attrs:{type:"text",placeholder:"请输入购买人"},domProps:{value:t.customerInfo.name},on:{input:function(e){e.target.composing||(t.customerInfo.name=e.target.value)}}}),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.validResult.name.show,expression:"validResult.name.show"}],staticClass:"form-item__status is-error",on:{click:function(e){t.handleHiddenError("name")}}},[s("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),t._v(" "),s("span",{staticClass:"form-item__status-tip"},[s("i",{staticClass:"form-item__status-cont"},[t._v(t._s(t.validResult.name.message))]),t._v(" "),s("i",{staticClass:"form-item__status-trigger"},[t._v("重新输入")])])])])])]),t._v(" "),s("div",{staticClass:"form-item"},[s("span",{staticClass:"form-item__label  font-color-extra-light-gray"},[t._v("联系方式")]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.customerInfo.phone,expression:"customerInfo.phone"}],staticClass:"font-color-white",attrs:{type:"text",placeholder:"请输入购买人手机"},domProps:{value:t.customerInfo.phone},on:{input:function(e){e.target.composing||(t.customerInfo.phone=e.target.value)}}}),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.validResult.phone.show,expression:"validResult.phone.show"}],staticClass:"form-item__status is-error",on:{click:function(e){t.handleHiddenError("phone")}}},[s("i",{staticClass:"form-item__status-icon jfk-font icon-msg_icon_error_norma"}),t._v(" "),s("span",{staticClass:"form-item__status-tip"},[s("i",{staticClass:"form-item__status-cont"},[t._v(t._s(t.validResult.phone.message))]),t._v(" "),s("i",{staticClass:"form-item__status-trigger"},[t._v("重新输入")])])])])]),t._v(" "),t.couponDisabled?t._e():s("div",{staticClass:"form-item form-item__select"},[s("span",{staticClass:"form-item__label  font-color-extra-light-gray form-item__label--word-3"},[t._v("优惠券")]),t._v(" "),s("div",{on:{click:t.handleShowCoupons}},[s("div",{staticClass:"form-item__body"},[s("p",{staticClass:"tip",class:t.couponsClass},[t._v(t._s(t.couponsText))])]),t._v(" "),t._m(4)])]),t._v(" "),t.orderRuleDisabled?t._e():s("div",{staticClass:"form-item",class:{"form-item__switch":t.packageActivityShowSwitch}},[s("span",{staticClass:"form-item__label  font-color-extra-light-gray"},[t._v("优惠活动")]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("p",{staticClass:"tip",class:t.activityClass},[t._v(t._s(t.activityText))])]),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.packageActivityShowSwitch,expression:"packageActivityShowSwitch"}],staticClass:"form-item__foot"},[s("label",{staticClass:"jfk-switch color-golden font-size--30"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.packageActivityChecked,expression:"packageActivityChecked"}],staticClass:"jfk-switch__input",attrs:{type:"checkbox"},domProps:{checked:Array.isArray(t.packageActivityChecked)?t._i(t.packageActivityChecked,null)>-1:t.packageActivityChecked},on:{__c:function(e){var s=t.packageActivityChecked,i=e.target,o=!!i.checked;if(Array.isArray(s)){var a=t._i(s,null);i.checked?a<0&&(t.packageActivityChecked=s.concat(null)):a>-1&&(t.packageActivityChecked=s.slice(0,a).concat(s.slice(a+1)))}else t.packageActivityChecked=o}}}),t._v(" "),s("span",{staticClass:"jfk-switch__core"})])])]),t._v(" "),s("div",{staticClass:"form-item"},[s("span",{staticClass:"form-item__label  font-color-extra-light-gray"},[t._v("支付方式")]),t._v(" "),s("div",{staticClass:"form-item__body"},[s("p",{staticClass:"tip font-color-white"},[t._v(t._s(t.payTypeText))])])])])]),t._v(" "),t._m(5),t._v(" "),s("footer",{staticClass:"footer jfk-footer jfk-clearfix"},[s("div",{staticClass:"order-detail jfk-fl-l",class:{"is-open":t.priceOrderVisible},on:{click:t.handleShowOrderDetail}},[s("span",{staticClass:"price color-golden-price"},[t.isIntegral?t._e():s("i",{staticClass:"price__currency font-size--24"},[t._v("¥")]),t._v(" "),s("i",{staticClass:"price__number font-size--48"},[t._v(t._s(t.priceWithDiscount))])]),t._v(" "),s("span",{directives:[{name:"show",rawName:"v-show",value:t.priceDiscountItem.name,expression:"priceDiscountItem.name"}],staticClass:"detail font-size--24 font-color-extra-light-gray"},[t._v("\n          明细\n        ")])]),t._v(" "),s("div",{staticClass:"control jfk-fl-l"},[s("button",{staticClass:"jfk-button font-size--34 jfk-button--higher jfk-button--suspension jfk-button--free",attrs:{href:"javascript:;"},on:{click:t.handleSubmitOrder}},[t._m(6)])])])],1):t._e(),t._v(" "),s("jfk-support"),t._v(" "),1===t.addressPosition||3===t.addressPosition?[s("div",{directives:[{name:"show",rawName:"v-show",value:t.addressLayerVisible,expression:"addressLayerVisible"}],staticClass:"page-address"},[s("div",{staticClass:"jfk-pages__theme"}),t._v(" "),s("jfk-address",{attrs:{address:t.address,"show-address-list":t.showAddressList,addressId:t.addressId},on:{"update:address":function(e){t.address=e},"pick-address":t.handlePickedAddress}})],1)]:t._e(),t._v(" "),[s("div",{directives:[{name:"show",rawName:"v-show",value:t.couponLayerVisible,expression:"couponLayerVisible"}],staticClass:"page-coupons"},[s("div",{staticClass:"jfk-pages__theme"}),t._v(" "),s("jfk-coupons",{attrs:{items:t.coupons,couponId:t.couponId},on:{"coupon-picked":t.handlePickedCoupon}})],1)],t._v(" "),s("jfk-popup",{staticClass:"jfk-popup__price-detail",attrs:{position:"bottom","modal-class":"jfk-modal__price-detail"},model:{value:t.priceOrderVisible,callback:function(e){t.priceOrderVisible=e},expression:"priceOrderVisible"}},[s("div",{staticClass:"price-detail-box"},[s("div",{staticClass:"price-detail-item item-price jfk-flex is-justify-space-between"},[s("span",{staticClass:"font-size--28 font-color-extra-light-gray price-detail-label"},[t._v("微信价")]),t._v(" "),s("span",{staticClass:"font-size--30 color-golden-price"},[t.isIntegral?t._e():s("i",[t._v("¥")]),t._v(t._s(t.pricePackage))])]),t._v(" "),s("div",{staticClass:"price-detail-item item-discount jfk-flex is-justify-space-between"},[s("span",{staticClass:"font-size--28 font-color-extra-light-gray price-detail-label"},[t._v(t._s(t.priceDiscountItem.name))]),t._v(" "),s("span",{staticClass:"font-size--30 color-golden-price"},[t._v("- "),t.isIntegral?t._e():s("i",[t._v("¥")]),t._v(t._s(t.priceDiscountItem.price))])])])])],2)},o=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"cont"},[s("span",{staticClass:"icon color-golden"},[s("i",{staticClass:"jfk-font icon-booking_icon_addpictures_normal"})]),t._v("新增收货地址\n          ")])},function(){var t=this,e=t.$createElement;return(t._self._c||e)("div",{staticClass:"product-other font-size--24",domProps:{innerHTML:t._s(t.packageInfoHtml)}})},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("span",{staticClass:"jfk-radio__icon"},[s("i",{staticClass:"jfk-font icon-radio_icon_selected_default jfk-radio__icon-icon"})])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("span",{staticClass:"jfk-radio__icon"},[s("i",{staticClass:"jfk-font icon-radio_icon_selected_default jfk-radio__icon-icon"})])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("span",{staticClass:"form-item__foot"},[s("i",{staticClass:"jfk-font icon-user_icon_jump_normal font-color-extra-light-gray"})])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"reserve-tip font-size--24 jfk-pl-30 jfk-pr-30"},[s("div",{staticClass:"tip-title font-color-extra-light-gray-common"},[s("i",{staticClass:"jfk-font icon-msg_icon_prompt_default font-size--28"}),t._v("说明")]),t._v(" "),s("div",{staticClass:"tip-cont font-color-light-gray-common"},[t._v("商品超过有效期不能使用也不能退款")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("span",{staticClass:"jfk-button__text"},[s("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_li_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_ji_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_zhi_qkbys"}),t._v(" "),s("i",{staticClass:"jfk-font jfk-button__text-item icon-font_zh_fu_qkbys"})])}],a={render:i,staticRenderFns:o};e.a=a},473:function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-coupons jfk-pl-30 jfk-pr-30"},[s("ul",{staticClass:"jfk-coupons__list font-size--24 jfk-pt-30",style:{"max-height":t.maxHeight}},t._l(t.items,function(e){return s("li",{key:e.member_card_id,staticClass:"jfk-coupons__box",attrs:{title:e.title}},[s("div",{staticClass:"jfk-coupons__item",class:{"is-disabled":!e.usable,"is-exchange":"3"===e.card_type,"is-offset":"1"===e.card_type,"is-discount":"2"===e.card_type},on:{click:function(s){t.handlePickCoupon(e.member_card_id,e.usable)}}},[s("div",{staticClass:"jfk-coupons__money"},[s("div",{staticClass:"jfk-coupons__money-cont jfk-flex is-align-middle is-justify-center"},["3"===e.card_type?s("span",{staticClass:"jfk-coupons__money-num jfk-font icon-font_zh_dui_qkbys"}):"2"===e.card_type?s("span",{staticClass:"jfk-coupons__money-num"},[s("i",{staticClass:"jfk-font-number jfk-coupons__money-number"},[t._v(t._s(e.discount))]),t._v(" "),s("i",{staticClass:"jfk-font jfk-coupons__money-unit icon-font_zh_zhe_qkbys"})]):"1"===e.card_type?s("span",{staticClass:"jfk-coupons__money-num color-golden",class:"jfk-coupons__money-num--length-"+e.reduce_cost.length},[s("i",{staticClass:"jfk-coupons__money-currency jfk-font-number"},[t._v("￥")]),t._v(" "),s("i",{staticClass:"jfk-coupons__money-number jfk-font-number"},[t._v(t._s(e.reduce_cost))])]):t._e()])]),t._v(" "),s("div",{staticClass:"jfk-coupons__cont"},[s("div",{staticClass:"jfk-coupons__name font-color-white"},[t._v(t._s(e.title))]),t._v(" "),s("div",{staticClass:"jfk-coupons__scope font-color-light-gray-common"},[t._v(t._s(e.scopeType))]),t._v(" "),s("div",{staticClass:"jfk-coupons__expire font-color-light-gray-common"},[t._v(t._s(e.expire_time))])]),t._v(" "),s("div",{staticClass:"jfk-coupons__status"},[s("span",{directives:[{name:"show",rawName:"v-show",value:e.usable,expression:"item.usable"}],staticClass:"jfk-radio jfk-radio--shape-circle color-golden",class:{"is-checked":e.member_card_id===t.cid}},[t._m(0,!0)]),t._v(" "),s("span",{directives:[{name:"show",rawName:"v-show",value:!e.usable,expression:"!item.usable"}],staticClass:"jfk-coupons__status-text color-golden"},[t._v("不可用")])])])])})),t._v(" "),s("div",{staticClass:"jfk-coupons__control"},[s("a",{staticClass:"jfk-button jfk-button--primary jfk-button--higher jfk-button--free",attrs:{href:"javascript:;"},on:{click:t.handlePickedCoupon}},[t._v("确认")])])])},o=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("label",{staticClass:"jfk-radio__label"},[s("span",{staticClass:"jfk-radio__icon"},[s("i",{staticClass:"jfk-font icon-radio_icon_selected_default jfk-radio__icon-icon"})])])}],a={render:i,staticRenderFns:o};e.a=a}});