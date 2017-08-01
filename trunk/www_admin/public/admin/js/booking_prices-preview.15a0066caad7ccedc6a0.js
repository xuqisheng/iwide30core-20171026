webpackJsonp([24],{228:function(t,e,s){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var o=s(203),l=a(o),i=s(197),n=a(i),r=s(138),c=a(r),f=s(198),p=s(199),_=s(210),m=a(_),v=s(202),d=s(207);e.default={data:function(){return{posting:!1}},methods:(0,c.default)({},(0,f.mapMutations)([p.UPDATE_PRICE_STEP]),{handlePrevStep:function(){this.posting||this[p.UPDATE_PRICE_STEP]({increment:!1})},handleNextStep:function(){if(!this.posting){var t=this.getPriceData();this.posting=!0;var e=this;(0,d.postHotelPricesCode)(t).then(function(t){if(1e3===t.status){e.posting=!1;var s=t.web_data.page_resource.links.next;location.href=s}}).catch(function(t){e.posting=!1})}},hotelRoomNameLists:function(t,e){var s=[];for(var a in t)s.push(e[a].name);return s.join("、")},getPriceData:function(){var t,e=(t={},(0,n.default)(t,this.form.csrfToken,this.form.csrfValue),(0,n.default)(t,"price_code",this.form.priceCode),(0,n.default)(t,"inter_id",this.form.interId),(0,n.default)(t,"price_name",this.form.priceName),(0,n.default)(t,"status",this.form.status),(0,n.default)(t,"use_condition",{no_pay_way:this.getNoPayWay,member_level:this.form.memberLevel,pre_d:this.form.preD,s_date_s:this.form.sDateS1.replace(/-/g,""),s_date_e:this.form.sDateE1.replace(/-/g,""),e_date_s:this.form.eDateS1.replace(/-/g,""),e_date_e:this.form.eDateE1.replace(/-/g,""),mxn:this.form.mxn,mxd:this.form.mxd,min_day:this.form.minDay}),(0,n.default)(t,"des",this.form.des),(0,n.default)(t,"sort",this.form.sort),(0,n.default)(t,"type",this.form.type),(0,n.default)(t,"related_code",this.form.relatedCode),(0,n.default)(t,"coupon_condition",{no_coupon:this.form.couponNoUse}),(0,n.default)(t,"bonus_condition",{no_part_bonus:this.form.bonusNoPart,poc:this.form.bonusPoc}),(0,n.default)(t,"time_condition",{limit_weeks:this.form.limitWeeks}),(0,n.default)(t,"bookpolicy_condition",{breakfast_nums:this.form.breakfastNums}),(0,n.default)(t,"is_packages",this.form.isPackages),(0,n.default)(t,"all_rooms",this.form.allRooms),t);if(""!==this.form.prePay&&(e.use_condition.pre_pay=this.form.prePay),"athour"===this.form.type&&(e.time_condition.book_time={s:this.form.bookTimeStart.replace(":",""),e:this.form.bookTimeEnd.replace(":",""),mod:this.form.bookTimeMod}),"protrol"===this.form.type&&(e.unlock_code=this.form.unlockCode),"member"===this.form.type&&"-1"===this.form.relatedCode||(e.related_cal_way=this.form.relatedCalWay,e.related_cal_value=this.form.relatedCalValue),0!==this.form.isPms&&(e.external_code=this.form.externalCode,e.coupon_condition.is_pms=this.form.couponIsPms),0===this.form.couponNoUse&&(e.coupon_condition.coupon_num=this.form.couponNum,e.coupon_condition.num_type=this.form.couponNumType),(0,l.default)(this.confCouponTypes).length&&(e.coupon_condition.couprel=this.form.couprel),this.form.payWays.length){e.bookpolicy_condition.retain_time={},e.bookpolicy_condition.delay_time={};for(var s in this.form.delayTime)e.bookpolicy_condition.delay_time[s]=this.form.delayTime[s].replace(":00","");for(var a in this.form.retainTime)e.bookpolicy_condition.retain_time[a]=this.form.retainTime[a].replace(":00","");this.hasWxPayInPayways&&this.form.wxPayFavour&&(e.bookpolicy_condition.wxpay_favour=this.form.wxPayFavour)}if("1"===this.form.isPackages?(e.goods_info={sale_way:this.form.goodInfoSaleWay},1===this.form.goodInfoSaleWay&&(e.goods_info.count_way=this.form.goodInfoCountWay,e.goods_info.sale_notice=this.form.goodInfoSaleNotice),e.goods_info.items=this.form.goodInfoItems.items):e.use_condition.package_only=this.form.packageOnly,"0"===this.form.allRooms){var o={};for(var i in this.form.limitRoomChecked)o[i]=(0,l.default)(this.form.limitRoomChecked[i].rooms);e.h_roomids=o}return e}}),computed:(0,c.default)({},(0,f.mapState)(["form"]),(0,f.mapState)("config",["confCodeType","confWeeks","confBfFields","confPriceCodes","confMemberLevels","confPayWays","confBookTimeMod","confCouponNumType","confCodeStatus","confCouponTypes"]),(0,f.mapState)("rooms",["hotelRoomItems"]),(0,f.mapState)("goods",["goodItems"]),(0,f.mapGetters)("config",["hasConfMemberLevel","hasConfPayWays"]),(0,f.mapGetters)("form",["limitRoomCheckedCount","hasWxPayInPayways"]),{limitWeeksText:function(){var t=[],e=this;return e.form.limitWeeks.forEach(function(s){t.push(e.confWeeks[s])}),t.join("，")},sDateText:function(){return this.form.sDateS1&&this.form.sDateE1?"必须在"+this.form.sDateS1+"和"+this.form.sDateE1+"之间入住":this.form.sDateS1?"必须在"+this.form.sDateS1+"之后入住":this.form.sDateE1?"必须在"+this.form.sDateE1+"之前入住":void 0},eDateText:function(){return this.form.eDateS1&&this.form.eDateE1?"必须在"+this.form.eDateS1+"和"+this.form.eDateE1+"之间离店":this.form.eDateS1?"必须在"+this.form.eDateS1+"之后离店":this.form.eDateE1?"必须在"+this.form.eDateE1+"之前离店":void 0},couponText:function(){return 1===this.form.couponNoUse?"不可用":"可用，每个"+this.confCouponNumType[this.form.couponNumType]+"可用"+this.form.couponNum+"张"},payWaysText:function(){var t=[],e=this;return e.form.payWays.forEach(function(s){t.push(e.confPayWays[s].pay_name)}),t.join("，")},postText:function(){return"保存"+(this.posting?"中":"")},goodInfoSaleWayText:function(){return 1===this.form.goodInfoSaleWay?"包价":"自由组合"},getNoPayWay:function(){var t=(0,l.default)(this.confPayWays);return t.length&&t.length!==this.form.payWays.length?(0,v.omitKeys)(this.form.payWays,this.confPayWays):[]},previewGoodInfo:function(){var t=this.form.goodInfoItems.items,e=[];for(var s in t)e.push(t[s]);return e}}),beforeRouteEnter:function(t,e,s){s(function(t){return!!((t.pcode||e.name)&&t.form.type&&t.form.priceName&&t.form.des&&t.form.limitWeeks.length)||s({path:m.default[0].path})})}}},306:function(t,e,s){e=t.exports=s(75)(!1),e.push([t.i,".jfk-fieldset__preview[data-v-1e395e40]{font-size:12px;line-height:2}.el-table[data-v-1e395e40]{font-size:12px}.preview-label[data-v-1e395e40]{color:gray;font-size:12px;text-align:right}.preview-content[data-v-1e395e40]{color:#333}.limit-room-tip[data-v-1e395e40]{color:#bfbfbf}.limit-room-tip span[data-v-1e395e40]{color:#333}.list-no-style[data-v-1e395e40]{color:#bfbfbf}.hotel-name[data-v-1e395e40]{color:#333}pre[data-v-1e395e40]{border:0 none;padding:0;font-size:12px;margin:0;background-color:transparent}",""])},336:function(t,e,s){var a=s(306);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);s(76)("2e9178d0",a,!0)},370:function(t,e,s){s(336);var a=s(139)(s(228),s(395),"data-v-1e395e40",null);t.exports=a.exports},395:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"jfk-pages__modules jfk-pages__modules-preview"},[s("dl",{staticClass:"jfk-fieldset jfk-fieldset__preview"},[t._m(0),t._v(" "),s("dd",{staticClass:"jfk-fieldset__content"},[s("el-row",{staticClass:"preview-group-baseinfo"},[s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("价格类型")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v("\n            "+t._s(t.confCodeType[t.form.type])+t._s("protrol"===t.form.type?";协议代码："+t.form.unlockCode:"")+"\n          ")])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("价格名称")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.form.priceName))])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("价格描述")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.form.des))])],1),t._v(" "),t.form.sDateS||t.form.sDateE?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("入住日期")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.sDateText))])],1):t._e(),t._v(" "),t.form.eDateS||t.form.eDateE?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("离店日期")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.eDateText))])],1):t._e(),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("可用星期")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.limitWeeksText))])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("早餐")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.confBfFields[t.form.breakfastNums]))])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("价格策略")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.confPriceCodes[t.form.relatedCode]&&t.confPriceCodes[t.form.relatedCode].price_name||""))])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-row",[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("适用范围")]),t._v(" "),"1"===t.form.allRooms?s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v("全部门店和房型")]):s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[s("p",{staticClass:"limit-room-tip"},[t._v("共"),s("span",[t._v(t._s(t.limitRoomCheckedCount))]),t._v("家酒店")]),t._v(" "),s("ul",{staticClass:"list-no-style"},t._l(t.form.limitRoomChecked,function(e,a){return s("li",{key:"hotel_"+a},[s("span",{staticClass:"hotel-name"},[t._v(t._s(t.hotelRoomItems[a].name)+"：")]),t._v("\n                  "+t._s(t.hotelRoomNameLists(e.rooms,t.hotelRoomItems[a].room_ids))+"\n                ")])}))])],1)],1),t._v(" "),t.hasConfMemberLevel||t.hasConfPayWays||""!==t.form.prePay?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-row",[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("适用条件")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t.hasConfMemberLevel?s("el-col",[t._v("会员等级："+t._s(t.confMemberLevels[t.form.memberLevel]))]):t._e(),t._v(" "),t.hasConfPayWays?s("el-col",[t._v("支付方式："+t._s(t.payWaysText))]):t._e(),t._v(" "),""!==t.form.prePay?s("el-col",[t._v("预付标记："+t._s(t.form.prePay?"显示":"不显示"))]):t._e()],1)],1)],1):t._e(),t._v(" "),"1"===t.form.isPackages?[s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-row",{attrs:{type:"flex"}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("套餐设置")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[s("el-col",[t._v("销售方式："+t._s(t.goodInfoSaleWayText))]),t._v(" "),1===t.form.goodInfoSaleWay?[s("el-col",[t._v("计价方式："+t._s(1===t.form.goodInfoCountWay?"按房晚":"按订单"))]),t._v(" "),s("el-col",[s("span",{staticClass:"jfk-f-l"},[t._v("订购须知：")]),t._v(" "),s("pre",{staticClass:"jfk-f-l"},[t._v(t._s(t.form.goodInfoSaleNotice))])])]:t._e()],2)],1)],1)]:t._e()],2)],1),t._v(" "),"1"===t.form.isPackages?[t._m(1),t._v(" "),s("dd",{staticClass:"jfk-fieldset__content"},[s("el-table",{class:{"jfk-table--no-border":t.previewGoodInfo.length>1},attrs:{"show-header":!1,data:t.previewGoodInfo}},[s("el-table-column",{scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n              "+t._s(e.row.name||t.goodItems[e.row.gs_id].name)+"\n            ")]}}])}),t._v(" "),s("el-table-column",{scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n              "+t._s(t.goodInfoSaleWayText)+"\n            ")]}}])}),t._v(" "),s("el-table-column",{scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n              "+t._s(e.row.num)+t._s(e.row.unit||t.goodItems[e.row.gs_id].unit)+"\n            ")]}}])}),t._v(" "),1===t.form.goodInfoSaleWay?s("el-table-column",{scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n            "+t._s(1===t.form.goodInfoCountWay?"按房晚":"按订单")+"\n            ")]}}])}):t._e()],1)],1)]:t._e(),t._v(" "),t._m(2),t._v(" "),s("dd",{staticClass:"jfk-fieldset__content"},[s("el-row",{staticClass:"preview-group-policy"},[t.hasConfPayWays?[s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-row",{attrs:{type:"flex",align:"middle"}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("保留时间")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},t._l(t.form.retainTime,function(e,a){return s("el-col",{key:"retainTime_"+a},[t._v("\n                  "+t._s(t.confPayWays[a].pay_name)+"：入住日期"+t._s(e)+"\n                ")])}))],1)],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-row",{attrs:{type:"flex",align:"middle"}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("退房时间")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},t._l(t.form.delayTime,function(e,a){return s("el-col",{key:"delayTime_"+a},[t._v("\n                  "+t._s(t.confPayWays[a].pay_name)+"：离店日期"+t._s(e)+"\n                ")])}))],1)],1)]:t._e()],2)],1),t._v(" "),""!==t.form.preD||t.form.mxn||t.form.minDay||t.form.mxd||"athour"===t.form.type?[t._m(3),t._v(" "),s("dd",{staticClass:"jfk-fieldset__content"},[s("el-row",[""!==t.form.preD?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("提前预定天数")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.form.preD)+"天")])],1):t._e(),t._v(" "),t.form.mxn?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("单次最大单数")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.form.mxn)+"间")])],1):t._e(),t._v(" "),t.form.minDay||t.form.mxd?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("可定天数")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t.form.minDay?[t._v("\n                最少须定"+t._s(t.form.minDay)+"天，\n              ")]:t._e(),t._v(" "),t.form.mxd?[t._v("\n                最多能定"+t._s(t.form.mxd)+"天\n              ")]:t._e()],2)],1):t._e(),t._v(" "),"athour"===t.form.type?[s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("到店时间段")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v("\n              在"+t._s(t.form.bookTimeStart)+"至"+t._s(t.form.bookTimeEnd)+"之间")])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("时间间隔")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.confBookTimeMod[t.form.bookTimeMod]))])],1)]:t._e()],2)],1)]:t._e(),t._v(" "),t._m(4),t._v(" "),s("dd",{staticClass:"jfk-fieldset__content"},[s("el-row",[t.hasWxPayInPayways&&t.form.wxPayFavour?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("微信支付立减")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.form.wxPayFavour)+"元")])],1):t._e(),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("用券规则")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.couponText))])],1),t._v(" "),Object.keys(t.confCouponTypes).length?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("券关联")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v("\n            "+t._s(t.confCouponTypes[t.form.couprel].title)+"\n          ")])],1):t._e(),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("积分兑换")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(1===t.form.bonusNoPart?"不可用":"可用"))])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("积分与券")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(1===t.form.bonusPoc?"不可同用":"可同用"))])],1),t._v(" "),0!==t.form.isPms?[s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("使用PMS券")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(1===t.form.couponIsPms?"使用":"不使用"))])],1),t._v(" "),s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("PMS代码")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.form.externalCode||"无"))])],1)]:t._e(),t._v(" "),"0"===t.form.isPackages?s("el-col",{staticClass:"preview-items",attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("仅用于套餐预订")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(1===t.form.packageOnly?"是":"否"))])],1):t._e()],2)],1),t._v(" "),t._m(5),t._v(" "),s("dd",{staticClass:"jfk-fieldset__content"},[s("el-row",[t.form.sort?s("el-col",{attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("排序")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.form.sort))])],1):t._e(),t._v(" "),s("el-col",{attrs:{lg:12,md:24}},[s("el-col",{staticClass:"preview-label",attrs:{lg:{span:4},span:3}},[t._v("状态")]),t._v(" "),s("el-col",{staticClass:"preview-content",attrs:{lg:{span:19},span:20,offset:1}},[t._v(t._s(t.confCodeStatus[t.form.status]))])],1)],1)],1)],2),t._v(" "),s("el-row",{attrs:{type:"flex",justify:"center"}},[s("el-button",{staticClass:"jfk-button--middle",attrs:{disabled:t.posting,size:"large"},nativeOn:{click:function(e){e.preventDefault(),t.handlePrevStep(e)}}},[t._v("上一步")]),t._v(" "),s("el-button",{staticClass:"jfk-button--middle",attrs:{type:"primary",loading:t.posting,size:"large"},nativeOn:{click:function(e){e.preventDefault(),t.handleNextStep(e)}}},[t._v(t._s(t.postText))])],1)],1)},staticRenderFns:[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dt",{staticClass:"jfk-fieldset__hd"},[s("div",{staticClass:"jfk-fieldset__title"},[t._v("基础信息")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dt",{staticClass:"jfk-fieldset__hd"},[s("div",{staticClass:"jfk-fieldset__title"},[t._v("关联商品")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dt",{staticClass:"jfk-fieldset__hd"},[s("div",{staticClass:"jfk-fieldset__title"},[t._v("预定政策")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dt",{staticClass:"jfk-fieldset__hd"},[s("div",{staticClass:"jfk-fieldset__title"},[t._v("高级预定政策")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dt",{staticClass:"jfk-fieldset__hd"},[s("div",{staticClass:"jfk-fieldset__title"},[t._v("营销规则")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dt",{staticClass:"jfk-fieldset__hd"},[s("div",{staticClass:"jfk-fieldset__title"},[t._v("其他")])])}]}}});