webpackJsonp([24],{160:function(t,e,n){!function(e,n){t.exports=n()}(0,function(){return function(t){function e(s){if(n[s])return n[s].exports;var i=n[s]={i:s,l:!1,exports:{}};return t[s].call(i.exports,i,i.exports,e),i.l=!0,i.exports}var n={};return e.m=t,e.c=n,e.i=function(t){return t},e.d=function(t,n,s){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:s})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="/",e(e.s=147)}({0:function(t,e){var n=t.exports="undefined"!=typeof window&&window.Math==Math?window:"undefined"!=typeof self&&self.Math==Math?self:Function("return this")();"number"==typeof __g&&(__g=n)},1:function(t,e,n){t.exports=!n(5)(function(){return 7!=Object.defineProperty({},"a",{get:function(){return 7}}).a})},10:function(t,e,n){var s=n(7),i=n(14);t.exports=n(1)?function(t,e,n){return s.f(t,e,i(1,n))}:function(t,e,n){return t[e]=n,t}},13:function(t,e,n){var s=n(19);t.exports=function(t,e,n){if(s(t),void 0===e)return t;switch(n){case 1:return function(n){return t.call(e,n)};case 2:return function(n,s){return t.call(e,n,s)};case 3:return function(n,s,i){return t.call(e,n,s,i)}}return function(){return t.apply(e,arguments)}}},14:function(t,e){t.exports=function(t,e){return{enumerable:!(1&t),configurable:!(2&t),writable:!(4&t),value:e}}},142:function(t,e,n){"use strict";function s(t){var e=parseInt(t/c),n=e*c,s=parseInt((t-n)/o),a=s*o,l=parseInt((t-n-a)/r);return{dates:e,hours:s,minutes:l,seconds:parseInt((t-n-a-l*r)/i)}}Object.defineProperty(e,"__esModule",{value:!0}),e.default=s;var i=1e3,r=6e4,o=60*r,c=24*o},143:function(t,e,n){"use strict";e.__esModule=!0,e.default=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},144:function(t,e,n){"use strict";e.__esModule=!0;var s=n(150),i=function(t){return t&&t.__esModule?t:{default:t}}(s);e.default=function(){function t(t,e){for(var n=0;n<e.length;n++){var s=e[n];s.enumerable=s.enumerable||!1,s.configurable=!0,"value"in s&&(s.writable=!0),(0,i.default)(t,s.key,s)}}return function(e,n,s){return n&&t(e.prototype,n),s&&t(e,s),e}}()},147:function(t,e,n){"use strict";function s(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var i=n(143),r=s(i),o=n(144),c=s(o),a=n(142),l=s(a),u=function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return(0,r.default)(this,t),this.options=e,!1!==this.options.auto&&this.start(),this}return(0,c.default)(t,[{key:"start",value:function(t){var e=this;if(!e._hasStarted||t){e._hasStarted=!0;var n=this.options,s=n.callback,i=n.start,r=n.end,o=n.rate,c=void 0===o?1e3:o;t&&e.close(),this.status=1,e.interval=setInterval(function(){var t=Date.now(),n=t-i,o=r-t;if(n<0){e.process=1;var c=(0,l.default)(-n);e.dates=c.dates,e.hours=c.hours,e.minutes=c.minutes,e.seconds=c.seconds}else if(o>0||0===n){e.process=2;var a=(0,l.default)(o);e.dates=a.dates,e.hours=a.hours,e.minutes=a.minutes,e.seconds=a.seconds,n>0&&!e._hasStartTrigger?(e._hasStartTrigger=!0,e.status&&s&&s("has-start",t,e)):0===n&&e.status&&s&&s("on-start",t,e)}else e.process=0,e.status&&s&&s(0===o?"on-finish":"has-finish",t,e),e.close();e.status&&s&&s("is-change",t,e)},c)}return this}},{key:"close",value:function(){return void 0!==this.interval&&(clearInterval(this.interval),this.status=0,!0)}}]),t}();e.default=u},150:function(t,e,n){t.exports={default:n(151),__esModule:!0}},151:function(t,e,n){n(152);var s=n(2).Object;t.exports=function(t,e,n){return s.defineProperty(t,e,n)}},152:function(t,e,n){var s=n(16);s(s.S+s.F*!n(1),"Object",{defineProperty:n(7).f})},16:function(t,e,n){var s=n(0),i=n(2),r=n(13),o=n(10),c=function(t,e,n){var a,l,u,f=t&c.F,d=t&c.G,v=t&c.S,p=t&c.P,_=t&c.B,h=t&c.W,k=d?i:i[e]||(i[e]={}),m=k.prototype,b=d?s:v?s[e]:(s[e]||{}).prototype;d&&(n=e);for(a in n)(l=!f&&b&&void 0!==b[a])&&a in k||(u=l?b[a]:n[a],k[a]=d&&"function"!=typeof b[a]?n[a]:_&&l?r(u,s):h&&b[a]==u?function(t){var e=function(e,n,s){if(this instanceof t){switch(arguments.length){case 0:return new t;case 1:return new t(e);case 2:return new t(e,n)}return new t(e,n,s)}return t.apply(this,arguments)};return e.prototype=t.prototype,e}(u):p&&"function"==typeof u?r(Function.call,u):u,p&&((k.virtual||(k.virtual={}))[a]=u,t&c.R&&m&&!m[a]&&o(m,a,u)))};c.F=1,c.G=2,c.S=4,c.P=8,c.B=16,c.W=32,c.U=64,c.R=128,t.exports=c},19:function(t,e){t.exports=function(t){if("function"!=typeof t)throw TypeError(t+" is not a function!");return t}},2:function(t,e){var n=t.exports={version:"2.4.0"};"number"==typeof __e&&(__e=n)},20:function(t,e,n){var s=n(3),i=n(0).document,r=s(i)&&s(i.createElement);t.exports=function(t){return r?i.createElement(t):{}}},21:function(t,e,n){var s=n(3);t.exports=function(t,e){if(!s(t))return t;var n,i;if(e&&"function"==typeof(n=t.toString)&&!s(i=n.call(t)))return i;if("function"==typeof(n=t.valueOf)&&!s(i=n.call(t)))return i;if(!e&&"function"==typeof(n=t.toString)&&!s(i=n.call(t)))return i;throw TypeError("Can't convert object to primitive value")}},26:function(t,e,n){t.exports=!n(1)&&!n(5)(function(){return 7!=Object.defineProperty(n(20)("div"),"a",{get:function(){return 7}}).a})},3:function(t,e){t.exports=function(t){return"object"==typeof t?null!==t:"function"==typeof t}},5:function(t,e){t.exports=function(t){try{return!!t()}catch(t){return!0}}},7:function(t,e,n){var s=n(9),i=n(26),r=n(21),o=Object.defineProperty;e.f=n(1)?Object.defineProperty:function(t,e,n){if(s(t),e=r(e,!0),s(n),i)try{return o(t,e,n)}catch(t){}if("get"in n||"set"in n)throw TypeError("Accessors not supported!");return"value"in n&&(t[e]=n.value),t}},9:function(t,e,n){var s=n(3);t.exports=function(t){if(!s(t))throw TypeError(t+" is not an object!");return t}}})})},161:function(t,e,n){!function(e,n){t.exports=n()}(0,function(){return function(t){function e(s){if(n[s])return n[s].exports;var i=n[s]={i:s,l:!1,exports:{}};return t[s].call(i.exports,i,i.exports,e),i.l=!0,i.exports}var n={};return e.m=t,e.c=n,e.i=function(t){return t},e.d=function(t,n,s){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:s})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="/",e(e.s=47)}({47:function(t,e,n){"use strict";function s(t){return(t<10?"0":"")+t}Object.defineProperty(e,"__esModule",{value:!0}),e.default=s}})})},201:function(t,e,n){"use strict";function s(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var i=n(72),r=s(i),o=n(160),c=s(o),a=n(161),l=s(a),u=n(94);e.default={name:"product-killsec",data:function(){return{interval:0,visible:!1,killsecTime:{},killsecParams:{dates:"0",hours:"00",minutes:"00",seconds:"00",process:0},killsecStock:"0",killsecTotal:"0",killsecPercent:100,showKillsecModule:!1}},methods:{getKillsecStockInfo:function(){var t=this;(0,u.getKillsecStock)({act_id:this.killsec.act_id}).then(function(e){var n=e.web_data,s=n.percent,i=n.stock,r=n.total;t.killsecStock=i,t.killsecTotal=r,t.killsecPercent=s}).catch(function(){t.stopCycleGetKillsecStock(),t.$emit("killsec-finish",0),t.visible=!1,t.killsecTime.close&&t.killsecTime.close()})},stopCycleGetKillsecStock:function(){clearInterval(this.interval)}},created:function(){var t=this,e=this.killsec,n=e.finish,s=e.killsec_time,i=e.end_time,o=e.stock_reflesh_rate,a=Date.now(),u=new Date(s).getTime(),f=new Date(i).getTime(),d=!1;!n&&a<f?(t.getKillsecStockInfo(),t.killsecTime=new c.default({start:u,end:f,callback:function(e,n,s){t.visible=!0,0===s.process&&(t.visible=!1,s.close(),t.stopCycleGetKillsecStock(),t.$emit("killsec-finish",2)),2===s.process&&(t.stopCycleGetKillsecStock(),t.interval=setInterval(function(){t.getKillsecStockInfo()},o),d||(t.$emit("killsec-start",1),d=!0)),t.killsecParams=(0,r.default)({},t.killsecParams,{dates:""+s.dates,hours:(0,l.default)(s.hours),minutes:(0,l.default)(s.minutes),seconds:(0,l.default)(s.seconds),process:s.process})}})):(t.$emit("killsec-finish",n?1:2),t.visible=!1)},props:{killsec:{type:Object,required:!0}},beforeDestroy:function(){this.killsecTime.close&&this.killsecTime.close()}}},244:function(t,e,n){var s=n(2)(n(201),n(280),null,null);t.exports=s.exports},280:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{directives:[{name:"show",rawName:"v-show",value:t.visible,expression:"visible"}],staticClass:"killsec"},[n("div",{staticClass:"layer"}),t._v(" "),n("div",{staticClass:"box jfk-flex is-align-middle"},[n("div",{staticClass:"cont"},[n("div",{staticClass:"time"},[n("span",{staticClass:"tip jfk-d-ib color-golden font-size--24"},[t._v("距离"+t._s(1===t.killsecParams.process?"开始":"结束"))]),t._v(" "),n("span",{staticClass:"clock jfk-d-ib"},[n("i",{directives:[{name:"show",rawName:"v-show",value:t.killsecParams.dates>0,expression:"killsecParams.dates > 0"}],staticClass:"num date jfk-d-ib font-color-white font-size--48"},[t._v(t._s(t.killsecParams.dates))]),t._v(" "),n("i",{directives:[{name:"show",rawName:"v-show",value:t.killsecParams.dates>0,expression:"killsecParams.dates > 0"}],staticClass:"unit font-size--20 jfk-d-ib font-color-light-gray"},[t._v("天")]),t._v(" "),n("i",{staticClass:"num jfk-d-ib font-color-white font-size--48"},[t._v(t._s(t.killsecParams.hours))]),t._v(" "),n("i",{staticClass:"unit font-size--20 jfk-d-ib font-color-light-gray"},[t._v("时")]),t._v(" "),n("i",{staticClass:"num jfk-d-ib font-color-white font-size--48"},[t._v(t._s(t.killsecParams.minutes))]),t._v(" "),n("i",{staticClass:"unit font-size--20 jfk-d-ib font-color-light-gray"},[t._v("分")]),t._v(" "),n("i",{staticClass:"num jfk-d-ib font-color-white font-size--48"},[t._v(t._s(t.killsecParams.seconds))]),t._v(" "),n("i",{staticClass:"unit font-size--20 jfk-d-ib font-color-light-gray"},[t._v("秒")])])]),t._v(" "),t._m(0),t._v(" "),n("div",{staticClass:"number font-size--24 font-color-light-gray"},[n("span",{staticClass:"tip jfk-d-ib font-size--22"},[t._v("剩余库存")]),t._v(" "),n("span",{staticClass:"stock jfk-d-ib"},[t._v(t._s(t.killsecStock)+"/")]),t._v(" "),n("span",{staticClass:"total jfk-d-ib font-size--20"},[t._v(t._s(t.killsecTotal))])])])]),t._v(" "),t._m(1)])},staticRenderFns:[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"process"},[n("div",{staticClass:"line"})])},function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"mask"},[n("i",{staticClass:"jfk-font icon-font_zh_miao_fzdbs miao"}),t._v(" "),n("i",{staticClass:"jfk-font icon-font_zh_sha_fzdbs sha"})])}]}}});