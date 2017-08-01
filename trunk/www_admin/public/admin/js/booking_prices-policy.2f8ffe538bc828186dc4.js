webpackJsonp([26],{229:function(e,o,t){"use strict";function r(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(o,"__esModule",{value:!0});var a=t(139),l=r(a),s=t(199),i=t(202),n=t(212),m=r(n),f=t(203),c=/^[1-9]\d*$/,u=/^\d+$/;o.default={data:function(){var e=this,o=function(o,t,r){return"athour"!==e.form.type||e.form.bookTimeMod?r():r(new Error(o.message))},t=function(o,t,r){if("athour"===e.form.type){if(!e.form.bookTimeStart)return r(new Error("请输入最早到店时间"));if(e.form.bookTimeEnd&&e.form.bookTimeStart>=e.form.bookTimeEnd)return r(new Error("最早到店时间应该小于最迟到店时间"))}return r()},r=function(o,t,r){if("athour"===e.form.type){if(!e.form.bookTimeEnd)return r(new Error("请输入最迟到店时间"));if(e.form.bookTimeStart&&e.form.bookTimeStart>=e.form.bookTimeEnd)return r(new Error("最迟到店时间应该大于最早到店时间"))}return r()},a=function(e,o,t){return""===o||c.test(o)?t():t(new Error(e.message))};return{policyRules:{bookTimeMod:[{validator:o,message:"时租价必须选择时间间隔",trigger:"change"}],bookTimeStart:[{validator:t,trigger:"blur"}],bookTimeEnd:[{validator:r,trigger:"blur"}],preD:[{trigger:"blur",message:"提前预定天数为非负整数",validator:function(e,o,t){return""===o||u.test(o)?t():t(new Error(e.message))}}],mxn:[{trigger:"blur",message:"单次最大间数为正整数",validator:a}],minDay:[{trigger:"blur",message:"最小可定天数为正整数",validator:a},{trigger:"blur",validator:function(o,t,r){return t&&e.form.mxd&&e.$refs.policyForm.validateField("mxd"),r()}}],mxd:[{trigger:"blur",message:"最大可定天数为正整数",validator:a},{trigger:"blur",message:"最大可定天数必须不小于最小可定天数",validator:function(o,t,r){return t&&+e.form.minDay>+t?r(new Error(o.message)):r()}}],couponNum:[{trigger:"blur",validator:function(o,t,r){if(0===e.form.couponNoUse){if(""===t)return r(new Error("请输入用券的使用张数"));if(!c.test(t))return r(new Error("用券的使用张数必须为正整数"))}return r()}}],wxPayFavour:[{trigger:"blur",message:"微信支付立减必须为最多两位小数的正数",validator:function(o,t,r){return e.hasWxPayInPayways&&t&&!(0,f.stringIsValidMoney)(t)?r(new Error(o.message)):r()}}],sort:[{trigger:"blur",message:"排序必须为正整数",validator:a}],status:[{trigger:"change",message:"必须选择一个状态",required:!0}]}}},methods:(0,l.default)({},(0,s.mapMutations)("form",[i.CODE_INFO]),(0,s.mapMutations)([i.UPDATE_PRICE_STEP]),{handlePrevStep:function(){this[i.UPDATE_PRICE_STEP]({increment:!1})},handleNextStep:function(){var e=this;this.$refs.policyForm.validate(function(o){o&&e[i.UPDATE_PRICE_STEP]({increment:!0})})}}),computed:(0,l.default)({},(0,s.mapState)("config",["confPayWays","confBookTimeMod","confCouponNumType","confCodeStatus","confCouponTypes"]),(0,s.mapState)(["form"]),(0,s.mapGetters)("form",["hasWxPayInPayways"])),beforeRouteEnter:function(e,o,t){if(!o.name)return t({path:m.default[0].path});t(function(e){var o={},t={};e.form.payWays.forEach(function(r){o[r]=e.form.delayTime[r]||(e.form.delay_time&&e.form.delay_time[r]||"12")+":00",t[r]=e.form.retainTime[r]||(e.form.retain_time&&e.form.retain_time[r]||"18")+":00"}),e[i.CODE_INFO]({delayTime:o,retainTime:t})})}}},335:function(e,o,t){o=e.exports=t(75)(!1),o.push([e.i,".policy-coupon-num-type[data-v-d826f22c]{width:145px}.policy-coupon-num-type-box[data-v-d826f22c]{max-width:210px;margin-left:15px}",""])},366:function(e,o,t){var r=t(335);"string"==typeof r&&(r=[[e.i,r,""]]),r.locals&&(e.exports=r.locals);t(76)("c403230e",r,!0)},375:function(e,o,t){t(366);var r=t(140)(t(229),t(425),"data-v-d826f22c",null);e.exports=r.exports},425:function(e,o){e.exports={render:function(){var e=this,o=e.$createElement,t=e._self._c||o;return t("div",{staticClass:"jfk-pages__modules jfk-pages__modules-policy"},[t("el-form",{ref:"policyForm",attrs:{rules:e.policyRules,model:e.form,"label-width":"150px"}},[t("div",{staticClass:"jfk-fieldset"},[t("div",{staticClass:"jfk-fieldset__hd"},[t("div",{staticClass:"jfk-fieldset__title"},[e._v("\n          预定政策\n        ")])]),e._v(" "),t("el-row",[t("el-col",{attrs:{lg:12,md:24}},[t("el-row",{attrs:{type:"flex",align:"middle"}},[t("div",{staticClass:"policy-time-label"},[e._v("保留时间")]),e._v(" "),t("div",{staticClass:"policy-time-content"},e._l(e.form.payWays,function(o){return t("el-form-item",{key:o,attrs:{label:e.confPayWays[o].pay_name+" 入住日期","label-width":"250px"}},[t("el-time-select",{attrs:{editable:!1,"picker-options":{start:"00:00",end:"23:00",step:"01:00"},placeholder:"入住时间"},model:{value:e.form.retainTime[o],callback:function(t){var r=e.form.retainTime,a=o;Array.isArray(r)?r.splice(a,1,t):e.form.retainTime[o]=t},expression:"form.retainTime[item]"}})],1)}))])],1),e._v(" "),t("el-col",{attrs:{lg:12,md:24}},[t("el-row",{attrs:{type:"flex",align:"middle"}},[t("div",{staticClass:"policy-time-label"},[e._v("退房时间")]),e._v(" "),t("div",{staticClass:"policy-time-content"},e._l(e.form.payWays,function(o){return t("el-form-item",{key:o,attrs:{label:e.confPayWays[o].pay_name+" 离店日期","label-width":"250px"}},[t("el-time-select",{attrs:{editable:!1,"picker-options":{start:"00:00",end:"23:00",step:"01:00"},placeholder:"退房时间"},model:{value:e.form.delayTime[o],callback:function(t){var r=e.form.delayTime,a=o;Array.isArray(r)?r.splice(a,1,t):e.form.delayTime[o]=t},expression:"form.delayTime[item]"}})],1)}))])],1)],1)],1),e._v(" "),t("div",{staticClass:"jfk-fieldset"},[t("div",{staticClass:"jfk-fieldset__hd"},[t("div",{staticClass:"jfk-fieldset__title"},[e._v("\n          高级预订政策\n        ")])]),e._v(" "),t("el-row",[t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"提前预定天数",prop:"preD"}},[t("el-input",{staticClass:"jfk-input__fixed-width--110",model:{value:e.form.preD,callback:function(o){e.form.preD="string"==typeof o?o.trim():o},expression:"form.preD"}},[t("template",{slot:"append"},[e._v("天")])],2)],1)],1),e._v(" "),t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"单次最大间数",prop:"mxn"}},[t("el-input",{staticClass:"jfk-input__fixed-width--110",model:{value:e.form.mxn,callback:function(o){e.form.mxn="string"==typeof o?o.trim():o},expression:"form.mxn"}},[t("template",{slot:"append"},[e._v("间")])],2)],1)],1),e._v(" "),t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"可定天数"}},[t("el-col",{attrs:{span:11}},[t("el-form-item",{attrs:{prop:"minDay"}},[t("el-input",{staticClass:"jfk-input__diy-icon",attrs:{placeholder:"最小可定天数"},model:{value:e.form.minDay,callback:function(o){e.form.minDay=o},expression:"form.minDay"}},[t("template",{slot:"prepend"},[t("i",{staticClass:"jfkfont icon-ipt_icon_gte_default"})])],2)],1)],1),e._v(" "),t("el-col",{attrs:{span:11,offset:1}},[t("el-form-item",{attrs:{prop:"mxd"}},[t("el-input",{staticClass:"jfk-input__diy-icon",attrs:{placeholder:"最大可定天数"},model:{value:e.form.mxd,callback:function(o){e.form.mxd=o},expression:"form.mxd"}},[t("template",{slot:"prepend"},[t("i",{staticClass:"jfkfont icon-ipt_icon_lte_default"})])],2)],1)],1)],1)],1),e._v(" "),t("el-col",{directives:[{name:"show",rawName:"v-show",value:"athour"===e.form.type,expression:"form.type === 'athour'"}],attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"到店时间段",required:""}},[t("el-form-item",{staticClass:"jfk-d-ib",attrs:{prop:"bookTimeStart"}},[t("el-time-select",{attrs:{placeholder:"起始时间","picker-options":{start:"00:00",step:"01:00",end:"23:00"}},model:{value:e.form.bookTimeStart,callback:function(o){e.form.bookTimeStart=o},expression:"form.bookTimeStart"}})],1),e._v(" "),t("span",[e._v("-")]),e._v(" "),t("el-form-item",{staticClass:"jfk-d-ib",attrs:{prop:"bookTimeEnd"}},[t("el-time-select",{attrs:{placeholder:"结束时间","picker-options":{start:"00:00",step:"01:00",end:"23:00",minTime:e.form.bookTimeStart}},model:{value:e.form.bookTimeEnd,callback:function(o){e.form.bookTimeEnd=o},expression:"form.bookTimeEnd"}})],1)],1)],1),e._v(" "),t("el-col",{directives:[{name:"show",rawName:"v-show",value:"athour"===e.form.type,expression:"form.type === 'athour'"}],attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"时间间隔",prop:"bookTimeMod",required:""}},[t("el-select",{model:{value:e.form.bookTimeMod,callback:function(o){e.form.bookTimeMod=o},expression:"form.bookTimeMod"}},e._l(e.confBookTimeMod,function(e,o){return t("el-option",{key:o,attrs:{label:e,value:o}})}))],1)],1)],1)],1),e._v(" "),t("div",{staticClass:"jfk-fieldset"},[t("div",{staticClass:"jfk-fieldset__hd"},[t("div",{staticClass:"jfk-fieldset__title"},[e._v("\n          营销规则\n        ")])]),e._v(" "),t("el-row",[t("el-col",{directives:[{name:"show",rawName:"v-show",value:e.hasWxPayInPayways,expression:"hasWxPayInPayways"}],attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"微信支付立减",prop:"wxPayFavour"}},[t("el-input",{staticClass:"jfk-input__fixed-width--110",model:{value:e.form.wxPayFavour,callback:function(o){e.form.wxPayFavour=e._n(o)},expression:"form.wxPayFavour"}},[t("template",{slot:"append"},[e._v("元")])],2)],1)],1),e._v(" "),t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"用券规则"}},[t("el-radio",{attrs:{label:1},model:{value:e.form.couponNoUse,callback:function(o){e.form.couponNoUse=o},expression:"form.couponNoUse"}},[e._v("不可用")]),e._v(" "),t("el-radio",{attrs:{label:0},model:{value:e.form.couponNoUse,callback:function(o){e.form.couponNoUse=o},expression:"form.couponNoUse"}},[e._v("可用")]),e._v(" "),t("el-form-item",{directives:[{name:"show",rawName:"v-show",value:0===e.form.couponNoUse,expression:"form.couponNoUse === 0"}],staticClass:"jfk-d-ib policy-coupon-num-type-box",attrs:{prop:"couponNum"}},[t("el-input",{attrs:{placeholder:"请输入使用张数"},model:{value:e.form.couponNum,callback:function(o){e.form.couponNum=o},expression:"form.couponNum"}},[t("el-select",{staticClass:"policy-coupon-num-type",attrs:{placeholder:"请选择"},slot:"prepend",model:{value:e.form.couponNumType,callback:function(o){e.form.couponNumType=e._n(o)},expression:"form.couponNumType"}},e._l(e.confCouponNumType,function(e,o){return t("el-option",{key:o,attrs:{label:"按"+e+"使用张数",value:o}})}))],1)],1)],1)],1),e._v(" "),Object.keys(e.confCouponTypes).length?t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"券关联"}},[t("el-select",{model:{value:e.form.couprel,callback:function(o){e.form.couprel=o},expression:"form.couprel"}},e._l(e.confCouponTypes,function(e,o){return t("el-option",{key:o,attrs:{label:e.title,value:o}})}))],1)],1):e._e(),e._v(" "),t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"积分兑换"}},[t("el-radio",{attrs:{label:1},model:{value:e.form.bonusNoPart,callback:function(o){e.form.bonusNoPart=o},expression:"form.bonusNoPart"}},[e._v("不可用")]),e._v(" "),t("el-radio",{attrs:{label:0},model:{value:e.form.bonusNoPart,callback:function(o){e.form.bonusNoPart=o},expression:"form.bonusNoPart"}},[e._v("可用")])],1)],1),e._v(" "),t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"积分与券"}},[t("el-radio",{attrs:{label:1},model:{value:e.form.bonusPoc,callback:function(o){e.form.bonusPoc=o},expression:"form.bonusPoc"}},[e._v("不可同用")]),e._v(" "),t("el-radio",{attrs:{label:0},model:{value:e.form.bonusPoc,callback:function(o){e.form.bonusPoc=o},expression:"form.bonusPoc"}},[e._v("可同用")])],1)],1),e._v(" "),0!==e.form.isPms?t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"使用pms券"}},[t("el-radio",{attrs:{label:0},model:{value:e.form.couponIsPms,callback:function(o){e.form.couponIsPms=o},expression:"form.couponIsPms"}},[e._v("不使用")]),e._v(" "),t("el-radio",{attrs:{label:1},model:{value:e.form.couponIsPms,callback:function(o){e.form.couponIsPms=o},expression:"form.couponIsPms"}},[e._v("使用")])],1)],1):e._e(),e._v(" "),0!==e.form.isPms?t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"pms代码"}},[t("el-col",{attrs:{span:8}},[t("el-input",{model:{value:e.form.externalCode,callback:function(o){e.form.externalCode=o},expression:"form.externalCode"}})],1)],1)],1):e._e(),e._v(" "),"0"===e.form.isPackages?t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"仅用于套餐预订"}},[t("el-radio",{attrs:{label:0},model:{value:e.form.packageOnly,callback:function(o){e.form.packageOnly=o},expression:"form.packageOnly"}},[e._v("否")]),e._v(" "),t("el-radio",{attrs:{label:1},model:{value:e.form.packageOnly,callback:function(o){e.form.packageOnly=o},expression:"form.packageOnly"}},[e._v("是")])],1)],1):e._e()],1)],1),e._v(" "),t("div",{staticClass:"jfk-fieldset"},[t("div",{staticClass:"jfk-fieldset__hd"},[t("div",{staticClass:"jfk-fieldset__title"},[e._v("\n          其他\n        ")])]),e._v(" "),t("el-row",[t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"排序",prop:"sort"}},[t("el-input",{staticClass:"jfk-input__fixed-width--110",model:{value:e.form.sort,callback:function(o){e.form.sort=o},expression:"form.sort"}})],1)],1),e._v(" "),t("el-col",{attrs:{lg:12,md:24}},[t("el-form-item",{attrs:{label:"状态",prop:"status"}},[t("el-radio-group",{model:{value:e.form.status,callback:function(o){e.form.status=o},expression:"form.status"}},e._l(e.confCodeStatus,function(o,r){return t("el-radio",{key:r,attrs:{label:r}},[e._v("\n                "+e._s(o)+"\n              ")])}))],1)],1)],1)],1),e._v(" "),t("el-row",{attrs:{type:"flex",justify:"center"}},[t("el-button",{staticClass:"jfk-button--middle",attrs:{size:"large"},nativeOn:{click:function(o){o.preventDefault(),e.handlePrevStep(o)}}},[e._v("上一步")]),e._v(" "),t("el-button",{staticClass:"jfk-button--middle",attrs:{type:"primary",size:"large"},nativeOn:{click:function(o){o.preventDefault(),e.handleNextStep(o)}}},[e._v("下一步")])],1)],1)],1)},staticRenderFns:[]}}});