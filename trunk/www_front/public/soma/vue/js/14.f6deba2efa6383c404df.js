webpackJsonp([14],{199:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={name:"gift",data:function(){return{name:"小马哥"}},created:function(){},methods:{openGift:function(){}}}},273:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=n(199),s=n.n(i),a=n(307),o=n(2),f=o(s.a,a.a,null,null,null);e.default=f.exports},30:function(t,e,n){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var s=n(1),a=i(s),o=n(273),f=i(o);e.default=function(){new a.default({el:"#app",template:"<App/>",components:{App:f.default}})}},307:function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"jfk-pages jfk-pages__gift"},[n("div",{staticClass:"gift-box"},[n("div",{staticClass:"gift-box-envelope"},[n("div",{staticClass:"gift-box-wish jfk-ta-c font-size--36",domProps:{innerHTML:t._s(t.name+"送你一份礼物")}}),t._v(" "),n("div",{staticClass:"gift-box-content"}),t._v(" "),n("div",{staticClass:"gift-box-bg"}),t._v(" "),n("div",{staticClass:"gift-box-btn jfk-ta-c font-size--34",on:{click:t.openGift}},[t._v("打开礼盒")])])])])},s=[],a={render:i,staticRenderFns:s};e.a=a}});