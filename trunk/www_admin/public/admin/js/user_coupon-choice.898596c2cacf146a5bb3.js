webpackJsonp([41],{258:function(e,t,r){e.exports={default:r(408),__esModule:!0}},408:function(e,t,r){r(410),e.exports=r(121).Object.keys},409:function(e,t,r){var l=r(124),s=r(121),a=r(134);e.exports=function(e,t){var r=(s.Object||{})[e]||Object[e],o={};o[e]=t(r),l(l.S+l.F*a(function(){r(1)}),"Object",o)}},410:function(e,t,r){var l=r(145),s=r(144);r(409)("keys",function(){return function(e){return s(l(e))}})},594:function(e,t,r){"use strict";function l(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=r(258),a=l(s),o=r(166),n=l(o),i=r(236),u=r(407),m=r(238),_=/^[1-9]\d*$/;t.default={data:function(){var e=this,t=function(t,r,l){return""===r?(e.errorScroll(),l(new Error(t.message))):l()},r=function(t,r,l){return""===r?(e.errorScroll(),l(new Error(t.message))):l()},l=function(t,r,l){if("1"===e.ruleForm.send_type){if(""===r)return e.errorScroll(),l(new Error("请输入数量"));if(!_.test(r))return e.errorScroll(),l(new Error("必须为正整数"))}return l()},s=function(t,r,l){if("2"===e.ruleForm.send_time_mode){var s=r.split(" ")[1];if(""===r)return e.errorScroll(),l(new Error("请输入时间"));if(r<e.getTime())return e.errorScroll(),l(new Error("定时发送时间必须为至少一个小时之后"));if(s<"08:00:00"||s>"23:30:00")return l(new Error("定时发送时间必须为早上8点至晚上11点半"))}return l()},a=function(t,r,l){return"1"===e.ruleForm.send_target&&0===r.length?l(new Error(t.message)):l()},o=function(t,r,l){return"2"===e.ruleForm.send_target&&""===r?l(new Error(t.message)):l()},n=function(t,r,l){return"2"===e.ruleForm.send_target&&""===r?l(new Error(t.message)):l()},i=function(t,r,l){return"2"===e.ruleForm.send_target&&"r"===e.ruleForm.rfm&&""===r?l(new Error(t.message)):l()},u=function(t,r,l){return"2"===e.ruleForm.send_target&&"f"===e.ruleForm.rfm&&""===r?l(new Error(t.message)):l()},m=function(t,r,l){return"2"===e.ruleForm.send_target&&"m"===e.ruleForm.rfm&&""===r?l(new Error(t.message)):l()},d=function(t,r,l){return"3"===e.ruleForm.send_target&&""===r?l(new Error(t.message)):l()},c=function(t,r,l){return"3"===e.ruleForm.send_target&&"1"===e.ruleForm.send_target_type&&""===r?l(new Error(t.message)):l()},p=function(t,r,l){return"3"===e.ruleForm.send_target&&"2"===e.ruleForm.send_target_type&&""===r?l(new Error(t.message)):l()},v=function(t,r,l){if("1"===e.ruleForm.send_type){if(""===r)return e.errorScroll(),l(new Error("请选择优惠券"));if(0===e.enum_des.card[r].state)return e.errorScroll(),l(new Error(e.enum_des.card[r].err_msg))}return l()},f=function(t,r,l){if("2"===e.ruleForm.send_type){if(""===r)return e.errorScroll(),l(new Error("请选择礼包"));if(0===e.enum_des.package[r].state)return e.errorScroll(),l(new Error(e.enum_des.package[r].err_msg))}return l()};return{pickerOptions:{disabledDate:function(e){return e.getTime()<Date.now()-864e5}},ruleForm:{task_name:"",send_time_mode:"1",send_time:"",send_type:"1",send_count:"",receive_repeat:!1,send_value:"",coupon_value:"",package_value:"",repeatget:"",send_target:"1",source:"",rfm:"",r_level:"",f_level:"",m_level:"",send_target_type:"",send_target_value:"",send_target_field:"1",send_target_file:"",send_member_lvl:[]},isIndeterminate:!0,memberCheckAll:!0,memberOption:[],settest:"手机号码",file_list:[],test:"录入",unload:!1,rules:{task_name:[{validator:t,message:"请输入活动名称",trigger:"blur"}],send_time_mode:[{validator:r,message:"请选择任务发送模式",trigger:"change"}],send_time:[{validator:s,trigger:"change"}],send_count:[{validator:l,trigger:"blur"}],coupon_value:[{validator:v,trigger:"change"}],package_value:[{validator:f,trigger:"change"}],send_member_lvl:[{validator:a,message:"请选择会员等级",trigger:"change"}],source:[{validator:o,message:"请选择类型",trigger:"change"}],rfm:[{validator:n,message:"请选择消费类型",trigger:"change"}],r_level:[{validator:i,message:"请选择活跃用户",trigger:"change"}],f_level:[{validator:u,message:"请选择用户",trigger:"change"}],m_level:[{validator:m,message:"请选择贡献度",trigger:"change"}],send_target_type:[{validator:d,message:"选择导入方式",trigger:"click"}],send_target_value:[{validator:c,message:"请输入内容",trigger:"click"}],send_target_file:[{validator:p,message:"请上存文件",trigger:"change"}]}}},methods:(0,n.default)({},(0,i.mapMutations)([u.UPDATE_COUPON_STEP]),{NextStep:function(e){var t=this;this.$refs[e].validate(function(e){if(e)return t.setDate(),t[u.UPDATE_COUPON_STEP]({increment:!0})})},setDate:function(){this.sumbit_data.task_name=this.ruleForm.task_name,this.sumbit_data.send_type=this.ruleForm.send_type,""!==this.ruleForm.send_time_mode&&(this.sumbit_data.send_time_mode=this.ruleForm.send_time_mode),"1"===this.ruleForm.send_type?this.sumbit_data.send_value=this.ruleForm.coupon_value:this.sumbit_data.send_value=this.ruleForm.package_value,""!==this.ruleForm.send_count?this.sumbit_data.send_count=this.ruleForm.send_count:this.sumbit_data.send_count=1,""!==this.ruleForm.send_time&&(this.sumbit_data.send_time=this.ruleForm.send_time),""!==this.ruleForm.send_target&&(this.sumbit_data.send_target=this.ruleForm.send_target),""!==this.ruleForm.send_member_lvl&&(this.sumbit_data.send_member_lvl=this.ruleForm.send_member_lvl),""!==this.ruleForm.source&&(this.sumbit_data.source=this.ruleForm.source),""!==this.ruleForm.rfm&&(this.sumbit_data.rfm=this.ruleForm.rfm),""!==this.ruleForm.r_level&&(this.sumbit_data.r_level=this.ruleForm.r_level),""!==this.ruleForm.f_level&&(this.sumbit_data.f_level=this.ruleForm.f_level),""!==this.ruleForm.m_level&&(this.sumbit_data.m_level=this.ruleForm.m_level),""!==this.ruleForm.send_target_type&&(this.sumbit_data.send_target_type=this.ruleForm.send_target_type),""!==this.ruleForm.send_target_value&&(this.sumbit_data.send_target_value=this.ruleForm.send_target_value),""!==this.ruleForm.send_target_field&&(this.sumbit_data.send_target_field=this.ruleForm.send_target_field),""!==this.ruleForm.send_target_file&&(this.sumbit_data.send_target_file=this.ruleForm.send_target_file),this.ruleForm.receive_repeat?this.sumbit_data.receive_repeat=1:this.sumbit_data.receive_repeat=2},changeType:function(e,t){var r=this;this.items.forEach(function(e){r.$set(e,"active",!1)}),this.$set(e,"active",!0)},dataChange:function(e){this.ruleForm.send_time=e},handleRemove:function(){this.unload=!1},handleResponse:function(){alert("服务器问题，上传失败"),this.unload=!1},handleSuccess:function(e,t){0===e.error&&(this.ruleForm.send_target_file=e.url)},handleProgress:function(e){this.unload=!0},checkAllMember:function(e){this.ruleForm.send_member_lvl=e.target.checked?(0,a.default)(this.enum_des["send_member_lvl[]"]):[],this.isIndeterminate=!1},checkedMemberChange:function(e){var t=e.length,r=(0,a.default)(this.enum_des["send_member_lvl[]"]).length;this.memberCheckAll=t===r,this.isIndeterminate=t>0&&t<r},errorScroll:function(){window.scroll(0,230)},getTime:function(){var e=new Date;e.setHours(e.getHours()+1);var t=e.getMonth()+1,r=e.getDate();return t>=1&&t<=9&&(t="0"+t),r>=0&&r<=9&&(r="0"+r),e.getFullYear()+"-"+t+"-"+r+" "+e.getHours()+":"+e.getMinutes()+":"+e.getSeconds()},beforeAvatarUpload:function(e){var t=e.name.indexOf(".xlsx")>0,r=e.name.indexOf(".xls")>0,l=e.name.indexOf(".csv")>0,s=e.size/1024/1024<.5;return t||r||l?s?void 0:(this.$message.error("上传模板大小不能超过 500KB!"),!1):(this.$message.error("上传模板只能是 xls,xlsx,csv 格式!"),!1)}}),computed:(0,n.default)({},(0,i.mapState)(["enum_des","enum_des_selected","input_tag","page_field","page_hint","page_resource","temp_input_group","text_selected","sumbit_data"])),watch:{enum_des_selected:function(e){(0,m.formatUrlParams)(location.search).id&&(this.ruleForm.send_time_mode=e.send_time_mode,this.ruleForm.coupon_value=e.send_value,this.ruleForm.send_target=e.send_target,this.ruleForm.send_type=e.send_type,"1"===e.send_type?this.ruleForm.coupon_value=e.send_value:this.ruleForm.package_value=e.send_value,this.ruleForm.send_member_lvl=e["send_member_lvl[]"],this.ruleForm.source=e.source.toString(),this.ruleForm.rfm=e.rfm,this.ruleForm.r_level=e.rfm_level.toString(),this.ruleForm.f_level=e.rfm_level.toString(),this.ruleForm.m_level=e.rfm_level.toString(),this.ruleForm.send_target_field=e.send_target_field.toString(),this.ruleForm.send_target_value=e.send_target_value,this.ruleForm.send_target_file=e.send_target_file,this.ruleForm.send_target_type=e.target_type,this.file_list=[{name:e.send_target_file,url:e.send_target_file,status:"finished"}])},input_tag:function(e){(0,m.formatUrlParams)(location.search).id&&(this.ruleForm.task_name=e.task_name.value,"1"===e.receive_repeat.value&&(this.ruleForm.receive_repeat=!0))},text_selected:function(e){(0,m.formatUrlParams)(location.search).id&&(this.ruleForm.send_count=e.send_count,this.ruleForm.send_time=e.send_time)}}}},689:function(e,t,r){t=e.exports=r(75)(!1),t.push([e.i,"a{text-decoration:none}.send-word{padding:6px 0;margin-right:10px;display:inline-block;font-size:15px}#j-upload{color:#ac9456;background-color:#f6f6f6;border:1px solid #ededed}.up-date-title{font-size:15px}.upload-title{line-height:15px;overflow:hidden;height:40px}.upload-title *{float:left;margin-right:15px}.upload-speed{background-color:#cbb58c;height:15px;width:80px;border:1px solid #b2945e}.unload-delete{cursor:pointer;color:#b2945e}.upload-word{margin-top:15px;color:gray;font-size:15px}.import-release{border:1px solid #e5e5e5}.jfk-pages__price .set-wrapper-title{font-size:15px;padding-left:20px;padding-bottom:0;background-color:#f6f6f6}.set-wrapper-title>span{margin-right:10px;padding:0 15px 10px;display:inline-block;cursor:pointer}.set-wrapper-title .active{border-bottom:1px solid #ac9456}.el-upload-list{margin-left:-100px}",""])},772:function(e,t,r){var l=r(689);"string"==typeof l&&(l=[[e.i,l,""]]),l.locals&&(e.exports=l.locals);r(76)("d37e2ce2",l,!0)},839:function(e,t,r){r(772);var l=r(165)(r(594),r(876),null,null);e.exports=l.exports},876:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[e._m(0),e._v(" "),r("div",[r("el-row",[r("el-col",{attrs:{span:11}},[r("el-col",{staticClass:"choice-step-num choice-step-active",attrs:{span:4}},[e._v("\n\t\t\t  \t \t01\n\t\t\t  \t")]),e._v(" "),r("el-col",{attrs:{span:20}},[r("p",{staticClass:"choice-step-title"},[e._v("选择优惠券发放内容和目标用户")]),e._v(" "),r("p",{staticClass:"choice-step-word"},[e._v("选择发送的优惠券或礼包，并选择需要发送的用户群体")])])],1),e._v(" "),r("el-col",{staticClass:"jfk-ta-c",attrs:{span:2}},[r("div",{staticClass:"choice-line"})]),e._v(" "),r("el-col",{attrs:{span:11}},[r("el-col",{staticClass:"choice-step-num",attrs:{span:4}},[e._v("\n\t\t\t  \t \t02\n\t\t\t  \t")]),e._v(" "),r("el-col",{attrs:{span:20}},[r("p",{staticClass:"choice-step-title"},[e._v("设置模版消息")]),e._v(" "),r("p",{staticClass:"choice-step-word"},[e._v("设置一个模版消息，给收到优惠的用户发送一个模版消息")])])],1)],1)],1),e._v(" "),r("el-form",{ref:"ruleForm",attrs:{model:e.ruleForm,rules:e.rules}},[r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[e._v("任务信息")])]),e._v(" "),r("div",{staticClass:"choice-rows"},[r("el-form-item",{staticStyle:{margin:"0"},attrs:{label:"请填写任务名称",prop:"task_name"}},[r("el-input",{staticStyle:{width:"250px"},attrs:{maxlength:"15",placeholder:"必填，最多15个汉字"},model:{value:e.ruleForm.task_name,callback:function(t){e.ruleForm.task_name=t},expression:"ruleForm.task_name"}})],1),e._v(" "),r("div",{staticClass:"el-form--inline",staticStyle:{"margin-top":"30px"}},[r("el-form-item",{attrs:{label:"",prop:"send_time_mode"}},[r("el-radio-group",{model:{value:e.ruleForm.send_time_mode,callback:function(t){e.ruleForm.send_time_mode=t},expression:"ruleForm.send_time_mode"}},[r("el-radio",{staticStyle:{"margin-right":"35px"},attrs:{label:"1",value:"1"}},[e._v("马上发送")]),e._v(" "),r("el-radio",{attrs:{label:"2",value:"2"}},[e._v("定时发送")])],1)],1),e._v(" "),r("el-form-item",{directives:[{name:"show",rawName:"v-show",value:"2"===e.ruleForm.send_time_mode,expression:"ruleForm.send_time_mode === '2'"}],attrs:{label:"",prop:"send_time"}},[r("el-date-picker",{attrs:{type:"datetime",editable:!1,placeholder:"选择日期时间","picker-options":e.pickerOptions},on:{change:e.dataChange},model:{value:e.ruleForm.send_time,callback:function(t){e.ruleForm.send_time=t},expression:"ruleForm.send_time"}})],1)],1)],1),e._v(" "),r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[e._v("选择发送内容")])]),e._v(" "),r("div",{staticClass:"choice-rows"},[r("el-form-item",{attrs:{label:""}},[r("el-radio-group",{model:{value:e.ruleForm.send_type,callback:function(t){e.ruleForm.send_type=t},expression:"ruleForm.send_type"}},e._l(e.enum_des.send_type,function(t,l){return r("el-radio",{key:l,attrs:{label:l,value:l}},[e._v("\n\t\t\t\t        "+e._s(t)+"\n\t\t\t\t  \t    ")])}))],1),e._v(" "),r("div",{staticClass:"el-form--inline gray-bg"},[r("el-form-item",{directives:[{name:"show",rawName:"v-show",value:"1"===e.ruleForm.send_type,expression:"ruleForm.send_type === '1' "}],attrs:{label:"",prop:"coupon_value"}},[r("el-select",{staticStyle:{width:"200px"},attrs:{placeholder:"选择优惠券"},model:{value:e.ruleForm.coupon_value,callback:function(t){e.ruleForm.coupon_value=t},expression:"ruleForm.coupon_value"}},e._l(e.enum_des.card,function(e,t){return r("el-option",{key:t,attrs:{label:e.title,value:e.card_id}})}))],1),e._v(" "),r("el-form-item",{directives:[{name:"show",rawName:"v-show",value:"2"===e.ruleForm.send_type,expression:"ruleForm.send_type === '2' "}],attrs:{label:"",prop:"package_value"}},[r("el-select",{staticStyle:{width:"200px"},attrs:{placeholder:"选择礼包"},model:{value:e.ruleForm.package_value,callback:function(t){e.ruleForm.package_value=t},expression:"ruleForm.package_value"}},e._l(e.enum_des.package,function(e,t){return r("el-option",{key:t,attrs:{label:e.name,value:e.package_id}})}))],1),e._v(" "),r("el-form-item",{directives:[{name:"show",rawName:"v-show",value:"1"===e.ruleForm.send_type,expression:"ruleForm.send_type === '1' "}],attrs:{label:"",prop:"send_count"}},[r("el-input",{staticStyle:{width:"120px","margin-right":"10px"},attrs:{placeholder:"每人发送数量"},model:{value:e.ruleForm.send_count,callback:function(t){e.ruleForm.send_count=t},expression:"ruleForm.send_count"}})],1),e._v(" "),r("span",{directives:[{name:"show",rawName:"v-show",value:"2"===e.ruleForm.send_type,expression:"ruleForm.send_type === '2' "}],staticClass:"send-word"},[e._v("礼包默认发送一个")]),e._v(" "),r("el-form-item",[r("el-checkbox",{model:{value:e.ruleForm.receive_repeat,callback:function(t){e.ruleForm.receive_repeat=t},expression:"ruleForm.receive_repeat"}},[e._v(e._s(e.input_tag.receive_repeat.name))])],1)],1),e._v(" "),r("el-row",{staticClass:"choice-tips"},[r("div",[e._v("提示 :")]),e._v(" "),r("div",[r("p",[e._v(" 请确保需要发放的优惠券/礼包，库存充足"),r("br"),e._v(" 勾选重复领取则已领取过该优惠的用户可以再次领取，否则不可以领取。")])])])],1),e._v(" "),r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[e._v("发送目标用户")])]),e._v(" "),r("div",{staticClass:"choice-rows"},[r("el-form-item",{attrs:{label:""}},[r("el-radio-group",{staticClass:"choice-radio-right",model:{value:e.ruleForm.send_target,callback:function(t){e.ruleForm.send_target=t},expression:"ruleForm.send_target"}},e._l(e.enum_des.send_target,function(t,l){return r("el-radio",{key:l,attrs:{label:l,value:t}},[e._v("\n\t\t\t\t        "+e._s(t)+"\n\t\t\t\t  \t   ")])}))],1),e._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:"1"===e.ruleForm.send_target,expression:"ruleForm.send_target === '1' "}],staticClass:"el-form--inline gray-bg"},[r("div",{staticClass:"el-form--inline choice-checkbox-right"},[r("el-form-item",[r("el-checkbox",{attrs:{indeterminate:e.isIndeterminate},on:{change:e.checkAllMember},model:{value:e.memberCheckAll,callback:function(t){e.memberCheckAll=t},expression:"memberCheckAll"}},[e._v("全选")])],1),e._v(" "),r("el-form-item",{attrs:{prop:"send_member_lvl"}},[r("el-checkbox-group",{model:{value:e.ruleForm.send_member_lvl,callback:function(t){e.ruleForm.send_member_lvl=t},expression:"ruleForm.send_member_lvl"}},e._l(e.enum_des["send_member_lvl[]"],function(t,l){return r("el-checkbox",{key:l,attrs:{label:l,value:t},on:{change:e.checkedMemberChange}},[e._v("\n\t\t\t\t\t\t\t        "+e._s(t)+"\n\t\t\t\t\t\t\t  \t  ")])}))],1)],1)]),e._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:"2"===e.ruleForm.send_target,expression:"ruleForm.send_target === '2' "}],staticClass:"el-form--inline gray-bg"},[r("el-form-item",{attrs:{prop:"source"}},[r("el-select",{staticStyle:{width:"140px"},attrs:{placeholder:"请选择类型"},model:{value:e.ruleForm.source,callback:function(t){e.ruleForm.source=t},expression:"ruleForm.source"}},e._l(e.enum_des.source,function(e,t){return r("el-option",{key:t,attrs:{label:e,value:t}})}))],1),e._v(" "),r("el-form-item",{attrs:{prop:"rfm"}},[r("el-select",{staticStyle:{width:"160px"},attrs:{placeholder:"请选择消费类型"},model:{value:e.ruleForm.rfm,callback:function(t){e.ruleForm.rfm=t},expression:"ruleForm.rfm"}},e._l(e.enum_des.rfm,function(e,t){return r("el-option",{key:t,attrs:{label:e,value:t}})}))],1),e._v(" "),r("el-form-item",{directives:[{name:"show",rawName:"v-show",value:"r"===e.ruleForm.rfm,expression:"ruleForm.rfm === 'r' "}],attrs:{prop:"r_level"}},[r("el-select",{staticStyle:{width:"160px"},attrs:{placeholder:"请选择活跃用户"},model:{value:e.ruleForm.r_level,callback:function(t){e.ruleForm.r_level=t},expression:"ruleForm.r_level"}},e._l(e.enum_des.r_level,function(e,t){return r("el-option",{key:t,attrs:{label:e,value:t}})}))],1),e._v(" "),r("el-form-item",{directives:[{name:"show",rawName:"v-show",value:"f"===e.ruleForm.rfm,expression:"ruleForm.rfm === 'f' "}],attrs:{prop:"f_level"}},[r("el-select",{staticStyle:{width:"160px"},attrs:{placeholder:"请选择用户"},model:{value:e.ruleForm.f_level,callback:function(t){e.ruleForm.f_level=t},expression:"ruleForm.f_level"}},e._l(e.enum_des.f_level,function(e,t){return r("el-option",{key:t,attrs:{label:e,value:t}})}))],1),e._v(" "),r("el-form-item",{directives:[{name:"show",rawName:"v-show",value:"m"===e.ruleForm.rfm,expression:"ruleForm.rfm === 'm' "}],attrs:{prop:"m_level"}},[r("el-select",{staticStyle:{width:"160px"},attrs:{placeholder:"请选择贡献度"},model:{value:e.ruleForm.m_level,callback:function(t){e.ruleForm.m_level=t},expression:"ruleForm.m_level"}},e._l(e.enum_des.m_level,function(e,t){return r("el-option",{key:t,attrs:{label:e,value:t}})}))],1)],1),e._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:"3"===e.ruleForm.send_target,expression:"ruleForm.send_target === '3' "}],staticClass:"import-release"},[r("el-row",{staticClass:"set-wrapper-title"},e._l(e.enum_des.send_target_field,function(t,l){return r("span",{class:{active:e.ruleForm.send_target_field===l},on:{click:function(r){e.ruleForm.send_target_field=l,e.settest=t}}},[e._v(e._s(t))])})),e._v(" "),r("div",{staticStyle:{padding:"0px 20px 30px 20px"}},[r("el-row",e._l(e.enum_des.send_target_type,function(t,l){return r("el-col",{key:l,staticClass:"el-form--inline",attrs:{span:12}},[r("el-form-item",{attrs:{prop:"send_target_type"}},["1"===l?r("el-form-item",{staticStyle:{"margin-bottom":"0px"},attrs:{label:""}},[r("el-radio",{key:l,attrs:{label:l,value:l},model:{value:e.ruleForm.send_target_type,callback:function(t){e.ruleForm.send_target_type=t},expression:"ruleForm.send_target_type"}},[e._v("\n\t\t\t\t\t\t       \t\t\t"+e._s(e.test+e.settest)+"\n\t\t\t\t\t\t\t\t  \t   ")])],1):e._e(),e._v(" "),"2"===l?r("el-form-item",{staticStyle:{"margin-bottom":"0px"},attrs:{label:""}},[r("el-radio",{key:l,attrs:{label:l,value:l},model:{value:e.ruleForm.send_target_type,callback:function(t){e.ruleForm.send_target_type=t},expression:"ruleForm.send_target_type"}},[e._v("\n\t\t\t\t\t\t       \t\t\t"+e._s(t)+"\n\t\t\t\t\t\t\t\t  \t   ")])],1):e._e(),e._v(" "),"2"===l?r("el-form-item",{staticStyle:{"margin-bottom":"0px"},attrs:{label:"",prop:"send_target_file"}},[r("el-upload",{directives:[{name:"show",rawName:"v-show",value:"2"===e.ruleForm.send_target_type,expression:"ruleForm.send_target_type === '2'"}],attrs:{action:"/index.php/basic/uploadftp/do_upload?file_post_name=imgFile",name:"imgFile",multiple:!1,"on-progress":e.handleProgress,"on-error":e.handleResponse,"on-remove":e.handleRemove,"on-success":e.handleSuccess,"file-list":e.file_list,"before-upload":e.beforeAvatarUpload}},[r("el-button",{directives:[{name:"show",rawName:"v-show",value:!1===e.unload,expression:"unload === false "}],attrs:{type:"primary",id:"j-upload"}},[e._v("点击上传")])],1)],1):e._e()],1)],1)})),e._v(" "),r("el-row",[r("el-col",{attrs:{span:12}},[r("el-form-item",{attrs:{prop:"send_target_value"}},[r("el-input",{staticStyle:{width:"80%","margin-left":"20px"},attrs:{type:"textarea",rows:5,placeholder:"请以英文,分隔"},model:{value:e.ruleForm.send_target_value,callback:function(t){e.ruleForm.send_target_value=t},expression:"ruleForm.send_target_value"}})],1)],1),e._v(" "),r("el-col",{attrs:{span:12}},[r("div",[r("a",{staticClass:"el-button el-button--primary",attrs:{href:e.page_resource.links.download}},[e._v("下载导入模版")])]),e._v(" "),r("div",{staticClass:"upload-word"},[r("p",[e._v("只能上传 xls或xlsx文件 , 且不超过 500KB")]),e._v(" "),r("p",[e._v("请根据您所选择的发送名单类型 , 将数据录入到导入模版中")])])])],1)],1)],1)],1),e._v(" "),r("el-row",{staticStyle:{"margin-top":"25px"},attrs:{type:"flex",justify:"center"}},[r("el-button",{staticClass:"jfk-button--middle",attrs:{type:"primary",size:"large"},nativeOn:{click:function(t){t.preventDefault(),e.NextStep("ruleForm")}}},[e._v("下一步")]),e._v(" "),r("a",{staticClass:"el-button jfk-button--middle el-button--default el-button--large",staticStyle:{"margin-left":"10px"},attrs:{href:e.page_resource.links.list}},[e._v("返回")])],1)],1)],1)},staticRenderFns:[function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"jfk-fieldset__hd"},[r("div",{staticClass:"jfk-fieldset__title"},[e._v("新建优惠券发放任务")])])}]}}});