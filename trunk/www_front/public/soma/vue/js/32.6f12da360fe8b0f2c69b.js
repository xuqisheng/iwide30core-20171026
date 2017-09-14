webpackJsonp([32],{186:function(e,s,t){"use strict";Object.defineProperty(s,"__esModule",{value:!0}),s.default={beforeCreate:function(){this.maxHeight=document.documentElement.clientHeight-85-34-50-67},data:function(){return{settingId:"-1",pricePackage:this.price}},computed:{specVisible:{get:function(){return this.visible},set:function(e){this.$emit("update:visible",e)}},buttonDisabled:function(){return"-1"===this.settingId}},methods:{handleSubmitSettingId:function(){"-1"!==this.settingId&&(this.specVisible=!1)},onClose:function(e){"cancel"!==e&&this.$emit("submit-setting-id",this.settingId)}},props:{isIntegral:Boolean,visible:{type:Boolean,required:!0,default:!1},productId:{type:String,required:!0},price:{type:String,required:!0}}}},349:function(e,s,t){"use strict";function i(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(s,"__esModule",{value:!0});var c=t(28),a=i(c),n=t(186),o=i(n),p=t(26),l=function(e){return e.map(function(e,s){return s+"_"+e})},r=function(e){var s={},t=!1,i=e.data,c=e.spec_name_id;for(var a in i)!function(e){var c=l(i[e].spec_name_id);c.forEach(function(a){s[a]||(s[a]={disabled:!1,ids:[],paths:[]}),0!==Number(i[e].stock)?(c.forEach(function(e){-1===s[a].ids.indexOf(e)&&s[a].ids.push(e)}),s[a].paths.push(c.join("/"))):t=!0})}(a);if(c.length>2)for(var a in s)s[a].ids.length<2&&(s[a].disabled=!0);return{specMaps:s,hasDisabledSpecItems:t}},d=function(e){var s={},t=[];return e.forEach(function(e,i){s[i]=[],t.push(-1),e.forEach(function(e){s[i].push(i+"_"+e)})}),{rows:s,keys:t}},u=/^(\d+)_/;s.default={name:"good-spec",mixins:[o.default],data:function(){return{spec:{},specKeys:[],hasDisabledSpecItems:!1,specMaps:{},specRows:{},enableSpecItems:[]}},created:function(){var e=this;(0,p.getPackageSpec)({pid:this.productId}).then(function(s){if(e.spec=(0,a.default)({},e.spec,s.web_data),e.spec.spec_type){var t=d(e.spec.spec_name_id);e.specKeys=t.keys,e.specRows=t.rows;var i=r(e.spec);e.hasDisabledSpecItems=i.hasDisabledSpecItems,e.specMaps=(0,a.default)({},e.specMaps,i.specMaps)}}).catch(function(e){})},watch:{specKeys:function(e){if(-1===e.indexOf(-1)){var s=this.getSettingId();s&&(this.settingId=s,this.pricePackage=this.spec.data[s].spec_price)}else this.settingId="-1"}},methods:{getFullSpecItems:function(e){var s=this;if(e.length>1){var t=[];e.forEach(function(e){t=t.concat(s.specMaps[e].paths)});var i={},c=[];return t.forEach(function(e){if(i[e]){var s=e.split("/");c=c.concat(s)}else i[e]=1}),c}return this.specMaps[e[0]].ids},changeSpecItemsStatus:function(e,s){var t=this;e.forEach(function(e){-1!==s.indexOf(e)?t.specMaps[e].disabled=!1:t.specMaps[e].disabled=!0})},resetSpecItemsStatus:function(){for(var e in this.specMaps)this.specMaps[e].ids.length<2?this.specMaps[e].disabled=!0:this.specMaps[e].disabled=!1},detectionSpecStatus:function(){if(!this.hasDisabledSpecItems)return!0;var e=[],s=[],t=[],i=this;if(this.specKeys.forEach(function(c,a){var n=a+"_"+c,o=i.specRows[a];-1!==c?(e.push(a),s.push(n)):t=t.concat(o)}),s.length){var c=this.getFullSpecItems(s);this.changeSpecItemsStatus(t,c),e.length>1&&s.forEach(function(e){var t=s.filter(function(s){return s!==e}),c=i.getFullSpecItems(t),a=u.exec(e)[1],n=i.specRows[a];i.changeSpecItemsStatus(n,c)})}else this.resetSpecItemsStatus()},getSettingId:function(){var e=this.specKeys.join(""),s=this.spec.spec_id.indexOf(e);if(s>-1)return this.spec.setting_id[s]},handleSpecChange:function(e,s,t){var i=t+"_"+e;this.specMaps[i].disabled||(e===this.specKeys[t]&&(e=-1),this.$set(this.specKeys,t,e),this.detectionSpecStatus())}}}},405:function(e,s,t){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var i=t(349),c=t.n(i),a=t(455),n=t(25),o=n(c.a,a.a,null,null,null);s.default=o.exports},455:function(e,s,t){"use strict";var i=function(){var e=this,s=e.$createElement,t=e._self._c||s;return t("div",{staticClass:"good-spec"},[t("jfk-popup",{staticClass:"jfk-popup__specTicket",attrs:{onClose:e.onClose,position:"bottom",showCloseButton:!0,closeOnClickModal:!1,lockScroll:!0},model:{value:e.specVisible,callback:function(s){e.specVisible=s},expression:"specVisible"}},[t("div",{staticClass:"popup-box"},[e.spec.spec_type?t("div",{staticClass:"popup-spec"},[t("div",{staticClass:"section-title font-size--24 font-color-extra-light-gray"},[e._v("选择规格")]),e._v(" "),t("ul",{staticClass:"list",style:{"max-height":e.maxHeight+"px"}},e._l(e.spec.spec_type,function(s,i){return t("li",{key:e.spec.spec_type_id[i],staticClass:"item"},[t("div",{staticClass:"title font-size--24 font-color-light-gray-common"},[e._v(e._s(s))]),e._v(" "),t("div",{staticClass:"cont"},e._l(e.spec.spec_name_id[i],function(s,c){return t("div",{key:s,staticClass:"spec-item jfk-d-ib color-golden font-size--30 jfk-radio jfk-radio--shape-rect",class:{"is-checked":s===e.specKeys[i],"is-disabled":e.specMaps[i+"_"+s].disabled},on:{click:function(t){e.handleSpecChange(s,c,i)}}},[t("label",{staticClass:"jfk-radio__label"},[t("div",{staticClass:"jfk-radio__text"},[t("span",[e._v(e._s(e.spec.spec_name[i][c]))])]),e._v(" "),t("div",{staticClass:"jfk-radio__icon"},[t("i",{staticClass:"jfk-font icon-radio_icon_selected_default jfk-radio__icon-icon"})])])])}))])}))]):e._e()])]),e._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:e.specVisible,expression:"specVisible"}],staticClass:"good-spec__footer"},[t("div",{staticClass:"jfk-clearfix"},[t("div",{staticClass:"jfk-fl-l price color-golden-price jfk-flex is-align-middle"},[t("div",{staticClass:"cont "},[e.isIntegral?e._e():t("span",{staticClass:"jfk-price__currency font-size--24"},[e._v("￥")]),e._v(" "),t("span",{staticClass:"jfk-price__number font-size--48"},[e._v(e._s(e.pricePackage))])])]),e._v(" "),t("div",{staticClass:"jfk-fl-r control"},[t("button",{staticClass:"jfk-button jfk-button--free jfk-button--suspension jfk-button--higher font-size--34",attrs:{disabled:e.buttonDisabled},on:{click:e.handleSubmitSettingId}},[e._v("立即购买")])])])])],1)},c=[],a={render:i,staticRenderFns:c};s.a=a}});