webpackJsonp([19],{151:function(e,t,i){!function(t,i){e.exports=i()}(0,function(){return function(e){function t(n){if(i[n])return i[n].exports;var s=i[n]={i:n,l:!1,exports:{}};return e[n].call(s.exports,s,s.exports,t),s.l=!0,s.exports}var i={};return t.m=e,t.c=i,t.i=function(e){return e},t.d=function(e,i,n){t.o(e,i)||Object.defineProperty(e,i,{configurable:!1,enumerable:!0,get:n})},t.n=function(e){var i=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(i,"a",i),i},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="/",t(t.s=47)}({47:function(e,t,i){"use strict";function n(e){return(e<10?"0":"")+e}Object.defineProperty(t,"__esModule",{value:!0}),t.default=n}})})},158:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{settingId:"-1",pricePackage:this.price}},computed:{specVisible:{get:function(){return this.visible},set:function(e){this.$emit("update:visible",e)}},buttonDisabled:function(){return"-1"===this.settingId}},methods:{handleSubmitSettingId:function(){"-1"!==this.settingId&&(this.specVisible=!1)},onClose:function(e){"cancel"!==e&&this.$emit("submit-setting-id",this.settingId)}},props:{visible:{type:Boolean,required:!0,default:!1},productId:{type:String,required:!0},price:{type:String,required:!0}}}},199:function(e,t,i){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=i(158),a=n(s),r=i(151),l=n(r),o=i(88),c=function(e){var t=e.data,i=e.enddate,n=e.startdate,s=e.settlement,a={};for(var r in t){var l=t[r];l.spec_price=Number(l.spec_price),a[l.date]=t[r]}return{list:a,startdate:n,enddate:i,settlement:s}};t.default={name:"good-spec",mixins:[a.default],data:function(){return{list:{},min:null,max:null,settlement:"",defaultValue:null}},created:function(){var e=this;(0,o.getPackageTickTime)({pid:this.productId,bsn:""}).then(function(t){var i=t.web_data.setting_list,n=c(i);e.list=n.list;var s=new Date(1e3*n.startdate),a=new Date(1e3*n.enddate),r=new Date,l=r;a<r&&(a=r),s>r&&(l=s),e.min=s,e.max=a,e.defaultValue=l,e.settlement=n.settlement}).catch(function(e){})},computed:{today:function(){var e=new Date;return e.getFullYear()+"/"+(0,l.default)(e.getMonth()+1)+"/"+(0,l.default)(e.getDate())}},mounted:function(){var e=window.innerHeight-85-20-34,t=this.$refs.jfkCalendar.$el.querySelector(".jfk-calendar__tools"),i=this.$refs.jfkCalendar.$el.querySelector(".jfk-calendar__thead"),n=this.$refs.jfkCalendar.$el.querySelector(".jfk-calendar__tbody"),s=this.$refs.sectionTip,a=Math.ceil(parseFloat(window.getComputedStyle(t,null).getPropertyValue("height"))),r=Math.ceil(parseFloat(window.getComputedStyle(i,null).getPropertyValue("height"))),l=Math.ceil(parseFloat(window.getComputedStyle(s,null).getPropertyValue("height"))),o=e-a-r-l;n.style.maxHeight=o+"px"},methods:{dateCellRender:function(e,t,i,n){var s=t+"/"+(0,l.default)(i)+"/"+(0,l.default)(n),a=this.list[s],r="";return a&&(r='<p class="font-size--18 font-color-extra-light-gray price">￥'+a.spec_price+"</p>",a.spec_stock>0&&(r+='<p class="font-size--16 font-color-extra-light-gray tip">',a.spec_stock<99?r+="余"+a.spec_stock:r+="充足",r+="</p>")),r},disabledDate:function(e,t,i,n,s){var a=i+"/"+(0,l.default)(n)+"/"+(0,l.default)(s),r=this.list[a];if(a<this.today||!r||"0"===r.spec_stock)return!0},handleDateClick:function(e,t){var i="-1",n=this.price;if(t){var s=this.list[e];i=s.setting_id,n=s.spec_price}this.settingId=i,this.pricePackage=n}}}},244:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=i(199),s=i.n(n),a=i(303),r=i(2),l=r(s.a,a.a,null,null,null);t.default=l.exports},303:function(e,t,i){"use strict";var n=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"good-spec"},[i("jfk-popup",{staticClass:"jfk-popup__specTicket",attrs:{onClose:e.onClose,position:"bottom",showCloseButton:!0,closeOnClickModal:!1,lockScroll:!0},model:{value:e.specVisible,callback:function(t){e.specVisible=t},expression:"specVisible"}},[i("div",{staticClass:"popup-box"},[i("div",{staticClass:"popup-ticket"},[i("div",{ref:"sectionTip",staticClass:"section-title font-size--24 font-color-extra-light-gray"},[e._v("选择日期")]),e._v(" "),i("div",{staticClass:"ticket-calendar"},[i("jfk-calendar",{ref:"jfkCalendar",staticClass:"font-size--28",attrs:{minDate:e.min,maxDate:e.max,defaultValue:e.defaultValue,dateCellRender:e.dateCellRender,disabledDate:e.disabledDate,format:"yyyy/MM/dd"},on:{"date-click":e.handleDateClick}})],1)])])]),e._v(" "),i("div",{directives:[{name:"show",rawName:"v-show",value:e.specVisible,expression:"specVisible"}],staticClass:"good-spec__footer"},[i("div",{staticClass:"jfk-clearfix"},[i("div",{staticClass:"jfk-fl-l price color-golden jfk-flex is-align-middle"},[i("div",{staticClass:"cont "},[i("span",{staticClass:"jfk-price__currency font-size--24"},[e._v("￥")]),e._v(" "),i("span",{staticClass:"jfk-price__number font-size--48"},[e._v(e._s(e.pricePackage))])])]),e._v(" "),i("div",{staticClass:"jfk-fl-r control"},[i("button",{staticClass:"jfk-button jfk-button--free jfk-button--higher jfk-button--primary font-size--34",attrs:{disabled:e.buttonDisabled},on:{click:e.handleSubmitSettingId}},[e._v("立即购买")])])])])],1)},s=[],a={render:n,staticRenderFns:s};t.a=a}});