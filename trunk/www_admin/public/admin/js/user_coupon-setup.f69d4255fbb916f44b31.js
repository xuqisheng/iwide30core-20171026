webpackJsonp([23],{247:function(t,e,r){"use strict";function l(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var s=r(197),a=l(s),i=r(138),u=l(i),o=r(198),n=r(209),m=r(214),_=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-.,@?^=%&:\/~#]*[\w\-?^=%&\/~#])?/;e.default={name:"coupon-setup",data:function(){var t=this;return{ruleForm:{is_send_temp:"",temp_id:"",jump_url:"",jump_type:"1",auto_jump_url:"",first:"",keyword1:"",keyword2:"",keyword3:"",keyword4:"",remark:""},posting:!1,rules:{is_send_temp:[{required:!0,message:"请选择是否发送模版消息",trigger:"change"}],temp_id:[{validator:function(e,r,l){return"1"===t.ruleForm.is_send_temp&&""===r?l(new Error("请输入时间")):l()},message:"请填写模版ID",trigger:"blur"}],jump_type:[{validator:function(e,r,l){return"1"===t.ruleForm.is_send_temp&&0===r.length?l(new Error(e.message)):l()},message:"请选择方式",trigger:"change"}],jump_url:[{validator:function(e,r,l){return"1"===t.ruleForm.jump_type&&"1"===t.ruleForm.is_send_temp&&0===r.length?l(new Error(e.message)):l()},message:"请选择跳转链接",trigger:"change"}],auto_jump_url:[{validator:function(e,r,l){if("2"===t.ruleForm.jump_type&&"1"===t.ruleForm.is_send_temp){if(""===r)return l(new Error("请填写自定义链接"));if(!_.test(r))return l(new Error("请填写正确链接"))}return l()},trigger:"change"}]}}},methods:(0,u.default)({},(0,o.mapMutations)([m.UPDATE_COUPON_STEP]),{submitData:function(t){var e=this,r=this;this.$refs[t].validate(function(t){t&&e.$confirm("请确认是否发送, 确认操作后不可以撤销。请确认发送内容无误","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){if(!e.posting){var t=e.sedData();e.posting=!0,(0,n.getRequestInfo)(t).then(function(t){1e3===t.status?window.location.href=r.page_resource.links.list:r.$alert(t.msg,"保存失败",{confirmButtonText:"确定",callback:function(t){return r[m.UPDATE_COUPON_STEP]({increment:!1})}})}).catch(function(t){})}}).catch(function(){r.$message({type:"info",message:"已取消发送"})})})},sedData:function(){var t;return t={},(0,a.default)(t,this.csrf_token,this.csrf_value),(0,a.default)(t,"task_name",this.sumbit_data.task_name),(0,a.default)(t,"send_type",this.sumbit_data.send_type),(0,a.default)(t,"send_value",this.sumbit_data.send_value),(0,a.default)(t,"send_time_mode",this.sumbit_data.send_time_mode),(0,a.default)(t,"send_time",this.sumbit_data.send_time),(0,a.default)(t,"send_count",this.sumbit_data.send_count),(0,a.default)(t,"send_target",this.sumbit_data.send_target),(0,a.default)(t,"receive_repeat",this.sumbit_data.receive_repeat),(0,a.default)(t,"send_member_lvl",this.sumbit_data.send_member_lvl),(0,a.default)(t,"send_target_field",this.sumbit_data.send_target_field),(0,a.default)(t,"send_target_type",this.sumbit_data.send_target_type),(0,a.default)(t,"send_target_file",this.sumbit_data.send_target_file),(0,a.default)(t,"send_target_value",this.sumbit_data.send_target_value),(0,a.default)(t,"source",this.sumbit_data.source),(0,a.default)(t,"rfm",this.sumbit_data.rfm),(0,a.default)(t,"r_level",this.sumbit_data.r_level),(0,a.default)(t,"f_level",this.sumbit_data.f_level),(0,a.default)(t,"m_level",this.sumbit_data.m_level),(0,a.default)(t,"is_send_temp",this.ruleForm.is_send_temp),(0,a.default)(t,"temp_id",this.ruleForm.temp_id),(0,a.default)(t,"jump_type",this.ruleForm.jump_type),(0,a.default)(t,"jump_url",this.ruleForm.jump_url),(0,a.default)(t,"auto_jump_url",this.ruleForm.auto_jump_url),(0,a.default)(t,"first",this.ruleForm.first),(0,a.default)(t,"keyword1",this.ruleForm.keyword1),(0,a.default)(t,"keyword2",this.ruleForm.keyword2),(0,a.default)(t,"keyword3",this.ruleForm.keyword3),(0,a.default)(t,"keyword4",this.ruleForm.keyword4),(0,a.default)(t,"remark",this.ruleForm.remark),(0,a.default)(t,"debug",1),t},cancelData:function(){var t=this;t.$confirm("是否取消发送，确认后不可撤销，请慎重选择","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){window.location.href=t.page_resource.links.list}).catch(function(){})}}),created:function(){this.ruleForm.first=this.temp_input_group[0].value,this.ruleForm.keyword1=this.temp_input_group[1].value,this.ruleForm.keyword2=this.temp_input_group[2].value,this.ruleForm.keyword3=this.temp_input_group[3].value,this.ruleForm.keyword4=this.temp_input_group[4].value,this.ruleForm.remark=this.temp_input_group[5].value},computed:(0,u.default)({},(0,o.mapState)(["csrf_token","csrf_value","enum_des","enum_des_selected","input_tag","page_field","page_hint","page_resource","temp_input_group","sumbit_data"]),{postText:function(){return"确认发送优惠"+(this.posting?"中":"")}})}},309:function(t,e,r){e=t.exports=r(75)(!1),e.push([t.i,"#coupon-setup .el-form-item__label{width:150px;margin-right:15px}#coupon-setup .steup-auto-width .el-form-item__content{margin-left:165px}#coupon-setup .steup-msg-title{margin-bottom:10px}#coupon-setup .steup-msg-word{font-size:15px;color:gray}",""])},339:function(t,e,r){var l=r(309);"string"==typeof l&&(l=[[t.i,l,""]]),l.locals&&(t.exports=l.locals);r(76)("29825c0c",l,!0)},389:function(t,e,r){r(339);var l=r(139)(r(247),r(398),null,null);t.exports=l.exports},398:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{attrs:{id:"coupon-setup"}},[t._m(0),t._v(" "),r("div",[r("el-row",[r("el-col",{attrs:{span:11}},[r("el-col",{staticClass:"choice-step-num",attrs:{span:4}},[t._v("\n\t\t\t  \t \t01\n\t\t\t  \t")]),t._v(" "),r("el-col",{attrs:{span:20}},[r("p",{staticClass:"choice-step-title"},[t._v("选择优惠券发放内容和目标用户")]),t._v(" "),r("p",{staticClass:"choice-step-word"},[t._v("选择发送的优惠券或礼包，并选择需要发送的用户群体")])])],1),t._v(" "),r("el-col",{staticClass:"jfk-ta-c",attrs:{span:2}},[r("div",{staticClass:"choice-line"})]),t._v(" "),r("el-col",{attrs:{span:11}},[r("el-col",{staticClass:"choice-step-num choice-step-active",attrs:{span:4}},[t._v("\n\t\t\t  \t \t02\n\t\t\t  \t")]),t._v(" "),r("el-col",{attrs:{span:20}},[r("p",{staticClass:"choice-step-title"},[t._v("设置模版消息")]),t._v(" "),r("p",{staticClass:"choice-step-word"},[t._v("设置一个模版消息，给收到优惠的用户发送一个模版消息")])])],1)],1)],1),t._v(" "),r("el-form",{ref:"ruleForm",attrs:{model:t.ruleForm,rules:t.rules}},[r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[t._v("选择发送内容")])]),t._v(" "),r("div",[r("el-form-item",{attrs:{label:"是否发送模版消息?",prop:"is_send_temp"}},[r("el-radio-group",{model:{value:t.ruleForm.is_send_temp,callback:function(e){t.ruleForm.is_send_temp=e},expression:"ruleForm.is_send_temp"}},[r("el-radio",{attrs:{label:"1",value:"1"}},[t._v("是")]),t._v(" "),r("el-radio",{attrs:{label:"2",value:"2"}},[t._v("否")])],1)],1),t._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:"1"===t.ruleForm.is_send_temp,expression:"ruleForm.is_send_temp === '1' "}]},[r("el-form-item",{attrs:{label:t.input_tag.temp_title.name}},[r("p",[t._v(t._s(t.input_tag.temp_title.value))])]),t._v(" "),r("el-form-item",{attrs:{label:t.input_tag.temp_id.name,prop:"temp_id"}},[r("el-input",{staticStyle:{width:"250px"},attrs:{placeholder:""},model:{value:t.ruleForm.temp_id,callback:function(e){t.ruleForm.temp_id=e},expression:"ruleForm.temp_id"}})],1),t._v(" "),r("div",{staticClass:"el-form--inline"},[r("el-form-item",{attrs:{label:"",prop:"jump_type"}},[r("el-form-item",{staticStyle:{"margin-left":"70px"},attrs:{label:""}},[r("el-radio",{attrs:{label:"1"},model:{value:t.ruleForm.jump_type,callback:function(e){t.ruleForm.jump_type=e},expression:"ruleForm.jump_type"}},[t._v("跳转链接")])],1),t._v(" "),r("el-form-item",{attrs:{label:"",prop:"jump_url"}},[r("el-select",{staticStyle:{width:"120px"},model:{value:t.ruleForm.jump_url,callback:function(e){t.ruleForm.jump_url=e},expression:"ruleForm.jump_url"}},t._l(t.enum_des.jump_url,function(t,e){return r("el-option",{key:e,attrs:{label:t,value:e}})}))],1),t._v(" "),r("el-form-item",{staticStyle:{"margin-left":"20px"},attrs:{label:""}},[r("el-radio",{attrs:{label:"2"},model:{value:t.ruleForm.jump_type,callback:function(e){t.ruleForm.jump_type=e},expression:"ruleForm.jump_type"}},[t._v("自定义链接")])],1),t._v(" "),r("el-form-item",{attrs:{label:"",prop:"auto_jump_url"}},[r("el-input",{staticStyle:{width:"250px"},attrs:{placeholder:""},model:{value:t.ruleForm.auto_jump_url,callback:function(e){t.ruleForm.auto_jump_url=e},expression:"ruleForm.auto_jump_url"}})],1),t._v(" "),r("el-form-item",{staticStyle:{"margin-left":"20px"},attrs:{label:""}},[r("el-radio",{attrs:{label:"3"},model:{value:t.ruleForm.jump_type,callback:function(e){t.ruleForm.jump_type=e},expression:"ruleForm.jump_type"}},[t._v("无链接")])],1)],1)],1)],1)],1),t._v(" "),r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[t._v("消息内容")])]),t._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"first"}},[r("el-input",{attrs:{placeholder:""},model:{value:t.ruleForm.first,callback:function(e){t.ruleForm.first=e},expression:"ruleForm.first"}})],1),t._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword1"}},[r("el-input",{attrs:{placeholder:""},model:{value:t.ruleForm.keyword1,callback:function(e){t.ruleForm.keyword1=e},expression:"ruleForm.keyword1"}})],1),t._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword2"}},[r("el-input",{attrs:{placeholder:""},model:{value:t.ruleForm.keyword2,callback:function(e){t.ruleForm.keyword2=e},expression:"ruleForm.keyword2"}})],1),t._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword3"}},[r("el-input",{attrs:{placeholder:""},model:{value:t.ruleForm.keyword3,callback:function(e){t.ruleForm.keyword3=e},expression:"ruleForm.keyword3"}})],1),t._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword4"}},[r("el-input",{attrs:{placeholder:""},model:{value:t.ruleForm.keyword4,callback:function(e){t.ruleForm.keyword4=e},expression:"ruleForm.keyword4"}})],1),t._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"remark"}},[r("el-input",{attrs:{placeholder:""},model:{value:t.ruleForm.remark,callback:function(e){t.ruleForm.remark=e},expression:"ruleForm.remark"}})],1),t._v(" "),r("el-row",{staticClass:"gray-bg"},[r("el-col",{staticStyle:{"padding-right":"35px"},attrs:{span:8}},[r("p",{staticClass:"steup-msg-title"},[t._v("模版消息提示")]),t._v(" "),t._l(t.page_hint.temp_hint,function(e,l){return r("p",{key:l,staticClass:"steup-msg-word"},[t._v(t._s(e))])})],2),t._v(" "),r("el-col",{attrs:{span:8}},[r("p",{staticClass:"steup-msg-title"},[t._v("模版详细内容")]),t._v(" "),t._l(t.page_hint.temp_contet_hint,function(e,l){return r("p",{key:l,staticClass:"steup-msg-word"},[t._v(t._s(e))])})],2),t._v(" "),r("el-col",{attrs:{span:6}},[r("p",{staticClass:"steup-msg-title"},[t._v("模版可用字段")]),t._v(" "),t._l(t.page_hint.temp_field_hint,function(e,l){return r("p",{key:l,staticClass:"steup-msg-word"},[t._v(t._s(e))])})],2)],1),t._v(" "),r("el-row",{staticStyle:{"margin-top":"25px"},attrs:{type:"flex",justify:"center"}},[r("el-button",{staticClass:"jfk-button--middle",attrs:{type:"primary",loading:t.posting,size:"large"},nativeOn:{click:function(e){e.preventDefault(),t.submitData("ruleForm")}}},[t._v(t._s(t.postText))]),t._v(" "),r("el-button",{staticClass:"jfk-button--middle",attrs:{size:"large"},nativeOn:{click:function(e){e.preventDefault(),t.cancelData()}}},[t._v("取消发送")])],1)],1)],1)},staticRenderFns:[function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[t._v("新建优惠券发放任务")])])}]}}});