webpackJsonp([28],{165:function(e,t,i){!function(t,i){e.exports=i()}(0,function(){return function(e){function t(n){if(i[n])return i[n].exports;var s=i[n]={i:n,l:!1,exports:{}};return e[n].call(s.exports,s,s.exports,t),s.l=!0,s.exports}var i={};return t.m=e,t.c=i,t.i=function(e){return e},t.d=function(e,i,n){t.o(e,i)||Object.defineProperty(e,i,{configurable:!1,enumerable:!0,get:n})},t.n=function(e){var i=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(i,"a",i),i},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="/",t(t.s=47)}({47:function(e,t,i){"use strict";function n(e){return(e<10?"0":"")+e}Object.defineProperty(t,"__esModule",{value:!0}),t.default=n}})})},182:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={beforeCreate:function(){this.maxHeight=document.documentElement.clientHeight-85-34-50-67},data:function(){return{settingId:"-1",pricePackage:this.price}},computed:{specVisible:{get:function(){return this.visible},set:function(e){this.$emit("update:visible",e)}},buttonDisabled:function(){return"-1"===this.settingId}},methods:{handleSubmitSettingId:function(){"-1"!==this.settingId&&(this.specVisible=!1)},onClose:function(e){"cancel"!==e&&this.$emit("submit-setting-id",this.settingId)}},props:{isIntegral:Boolean,visible:{type:Boolean,required:!0,default:!1},productId:{type:String,required:!0},price:{type:String,required:!0}}}},346:function(e,t,i){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=i(182),a=n(s),l=i(165),r=n(l),o=i(27),c=function(e){var t=e.data,i=e.enddate,n=e.startdate,s=e.settlement,a={};for(var l in t){var r=t[l];r.spec_price=Number(r.spec_price),a[r.date]=t[l]}return{list:a,startdate:n,enddate:i,settlement:s}};t.default={name:"good-spec",mixins:[a.default],data:function(){return{list:{},min:null,max:null,settlement:"",defaultValue:null}},created:function(){var e=this;(0,o.getPackageTickTime)({pid:this.productId,bsn:""}).then(function(t){var i=t.web_data.setting_list,n=c(i);e.list=n.list;var s=new Date(1e3*n.startdate),a=new Date(1e3*n.enddate),l=new Date,r=l;a<l&&(a=l),s>l&&(r=s),e.min=s,e.max=a,e.defaultValue=r,e.settlement=n.settlement}).catch(function(e){})},computed:{today:function(){var e=new Date;return e.getFullYear()+"/"+(0,r.default)(e.getMonth()+1)+"/"+(0,r.default)(e.getDate())}},mounted:function(){var e=this.$refs.jfkCalendar.$el.querySelector(".jfk-calendar__tools"),t=this.$refs.jfkCalendar.$el.querySelector(".jfk-calendar__thead"),i=this.$refs.jfkCalendar.$el.querySelector(".jfk-calendar__tbody"),n=Math.ceil(parseFloat(window.getComputedStyle(e,null).getPropertyValue("height"))),s=Math.ceil(parseFloat(window.getComputedStyle(t,null).getPropertyValue("height"))),a=this.maxHeight-n-s;i.style.maxHeight=a+"px"},methods:{dateCellRender:function(e,t,i,n){var s=t+"/"+(0,r.default)(i)+"/"+(0,r.default)(n),a=this.list[s],l="";return a&&(l='<p class="font-size--18 font-color-extra-light-gray price">￥'+a.spec_price+"</p>",a.spec_stock>0&&(l+='<p class="font-size--16 font-color-extra-light-gray tip">',a.spec_stock<99?l+="余"+a.spec_stock:l+="充足",l+="</p>")),l},disabledDate:function(e,t,i,n,s){var a=i+"/"+(0,r.default)(n)+"/"+(0,r.default)(s),l=this.list[a];if(a<this.today||!l||"0"===l.spec_stock)return!0},handleDateClick:function(e,t){var i="-1",n=this.price;if(t){var s=this.list[e];i=s.setting_id,n=s.spec_price}this.settingId=i,this.pricePackage=n}}}},402:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=i(346),s=i.n(n),a=i(479),l=i(26),r=l(s.a,a.a,null,null,null);t.default=r.exports},479:function(e,t,i){"use strict";var n=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"good-spec"},[i("jfk-popup",{staticClass:"jfk-popup__specTicket",attrs:{onClose:e.onClose,position:"bottom",showCloseButton:!0,closeOnClickModal:!1,lockScroll:!0},model:{value:e.specVisible,callback:function(t){e.specVisible=t},expression:"specVisible"}},[i("div",{staticClass:"popup-box"},[i("div",{staticClass:"popup-ticket"},[i("div",{ref:"sectionTip",staticClass:"section-title font-size--24 font-color-extra-light-gray"},[e._v("选择日期")]),e._v(" "),i("div",{staticClass:"ticket-calendar"},[i("jfk-calendar",{ref:"jfkCalendar",staticClass:"font-size--28",attrs:{minDate:e.min,maxDate:e.max,defaultValue:e.defaultValue,dateCellRender:e.dateCellRender,disabledDate:e.disabledDate,format:"yyyy/MM/dd"},on:{"date-click":e.handleDateClick}})],1)])])]),e._v(" "),i("div",{directives:[{name:"show",rawName:"v-show",value:e.specVisible,expression:"specVisible"}],staticClass:"good-spec__footer"},[i("div",{staticClass:"jfk-clearfix"},[i("div",{staticClass:"jfk-fl-l price color-golden-price jfk-flex is-align-middle"},[i("div",{staticClass:"cont "},[e.isIntegral?e._e():i("span",{staticClass:"jfk-price__currency font-size--24"},[e._v("￥")]),e._v(" "),i("span",{staticClass:"jfk-price__number font-size--48"},[e._v(e._s(e.pricePackage))])])]),e._v(" "),i("div",{staticClass:"jfk-fl-r control"},[i("button",{staticClass:"jfk-button jfk-button--free jfk-button--higher font-size--34 jfk-button--suspension",attrs:{disabled:e.buttonDisabled},on:{click:e.handleSubmitSettingId}},[e._v("立即购买\n        ")])])])])],1)},s=[],a={render:n,staticRenderFns:s};t.a=a}});