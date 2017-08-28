webpackJsonp([34],{414:function(e,t,o){"use strict";function s(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var a=o(151),n=s(a),i=o(210),l=o(213),d=o(236),r=s(d),u=function(e,t){var o=t.$refs.goodsInfoTable.store.states.selection;e.forEach(function(e){t.goodInfoItems.items[e.goods_id]&&-1===o.indexOf(e)&&o.push(e)})};t.default={data:function(){return{page:1,size:10,goodItemsMaxSelectionCount:10,pageHasHardCodeSelection:{}}},methods:(0,n.default)({},(0,i.mapActions)("goods",[l.GET_HOTEL_GOODS_ACTION]),(0,i.mapMutations)("goods",[l.CLEAN_HOTEL_GOODS]),(0,i.mapMutations)([l.UPDATE_PRICE_STEP]),(0,i.mapMutations)("form",[l.CODE_INFO,l.UPDATE_HOTEL_NUM]),{handleChangeGoodMaxNum:function(e,t){this[l.UPDATE_HOTEL_NUM]({gs_id:e,num:t})},handleSelectionGoodsChange:function(e){var t={};e.forEach(function(e){t[e.goods_id]={gs_id:e.goods_id,num:e.good_max_num}}),this[l.CODE_INFO]({goods_info:{items:t}})},handleGoodInfosCurrentChange:function(e){var t=this.size*(e-1);this.goodIds[t]||this[l.GET_HOTEL_GOODS_ACTION]({page:this.page,size:this.size}),this.page=e},goodsInfoSelectable:function(e,t){return!(this.goodInfoItemsNumber>=this.goodItemsMaxSelectionCount)||!!this.goodInfoItems.items[e.goods_id]},handleNextStep:function(){0===this.goodInfoItemsNumber?this.$alert("开启套餐属性必须关联商品",{title:"温馨提示",type:"error"}):this.goodInfoItemsNumber>this.goodItemsMaxSelectionCount?this.$alert("套餐最多关联"+this.goodItemsMaxSelectionCount+"个商品，请删除多余商品再进行下一步",{title:"温馨提示",type:"error"}):this[l.UPDATE_PRICE_STEP]({increment:!0})},handlePrevStep:function(){this[l.UPDATE_PRICE_STEP]({increment:!1})},handleSyncGood:function(){this[l.CLEAN_HOTEL_GOODS](),this.page=1,this.pageHasHardCodeSelection={},this[l.GET_HOTEL_GOODS_ACTION]({page:this.page,size:this.size})},disabledChangeGoodMaxNum:function(e){return!this.goodInfoItems.items[e]},showStockTip:function(e){return!!(this.goodInfoItems.items[e.goods_id]&&e.stock<e.good_max_num)}}),computed:(0,n.default)({},(0,i.mapState)("form",["goodInfoSaleWay","isPackages","goodInfoItems"]),(0,i.mapState)("goods",["goodIds","goodItems","count","links","loading"]),(0,i.mapGetters)("form",["goodInfoItemsNumber"]),{goods:function(){var e=[];if(this.goodIds.length)for(var t=(this.page-1)*this.size,o=this.page*this.size;t<o;){var s=this.goodIds[t];if(s){var a=this.goodInfoItems.items[s],n=this.goodItems[s];void 0===n.good_max_num&&(n.good_max_num=a&&a.num||1),e.push(n)}t++}return e}}),watch:{goods:function(e){e.length&&!this.pageHasHardCodeSelection[this.page]&&(u(e,this),this.pageHasHardCodeSelection[this.page]=!0)}},beforeRouteEnter:function(e,t,o){o(function(e){if("1"!==e.isPackages)return o({path:r.default[0].path});e.goodIds[0]?e.goods&&e.goods.length&&(u(e.goods,e),e.pageHasHardCodeSelection[e.page]=!0):e[l.GET_HOTEL_GOODS_ACTION]({page:e.page,size:e.size})})}}},650:function(e,t,o){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=o(414),a=o.n(s),n=o(703),i=o(132),l=i(a.a,n.a,null,null,null);t.default=l.exports},703:function(e,t,o){"use strict";var s=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"jfk-pages__modules jfk-pages__modules-goods"},[o("div",{staticClass:"jfk-mb-20 jfk-ta-r"},[o("el-button",{staticClass:"jfk-button-tag-a",attrs:{type:"info",size:"small",plain:!0}},[o("a",{staticClass:"goods_add",attrs:{href:e.links.add,target:"_blank"}},[o("i",{staticClass:"el-icon-plus"}),e._v(" 新增商品")])]),e._v(" "),o("el-button",{attrs:{type:"primary",size:"small"},on:{click:e.handleSyncGood}},[e._v("同步商品")])],1),e._v(" "),o("div",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}]},[o("el-table",{ref:"goodsInfoTable",staticClass:"jfk-table--no-border",staticStyle:{width:"100%"},attrs:{data:e.goods,"tooltip-effect":"dark","row-key":"goods_id"},on:{"selection-change":e.handleSelectionGoodsChange}},[o("el-table-column",{attrs:{type:"selection",label:"选择",prop:"goods_id",selectable:e.goodsInfoSelectable,"reserve-selection":"",width:"55"}}),e._v(" "),o("el-table-column",{attrs:{"show-overflow-tooltip":"",prop:"name",label:"商品名称"}}),e._v(" "),o("el-table-column",{attrs:{prop:"price_package",label:"商城价"}}),e._v(" "),o("el-table-column",{attrs:{prop:"price",label:"订房优惠价"}}),e._v(" "),o("el-table-column",{attrs:{label:"销售方式"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("\n          "+e._s(1===e.goodInfoSaleWay?"包价":"自由组合")+"\n        ")]}}])}),e._v(" "),o("el-table-column",{attrs:{label:"库存"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._v("\n          "+e._s(t.row.stock)+"\n          "),o("el-button",{directives:[{name:"show",rawName:"v-show",value:e.showStockTip(t.row),expression:"showStockTip(scope.row)"}],attrs:{size:"mini",type:"danger"}},[e._v("加库存")])]}}])}),e._v(" "),o("el-table-column",{attrs:{label:"数量"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-select",{attrs:{size:"small",disabled:e.disabledChangeGoodMaxNum(t.row.goods_id)},on:{change:function(o){e.handleChangeGoodMaxNum(t.row.goods_id,o)}},model:{value:t.row.good_max_num,callback:function(e){t.row.good_max_num=e},expression:"scope.row.good_max_num"}},e._l(10,function(e){return o("el-option",{key:e,attrs:{label:e,value:e}})}))]}}])})],1),e._v(" "),o("el-row",{staticClass:"jfk-mt-20 jfk-mb-20"},[o("el-col",{staticClass:"jfk-fz-12 jfk-color--base-gray jfk-lh--32",attrs:{span:12}},[e._v("共"+e._s(e.count)+"个商品，已选择"+e._s(e.goodInfoItemsNumber)+"个商品\n      "),o("span",{directives:[{name:"show",rawName:"v-show",value:e.goodInfoItemsNumber>=e.goodItemsMaxSelectionCount,expression:"goodInfoItemsNumber >= goodItemsMaxSelectionCount"}],staticClass:"jfk-color--warning"},[e._v("\n        （最多选择"+e._s(e.goodItemsMaxSelectionCount)+"个商品，请删除后再添加其余商品）\n      ")])]),e._v(" "),o("el-col",{staticClass:"jfk-ta-r",attrs:{span:12}},[o("el-pagination",{directives:[{name:"show",rawName:"v-show",value:e.count>e.size,expression:"count > size"}],staticClass:"jfk-d-ib",attrs:{"current-page":e.page,"page-size":e.size,total:e.count,layout:"prev, pager, next, jumper"},on:{"current-change":e.handleGoodInfosCurrentChange,"update:currentPage":function(t){e.page=t}}})],1)],1)],1),e._v(" "),o("el-row",{attrs:{type:"flex",justify:"center"}},[o("el-button",{staticClass:"jfk-button--middle",attrs:{size:"large"},nativeOn:{click:function(t){t.preventDefault(),e.handlePrevStep(t)}}},[e._v("上一步")]),e._v(" "),o("el-button",{staticClass:"jfk-button--middle",attrs:{size:"large",type:"primary"},nativeOn:{click:function(t){t.preventDefault(),e.handleNextStep(t)}}},[e._v("下一步")])],1)],1)},a=[],n={render:s,staticRenderFns:a};t.a=n}});