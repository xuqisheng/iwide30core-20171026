webpackJsonp([34],{319:function(e,a,t){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var n=t(335),s=t.n(n),l=t(444),r=t(25),d=r(s.a,l.a,null,null,null);a.default=d.exports},335:function(e,a,t){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var n=t(173),s=t(26),l=function(e,a){var t=[];if(e.length){var s=0,l=0,r=0;a[0]&&(s=(0,n.findIndex)(e,function(e){return e.region_id===a[0]}),s=-1===s?0:s);var d=e[s].children;a[1]&&(l=(0,n.findIndex)(d,function(e){return e.region_id===a[1]}),l=-1===l?0:l);var i=d[l].children;i&&a[2]&&(r=(0,n.findIndex)(i,function(e){return e.region_id===a[2]}),r=-1===r?0:r),t=[{flex:1,values:e,defaultIndex:s},{flex:1,values:d,defaultIndex:l}],i?t.push({flex:1,values:i,defaultIndex:r}):t.push({flex:0,values:[],defaultIndex:r})}return t};a.default={name:"address-select",data:function(){return{pickValueKey:"region_name",addressData:[],slots:[]}},created:function(){var e=this;(0,s.getExpressTree)().then(function(a){e.$emit("address-data-loaded",!0),e.addressData=a.web_data[0].children,e.slots=l(e.addressData,e.ids)}).catch(function(){e.$emit("address-data-loaded",!1)})},methods:{onValuesChange:function(e,a){if(a){var t=a[0].region_id,n=a[1]&&a[1].region_id,s=l(this.addressData,[t,n]);e.setSlotValues(1,s[1].values),e.setSlotValues(2,s[2].values)}},handleSaveArea:function(){var e=this.$children[0].getValues(),a=e.map(function(e){return{region_id:e.region_id,region_name:e.region_name}});this.$emit("address-picked","ok",a)},handleCancelArea:function(){this.$emit("address-picked","cancel")}},watch:{ids:function(e){var a=l(this.addressData,e),t=this.$children[0];t&&(t.setSlotValues(1,a[1].values),t.setSlotValues(2,a[2].values),t.setValues([a[0].values[a[0].defaultIndex],a[1].values[a[1].defaultIndex],a[2].values?a[2].values[a[2].defaultIndex]:null]))}},props:{ids:{type:Array,required:!0,default:function(){return[]}}}}},444:function(e,a,t){"use strict";var n=function(){var e=this,a=e.$createElement,t=e._self._c||a;return e.slots.length?t("jfk-picker",{staticClass:"font-size--28 jfk-picker__address",attrs:{"rotate-effect":!0,showToolbar:!0,valueKey:e.pickValueKey,slots:e.slots},on:{change:e.onValuesChange}},[t("div",{staticClass:"jfk-flex is-justify-space-between font-size--32"},[t("span",{staticClass:"font-color-extra-light-gray",on:{click:e.handleCancelArea}},[e._v("取消")]),e._v(" "),t("span",{staticClass:"color-golden",on:{click:e.handleSaveArea}},[e._v("完成")])])]):e._e()},s=[],l={render:n,staticRenderFns:s};a.a=l}});