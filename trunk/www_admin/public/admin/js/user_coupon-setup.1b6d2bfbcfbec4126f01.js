webpackJsonp([37],{462:function(e,t,r){"use strict";function l(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=r(214),a=l(s),i=r(156),o=l(i),u=r(217),n=r(242),m=r(220),p=r(250),_=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-.,@?^=%&:\/~#]*[\w\-?^=%&\/~#])?/;t.default={name:"coupon-setup",data:function(){var e=this,t=function(t,r,l){return"1"===e.ruleForm.is_send_temp&&""===r?(e.errorScroll(),l(new Error("请输入时间"))):l()},r=function(t,r,l){return"1"===e.ruleForm.is_send_temp&&0===r.length?(e.errorScroll(),l(new Error(t.message))):l()},l=function(t,r,l){return"1"===e.ruleForm.jump_type&&"1"===e.ruleForm.is_send_temp&&0===r.length?(e.errorScroll(),l(new Error(t.message))):l()},s=function(t,r,l){if("2"===e.ruleForm.jump_type&&"1"===e.ruleForm.is_send_temp){if(""===r)return e.errorScroll(),l(new Error("请填写自定义链接"));if(!_.test(r))return e.errorScroll(),l(new Error("请填写正确链接"))}return l()};return{ruleForm:{is_send_temp:"2",temp_id:"",jump_url:"",jump_type:"1",auto_jump_url:"",first:"",keyword1:"",keyword2:"",keyword3:"",keyword4:"",remark:""},posting:!1,rules:{is_send_temp:[{validator:function(t,r,l){return""===r?(e.errorScroll(),l(new Error(t.message))):l()},message:"请选择是否发送模版消息",trigger:"change"}],temp_id:[{validator:t,message:"请填写模版ID",trigger:"blur"}],jump_type:[{validator:r,message:"请选择方式",trigger:"change"}],jump_url:[{validator:l,message:"请选择跳转链接",trigger:"change"}],auto_jump_url:[{validator:s,trigger:"change"}]}}},methods:(0,o.default)({},(0,u.mapMutations)([p.UPDATE_COUPON_STEP]),{submitData:function(e){var t=this,r=this;this.$refs[e].validate(function(e){e&&t.$confirm("请确认是否发送, 确认操作后不可以撤销。请确认发送内容无误","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){if(!t.posting){var e=t.sedData();t.posting=!0,(0,n.getRequestInfo)(e,{REJECTERRORCONFIG:{serveError:!0}}).then(function(e){window.location.href=r.page_resource.links.list}).catch(function(t){r.$alert(t.msg,"保存失败",{confirmButtonText:"确定",callback:function(t){window.location.href=r.page_resource.links.edit+"?id="+e.id}})})}}).catch(function(){r.$message({type:"info",message:"已取消发送"})})})},calTemp:function(){var e=this,t=this,r=this.$createElement;this.$msgbox({title:"风险提示",message:r("p",null,[r("p",{style:"font-size: 16px"},"根据微信公众平台规定："),r("p",{style:"margin-top: 15px"},"模板消息的定位是用户触发后的通知消息，不允许在用户没做任何操作或未经用户同意接收的前提下，主动下发消息给用户。允许发的模板消息必须是用户接受过帐号主体提供过服务的，严禁用户未接受服务而向其推送模板消息。发送模板消息的前提是内容不•涉及广告营销骚扰用户，一经发现内容涉及营销骚扰将严厉处罚。违规行为包括："),r("p",{style:"margin-top: 5px"},"1、模板消息内容不能做营销、推广、诱导分享及诱导下载APP"),r("p",{style:"margin-top: 5px"},"2、模板内容与模板标题或关键词无关联"),r("p",{style:"margin: 5px 0 10px 0"},"3、模板内容是营销性质的群发活动公告通知"),r("span",null,"请详情查阅"),r("a",{domProps:{href:"https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751288",target:"_blank"},style:{color:"blue",cursor:"pointer","text-decoration":"none"}},"模版消息运营规范"),r("div",{style:"margin-bottom: 15px"},null),r("input",{domProps:{type:"checkbox",id:"agreeCheckbox"},style:"float:left"},null),r("span",null,"我已了解，确认继续发送模板消息")]),showCancelButton:!0,confirmButtonText:"确定",cancelButtonText:"取消",type:"warning",beforeClose:function(r,l,s){"confirm"===r?document.getElementById("agreeCheckbox").checked?(t.ruleForm.is_send_temp="1",s()):t.$message({message:"请先了解模版公众平台规定",type:"warning"}):(s(),e.ruleForm.is_send_temp="2")}})},sedData:function(){var e,t=(0,m.formatUrlParams)(location.search),r="";return t.id&&(r=t.id),e={},(0,a.default)(e,this.csrf_token,this.csrf_value),(0,a.default)(e,"task_name",this.sumbit_data.task_name),(0,a.default)(e,"send_type",this.sumbit_data.send_type),(0,a.default)(e,"send_value",this.sumbit_data.send_value),(0,a.default)(e,"send_time_mode",this.sumbit_data.send_time_mode),(0,a.default)(e,"send_time",this.sumbit_data.send_time),(0,a.default)(e,"send_count",this.sumbit_data.send_count),(0,a.default)(e,"send_target",this.sumbit_data.send_target),(0,a.default)(e,"receive_repeat",this.sumbit_data.receive_repeat),(0,a.default)(e,"send_member_lvl",this.sumbit_data.send_member_lvl),(0,a.default)(e,"send_target_field",this.sumbit_data.send_target_field),(0,a.default)(e,"send_target_type",this.sumbit_data.send_target_type),(0,a.default)(e,"send_target_file",this.sumbit_data.send_target_file),(0,a.default)(e,"send_target_value",this.sumbit_data.send_target_value),(0,a.default)(e,"source",this.sumbit_data.source),(0,a.default)(e,"rfm",this.sumbit_data.rfm),(0,a.default)(e,"r_level",this.sumbit_data.r_level),(0,a.default)(e,"f_level",this.sumbit_data.f_level),(0,a.default)(e,"m_level",this.sumbit_data.m_level),(0,a.default)(e,"is_send_temp",this.ruleForm.is_send_temp),(0,a.default)(e,"temp_id",this.ruleForm.temp_id),(0,a.default)(e,"jump_type",this.ruleForm.jump_type),(0,a.default)(e,"jump_url",this.ruleForm.jump_url),(0,a.default)(e,"auto_jump_url",this.ruleForm.auto_jump_url),(0,a.default)(e,"first",this.ruleForm.first),(0,a.default)(e,"keyword1",this.ruleForm.keyword1),(0,a.default)(e,"keyword2",this.ruleForm.keyword2),(0,a.default)(e,"keyword3",this.ruleForm.keyword3),(0,a.default)(e,"keyword4",this.ruleForm.keyword4),(0,a.default)(e,"remark",this.ruleForm.remark),(0,a.default)(e,"id",r),e},errorScroll:function(){window.scroll(0,250)},cancelData:function(){var e=this;e.$confirm("是否取消发送，确认后不可撤销，请慎重选择","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){window.location.href=e.page_resource.links.list}).catch(function(){})}}),created:function(){(0,m.formatUrlParams)(location.search).id&&(this.ruleForm.is_send_temp=this.enum_des_selected.is_send_temp),this.ruleForm.jump_type=this.enum_des_selected.jump_type,this.ruleForm.auto_jump_url=this.input_tag.auto_jump_url.value,this.ruleForm.jump_url=this.enum_des_selected.jump_url,this.ruleForm.temp_id=this.input_tag.temp_id.value,this.ruleForm.first=this.temp_input_group[0].value,this.ruleForm.keyword1=this.temp_input_group[1].value,this.ruleForm.keyword2=this.temp_input_group[2].value,this.ruleForm.keyword3=this.temp_input_group[3].value,this.ruleForm.keyword4=this.temp_input_group[4].value,this.ruleForm.remark=this.temp_input_group[5].value},computed:(0,o.default)({},(0,u.mapState)(["csrf_token","csrf_value","enum_des","enum_des_selected","input_tag","page_field","page_hint","page_resource","temp_input_group","sumbit_data"]),{postText:function(){return"确认发送优惠"+(this.posting?"中":"")}})}},536:function(e,t,r){t=e.exports=r(75)(!1),t.push([e.i,"#coupon-setup .el-form-item__label{width:150px;margin-right:15px}#coupon-setup .steup-auto-width .el-form-item__content{margin-left:165px}#coupon-setup .steup-msg-title{margin-bottom:10px}#coupon-setup .steup-msg-word{font-size:15px;color:gray}.el-message-box__status{top:40px!important}",""])},658:function(e,t,r){var l=r(536);"string"==typeof l&&(l=[[e.i,l,""]]),l.locals&&(e.exports=l.locals);r(76)("2e237fe7",l,!0)},732:function(e,t,r){"use strict";function l(e){r(658)}Object.defineProperty(t,"__esModule",{value:!0});var s=r(462),a=r.n(s),i=r(750),o=r(155),u=l,n=o(a.a,i.a,u,null,null);t.default=n.exports},750:function(e,t,r){"use strict";var l=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{attrs:{id:"coupon-setup"}},[e._m(0),e._v(" "),r("div",[r("el-row",[r("el-col",{attrs:{span:11}},[r("el-col",{staticClass:"choice-step-num",attrs:{span:4}},[e._v("\n\t\t\t  \t \t01\n\t\t\t  \t")]),e._v(" "),r("el-col",{attrs:{span:20}},[r("p",{staticClass:"choice-step-title"},[e._v("选择优惠券发放内容和目标用户")]),e._v(" "),r("p",{staticClass:"choice-step-word"},[e._v("选择发送的优惠券或礼包，并选择需要发送的用户群体")])])],1),e._v(" "),r("el-col",{staticClass:"jfk-ta-c",attrs:{span:2}},[r("div",{staticClass:"choice-line"})]),e._v(" "),r("el-col",{attrs:{span:11}},[r("el-col",{staticClass:"choice-step-num choice-step-active",attrs:{span:4}},[e._v("\n\t\t\t  \t \t02\n\t\t\t  \t")]),e._v(" "),r("el-col",{attrs:{span:20}},[r("p",{staticClass:"choice-step-title"},[e._v("设置模版消息")]),e._v(" "),r("p",{staticClass:"choice-step-word"},[e._v("设置一个模版消息，给收到优惠的用户发送一个模版消息")])])],1)],1)],1),e._v(" "),r("el-form",{ref:"ruleForm",attrs:{model:e.ruleForm,rules:e.rules}},[r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[e._v("选择发送内容")])]),e._v(" "),r("div",[r("el-form-item",{attrs:{label:"是否发送模版消息?",prop:"is_send_temp"}},[r("el-radio-group",{model:{value:e.ruleForm.is_send_temp,callback:function(t){e.ruleForm.is_send_temp=t},expression:"ruleForm.is_send_temp"}},[r("el-radio",{attrs:{label:"1",value:"1"},nativeOn:{click:function(t){t.preventDefault(),e.calTemp()}}},[e._v("是")]),e._v(" "),r("el-radio",{attrs:{label:"2",value:"2"}},[e._v("否")])],1)],1),e._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:"1"===e.ruleForm.is_send_temp,expression:"ruleForm.is_send_temp === '1' "}]},[r("el-form-item",{attrs:{label:e.input_tag.temp_title.name}},[r("p",[e._v(e._s(e.input_tag.temp_title.value))])]),e._v(" "),r("el-form-item",{attrs:{label:e.input_tag.temp_id.name,prop:"temp_id"}},[r("el-input",{staticStyle:{width:"250px"},attrs:{placeholder:""},model:{value:e.ruleForm.temp_id,callback:function(t){e.ruleForm.temp_id=t},expression:"ruleForm.temp_id"}})],1),e._v(" "),r("div",{staticClass:"el-form--inline"},[r("el-form-item",{attrs:{label:"",prop:"jump_type"}},[r("el-form-item",{staticStyle:{"margin-left":"70px"},attrs:{label:""}},[r("el-radio",{attrs:{label:"1"},model:{value:e.ruleForm.jump_type,callback:function(t){e.ruleForm.jump_type=t},expression:"ruleForm.jump_type"}},[e._v("跳转链接")])],1),e._v(" "),r("el-form-item",{attrs:{label:"",prop:"jump_url"}},[r("el-select",{staticStyle:{width:"120px"},model:{value:e.ruleForm.jump_url,callback:function(t){e.ruleForm.jump_url=t},expression:"ruleForm.jump_url"}},e._l(e.enum_des.jump_url,function(e,t){return r("el-option",{key:t,attrs:{label:e,value:t}})}))],1),e._v(" "),r("el-form-item",{staticStyle:{"margin-left":"20px"},attrs:{label:""}},[r("el-radio",{attrs:{label:"2"},model:{value:e.ruleForm.jump_type,callback:function(t){e.ruleForm.jump_type=t},expression:"ruleForm.jump_type"}},[e._v("自定义链接")])],1),e._v(" "),r("el-form-item",{attrs:{label:"",prop:"auto_jump_url"}},[r("el-input",{staticStyle:{width:"250px"},attrs:{placeholder:""},model:{value:e.ruleForm.auto_jump_url,callback:function(t){e.ruleForm.auto_jump_url=t},expression:"ruleForm.auto_jump_url"}})],1),e._v(" "),r("el-form-item",{staticStyle:{"margin-left":"20px"},attrs:{label:""}},[r("el-radio",{attrs:{label:"3"},model:{value:e.ruleForm.jump_type,callback:function(t){e.ruleForm.jump_type=t},expression:"ruleForm.jump_type"}},[e._v("无链接")])],1)],1)],1)],1)],1),e._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:"1"===e.ruleForm.is_send_temp,expression:"ruleForm.is_send_temp === '1' "}]},[r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[e._v("消息内容")])]),e._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"first"}},[r("el-input",{attrs:{placeholder:""},model:{value:e.ruleForm.first,callback:function(t){e.ruleForm.first=t},expression:"ruleForm.first"}})],1),e._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword1"}},[r("el-input",{attrs:{placeholder:""},model:{value:e.ruleForm.keyword1,callback:function(t){e.ruleForm.keyword1=t},expression:"ruleForm.keyword1"}})],1),e._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword2"}},[r("el-input",{attrs:{placeholder:""},model:{value:e.ruleForm.keyword2,callback:function(t){e.ruleForm.keyword2=t},expression:"ruleForm.keyword2"}})],1),e._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword3"}},[r("el-input",{attrs:{placeholder:""},model:{value:e.ruleForm.keyword3,callback:function(t){e.ruleForm.keyword3=t},expression:"ruleForm.keyword3"}})],1),e._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"keyword4"}},[r("el-input",{attrs:{placeholder:""},model:{value:e.ruleForm.keyword4,callback:function(t){e.ruleForm.keyword4=t},expression:"ruleForm.keyword4"}})],1),e._v(" "),r("el-form-item",{staticClass:"steup-auto-width",attrs:{label:"remark"}},[r("el-input",{attrs:{placeholder:""},model:{value:e.ruleForm.remark,callback:function(t){e.ruleForm.remark=t},expression:"ruleForm.remark"}})],1)],1),e._v(" "),r("el-row",{staticClass:"gray-bg"},[r("el-col",{staticStyle:{"padding-right":"35px"},attrs:{span:8}},[r("p",{staticClass:"steup-msg-title"},[e._v("模版消息提示")]),e._v(" "),e._l(e.page_hint.temp_hint,function(t,l){return r("p",{key:l,staticClass:"steup-msg-word"},[e._v(e._s(t))])})],2),e._v(" "),r("el-col",{attrs:{span:8}},[r("p",{staticClass:"steup-msg-title"},[e._v("模版详细内容")]),e._v(" "),e._l(e.page_hint.temp_contet_hint,function(t,l){return r("p",{key:l,staticClass:"steup-msg-word"},[e._v(e._s(t))])})],2),e._v(" "),r("el-col",{attrs:{span:6}},[r("p",{staticClass:"steup-msg-title"},[e._v("模版可用字段")]),e._v(" "),e._l(e.page_hint.temp_field_hint,function(t,l){return r("p",{key:l,staticClass:"steup-msg-word"},[e._v(e._s(t))])})],2)],1),e._v(" "),r("el-row",{staticStyle:{"margin-top":"25px"},attrs:{type:"flex",justify:"center"}},[r("el-button",{staticClass:"jfk-button--middle",attrs:{type:"primary",loading:e.posting,size:"large"},nativeOn:{click:function(t){t.preventDefault(),e.submitData("ruleForm")}}},[e._v(e._s(e.postText))]),e._v(" "),r("el-button",{staticClass:"jfk-button--middle",attrs:{size:"large"},nativeOn:{click:function(t){t.preventDefault(),e.cancelData()}}},[e._v("取消发送")])],1)],1)],1)},s=[function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[e._v("新建优惠券发放任务")])])}],a={render:l,staticRenderFns:s};t.a=a}});