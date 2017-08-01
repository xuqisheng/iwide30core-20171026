<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EA_block_admin extends EA_base
{
    public $session;
    
	public function __construct()
	{
	     return parent::__construct();
	}

	public static function inst($className=__CLASS__)
	{
		return parent::inst($className);
	}
	
	/**
	 * 直接输出管理员权限列表
	 * @return multitype:string
	 */
	public function get_acl_array($session)
	{
		/*$acl= array(
		     'adminhtml'=> array(
		        'module'=> array(
		 		    'controller'=> array('index','empty'),
		 		    ...
			 'adminhtml'=> ALL_PRIVILEGES,
		);*/
        $acl_array= $session->allow_actions;
		if( !$acl_array ) {
		    //重新读取，并写入？
		    //赋予会话权限信息
		    $acl_array= array();
		}
		return $acl_array;
	}
	
	/**
	 * print_r($nodes);
Array (
    [8] => Array (
        [name] => qr_code
        [label] => 参数二维码
        [url] => #
        [child] => Array (
            [8] => Array (
                [target] => navTab
                [title] => 参数二维码
                [href] => privilege/node/grid
                [label] => 参数二维码
                [icon] => 
                [child] => Array (
                    [64] => Array (
                        [target] => navTab
                        [title] => 多图文回复
                        [href] => privilege/node/grid
                        [label] => 多图文回复
                        [icon] => 
                        [child] => Array
                        (
                        )
                    )
                )
            )
        )
    )
	 * 
	 * print_r($nodes);
Array(
    [adminhtml] => Array(
        [privilege] => Array(
            [node] => Array(
                [0] => view
                [1] => index
                [2] => delete
            )
        )
    )
	 * 
	 * model校验账号密码、session写入acl信息后，获取acl，根据权限生成1+2=3级  菜单数组，结构如上
	 * 菜单不出来原因：
	 *     1，一级、二级菜单使用了非法 href 属性：#，空 等会导致下级菜单被隐藏
	 */
	public function build_menu( $nodes, $acl, $session )
	{
	    //无权限/未登录时，返回空菜单。
	    if( !isset($acl[ADMINHTML]) ) return array();
	    
	    //根据权限定制数组清除没有访问权的菜单项
    	else {
//print_r($nodes);die;
//print_r($acl);die;
            $acl_flat= $acl[ADMINHTML];

            //部分菜单只会开发人员开放
            $admin_profile= $session->get_admin_profile();
            $writelist= array('libinyan', 'luguihong', 'F.oris');
            $hide_tookit= in_array($admin_profile['username'], $writelist)? FALSE: TRUE;
            $toolkit_ids= array( 57, );

            //中心平台只对部分管理员开放
            $center_writelist= array('luguihong','F.oris','zxpt-001','jfkpingtai','jianlei1','jianlei');
            $center_hide_tookit= in_array($admin_profile['username'], $center_writelist)? FALSE: TRUE;
            $center_toolkit_ids= array( 14 );
            
    		//build menu array when not be full privilege
	    	foreach ($nodes as $k=> $v) {
			//level 1: Project elements
                
                //如不需隐藏去掉下面一行即可
    		    if( in_array($k, $center_toolkit_ids) && $center_hide_tookit ) unset($nodes[$k]); else 

	    	    if( isset($nodes[$k]['child']) && count($nodes[$k]['child'])>0 ) {
		    		foreach ($nodes[$k]['child'] as $sk=> $sv) {
		    		//level 2: Group elements, 

		    		    //如不需隐藏去掉下面一行即可
		    		    if( in_array($sk, $toolkit_ids) && $hide_tookit ) unset($nodes[$k]['child'][$sk]); else 
		    		        
		    		    if( isset($nodes[$k]['child'][$sk]['child']) && count($nodes[$k]['child'][$sk]['child'])>0 ) {
			    			foreach ($nodes[$k]['child'][$sk]['child'] as $ssk=> $ssv) {
			    			//level 3: Item elements
								$href= explode('/', $ssv['href']);
			    				if(isset($href[0])) $m= strtolower($href[0]);
								if(isset($href[1])) $c= strtolower($href[1]);
								if(isset($href[2])) $a= strtolower($href[2]);
								else $a= 'index';

								if( $acl_flat != FULL_ACCESS ){
								    //非超级权限
								    if(count($href)==0 || !isset($acl_flat[$m]) || !isset($acl_flat[$m][$c]) 
								        || !in_array( $a, $acl_flat[$m][$c] ) ){
//echo $m. ';'.$c. ';'.$a. ';'.$k. ';'.$sk. ';'.$ssk. "\n";
//print_r($acl_flat[$m]);
								        unset($nodes[$k]['child'][$sk]['child'][$ssk]);
								    }
								}
			    			}
			    			//3级菜单循环完毕，无权限列表的子菜单将会被清除
		    			}
		    			
		    			$href= explode('/', $sv['href']);
		    			if(isset($href[0])) $m= strtolower($href[0]);
		    			if(isset($href[1])) $c= strtolower($href[1]);
		    			if(isset($href[2])) $a= strtolower($href[2]);
						else $a= 'index';
		    			
		    			if( $acl_flat != FULL_ACCESS ){
		    			    //非超级权限
		    			    if(count($href)==0 || !isset($acl_flat[$m]) || !isset($acl_flat[$m][$c]) || !in_array( $a, $acl_flat[$m][$c] ) ){
//echo $m. ';'.$c. ';'.$a. ';'.$k. ';'.$sk. "\n";
//print_r($acl_flat[$m]);
		    			        unset($nodes[$k]['child'][$sk]);
		    			    }
		    			}
		    			
		    			//If item count is 0, unset this Group.
		    			//当以3级菜单为基准的情况下，需判断2级下面是否有子元素，如以2级为住，则不需判断
// 		    			if( !isset($nodes[$k]['child'][$sk]['child']) || count($nodes[$k]['child'][$sk]['child'])==0 ){
// 						    unset($nodes[$k]['child'][$sk]);
// 						}
		    		}
			    	//2级菜单循环完毕，不再权限列表的子菜单将会被清除
	    		}
	    		
		    	//If group count is 0, unset this project( Level1 ).
	    		if( !isset($nodes[$k]['child']) || count($nodes[$k]['child'])==0 )
	    			unset($nodes[$k]);
	    	}
			//1级菜单循环完毕，不再权限列表的子菜单将会被清除
    	}
    	//print_r($nodes);die;
    	return $nodes;
    }
    
    /**
     * AdminLTE  edit from element output render
     * @param $string $name
     * @param Array $config
     * @param MY_model $model
     * @param string $is_default
     * @return string
     */
    public function render_from_element($name, $config, $model, $is_default= TRUE )
    {
        if($name==$model->table_primary_key()) return ''; //表主键不在form中编辑，再form加载时直接放入hidden
        if( isset($config['form_hide']) && $config['form_hide']===TRUE ) return '';

        $html= '';
        $message= '';
        if( $is_default ){
            $status_wrap= '';       //has-success|has-error 2种
            $status_tips= '';       //glyphicon-ok-sign|glyphicon-remove-sign 2种
            
        } else if( form_error($name) ){
            $status_wrap= 'has-error';
            $status_tips= 'glyphicon-remove-sign';
            $message= form_error($name);
        } else {
            $status_wrap= 'has-success';
            $status_tips= 'glyphicon-ok-sign';
            $message= '<span class="glyphicon form-control-feedback glyphicon-ok-sign " aria-hidden="true"></span>';
        }
        
        $placeholder= isset($config['form_tips'])? $config['form_tips']: $config['label'];
        switch ($config['type']){
            case 'textarea':
                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= !empty($value)? $value: $config['form_default'];
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                $html.= 
"<div class='form-group {$status_wrap}'>
	<label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
	<div class='col-sm-8'>
		<textarea class='form-control' name='{$name}' id='el_{$name}' placeholder='{$placeholder}' {$config['form_ui']} >$value</textarea>
	</div>
</div>";
                break;
                
            case 'combobox':
				if( !defined('IS_LOAD_SELECTJS') ){
					//标记，只加载一次
					define('IS_LOAD_SELECTJS', TRUE);
					$html.= 
"
<link rel='stylesheet' href='". base_url(FD_PUBLIC). "/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.css'>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/bootstrap-select/bootstrap-select.min.js'></script>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/bootstrap-select/i18n/defaults-zh_CN.min.js'></script>
";
				}
                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= isset($value)? $value: $config['form_default'];
                $options= $model->hash_to_optionhtml($config['select'], $value);
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                
                /** @see http://silviomoreto.github.io/bootstrap-select/examples/  */
                $html.= 
"<div class='form-group {$status_wrap}'>
	<label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
	<div class='col-sm-8'>
		<select class='form-control selectpicker show-tick' data-live-search='true' name='{$name}' id='el_{$name}' {$config['form_ui']}>{$options}</select>
	</div>
</div>";
                break;

/**
 * Bootstrap 支持以下类型元素  @see http://v3.bootcss.com/css/#forms-controls
 * text、password、datetime、datetime-local、date、month、time、week、number、email、url、search、tel、color
 */
            case 'time':
            case 'datetime-local':
            case 'datetime':
                if( !defined('IS_LOAD_DATETIMEJS') ){
                    //标记，只加载一次
                    define('IS_LOAD_DATETIMEJS', TRUE);
                    $html.=
                    "
<link rel='stylesheet' href='". base_url(FD_PUBLIC). "/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.css'>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/datetimepicker/bootstrap-datetimepicker.js'></script>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js'></script>
";
                }
                
                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= !empty($value)? $value: $config['form_default'];
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                
                /**
                 * @see http://www.bootcss.com/p/bootstrap-datetimepicker/
                */
                $html.=
"<div class='form-group {$status_wrap} has-feedback'>
    <label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
    <div class='col-sm-8' >
        <div class=' input-group date'>
            <input type='text' class='form-control' name='{$name}' size='16' id='el_{$name}' value='{$value}' {$config['form_ui']}>
            <span class='input-group-addon'><i class='glyphicon glyphicon-time'></i></span>
    	</div>{$message}
    </div>
</div>";
                /** online manual docs:
                @see http://eternicode.github.io/bootstrap-datepicker/
                */
                $datetime_format= ($config['type']=='time')? 'hh:ii:ss': 'yyyy-mm-dd hh:ii:ss';
                $html.= '<script type="text/javascript">$("#el_'. $name. '").datetimepicker({
                format:"'. $datetime_format. '", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",'. $config['js_config']. '
				});</script>';
                break;
                
            case 'date':
            case 'month':
            case 'week':
				if( !defined('IS_LOAD_DATEJS') ){
					//标记，只加载一次
					define('IS_LOAD_DATEJS', TRUE);
					$html.= 
"
<link rel='stylesheet' href='". base_url(FD_PUBLIC). "/AdminLTE/plugins/datepicker/datepicker3.css'>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/datepicker/bootstrap-datepicker.js'></script>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.zh-CN.js'></script>
";
				}

                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= !empty($value)? $value: $config['form_default'];
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                
				/** @see http://www.bootcss.com/p/bootstrap-datetimepicker/  */
                $html.= 
"<div class='form-group {$status_wrap} has-feedback'>
	<label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
	<div class='col-sm-8' >
	    <div class=' input-group date'>
			<input type='text' class='form-control' name='{$name}' size='16' id='el_{$name}' value='{$value}' {$config['form_ui']}>
			<span class='input-group-addon'><i class='glyphicon glyphicon-calendar'></i></span>
		</div>{$message}
	</div>
</div>";
				/** online manual docs: 
					@see http://eternicode.github.io/bootstrap-datepicker/
				*/
				$html.= '<script type="text/javascript">$("#el_'. $name. '").datepicker({
					format:"yyyy-mm-dd", language: "zh-CN",clearBtn: false,todayBtn: false,orientation: "auto left",'. $config['js_config']. '
				});</script>';
                break;

            case 'price':
                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= !empty($value)? $value: $config['form_default'];
                if( $config['function'] ){
                    $funp= explode('|', $config['function']);
                    $fun= $funp[0];
                    $funp[0]= $value;
                    $value= call_user_func_array ($fun, $funp);
                }
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                
                $html.= 
"<div class='form-group {$status_wrap} has-feedback'>
    <label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
    <div class='col-sm-8'>
        <div class='input-group'>
           <div class='input-group-addon'>￥</div>
           <input type='{$config['type']}' class='form-control' name='{$name}' id='el_{$name}' placeholder='{$placeholder}' value='{$value}' {$config['form_ui']}>
        </div>{$message}
    </div>
</div>";
                break;
            
            case 'weight':
                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= !empty($value)? $value: $config['form_default'];
                if( $config['function'] ){
                    $funp= explode('|', $config['function']);
                    $fun= $funp[0];
                    $funp[0]= $value;
                    $value= call_user_func_array ($fun, $funp);
                }
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                
                $html.= 
"<div class='form-group {$status_wrap} has-feedback'>
    <label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
    <div class='col-sm-8'>
        <div class='input-group'>
           <input type='{$config['type']}' class='form-control' name='{$name}' id='el_{$name}' placeholder='重量单位为KG' value='{$value}' {$config['form_ui']} />
           <div class='input-group-addon'> KG/千克 </div>
        </div>{$message}
    </div>
</div>";
                break;
            case 'color':
                if( !defined('IS_LOAD_COLORJS') ){
                    //标记，只加载一次
                    define('IS_LOAD_COLORJS', TRUE);
                    $html.="
<link rel='stylesheet' href='". base_url(FD_PUBLIC). "/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.css'>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/colorpickersliders/tinycolor.min.js'></script>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.min.js'></script>
<script src='". base_url(FD_PUBLIC). "/AdminLTE/plugins/colorpickersliders/bootstrap.colorpickersliders.nocielch.min.js'></script>
";
                }
                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= !empty($value)? $value: $config['form_default'];
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                
                /** @see http://www.virtuosoft.eu/code/bootstrap-colorpickersliders/  */
                $html.=
"<div class='form-group {$status_wrap} has-feedback'>
	<label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
    <div class='col-sm-8' >
        <div class=' input-group color'>
            <input type='text' class='form-control' name='{$name}' id='el_{$name}' value='{$value}' >
            <span class='input-group-addon'><i class='fa fa-dashboard'></i></span>
        </div>{$message}
    </div>
</div>";
                /** online manual docs:
                @see http://eternicode.github.io/bootstrap-datepicker/
                */
                $html.= '<script type="text/javascript">$("#el_'. $name. '").ColorPickerSliders({
                	size: "sm", placement: "top", hsvpanel: true, previewformat:"hex"
                });</script>';
                break;
                
                break;
            case 'text':
            case 'password':
            case 'number':
            case 'email':
            case 'url':
            case 'file':
            case 'search':
            case 'tel':
            default: 
                $value= set_value($name)? set_value($name): $model->m_get($name);
                $value= ( $value==='0' || !empty($value))? $value: $config['form_default'];
                if( $config['function'] ){
                	$funp= explode('|', $config['function']);
                 	$fun= $funp[0];
                 	$funp[0]= $value;
                 	$value= call_user_func_array ($fun, $funp);
                }
                $label= isset($config['form_tips'])? "<abbr title='{$config['form_tips']}'>{$config['label']}: </abbr>": $config['label'];
                $extra= '';
                
                if($config['type']=='logo' || $config['type']=='file' ){
                    //针对logo类型，显示缩略图
                    if( $model->m_get($name) ) {
                        $extra= "<span class='input-group-addon'>图片效果预览（圆型）：
                        <span><img src='{$model->m_get($name)}' class='img-circle' width='100' height='100' /></span>&nbsp;&nbsp;&nbsp;（方形）：
                        <span><img src='{$model->m_get($name)}' class='img-polaroid' width='100' height='100' /></span>
                        </span>";
                    } else $extra= "<span class='input-group-addon'>文件大小必须 < <b>1MB</b> </span>";
                    $config['type']= 'file';
                    
                    $html_= "<input type='{$config['type']}' class='form-control ' name='{$name}' id='el_{$name}' placeholder='{$placeholder}' value='{$value}' {$config['form_ui']}>
                        {$extra}{$message}";
                    
                } else {
                    if( isset($config['input_unit']) && $config['input_unit'] ){
                        $html_= "<div class='input-group'>
                            <input type='{$config['type']}' class='form-control ' name='{$name}' id='el_{$name}' placeholder='{$placeholder}' value='{$value}' {$config['form_ui']}>
                            {$config['input_unit']}
                            </div>";
                    } else {
                        $html_= "<input type='{$config['type']}' class='form-control ' name='{$name}' id='el_{$name}' placeholder='{$placeholder}' value='{$value}' {$config['form_ui']}>";
                    }
                }
                
                $html.= 
"<div class='form-group {$status_wrap} has-feedback'>
	<label for='el_{$name}' class='col-sm-2 control-label'>$label</label>
	<div class='col-sm-8'>{$html_}</div>
</div>";
                break;
        }
        return $html;
    }

    
    
    

    /**
     * 将三级菜单数组转换为JSON数据
     * Json 结构
     [{
         "id":1,
         "text":"My Documents",
         "children":[{
             "id":11,
             "text":"Photos",
             "state":"closed",
             "children":[{
                 "id":111,
                 "text":"Friend"
             },{
                 "id":112,
                 "text":"Wife"
             },{
                 "id":113,
                 "text":"Company"
             }]
         }]
     }]
     * @param Array $node_array
     * @return JSON
     * @deprecated 已废弃，专用于 easyUI下的菜单元素输出
     */
    public function json_menu( $node_array )
    {
        $data= array();
        if(count($node_array)>0 ) {
            foreach ($node_array as $k=> $v) {
                //处理一级数组
                $child1= array();
                if(count($v)>0 ) {
                    $child2= array();
                    foreach ($v['child'] as $sk=> $sv) {
                        //处理二级数组
                        if(count($sv)>0 ) {
                            $child3= array();
                            foreach ($sv['child'] as $ssk=> $ssv) {
                                //处理三级数组
                                $tmp3= array(
                                    'id'=> $ssk,
                                    'text'=> $ssv['label'],
                                    'iconCls'=> isset($ssv['icon'])? $ssv['icon']: 'icon-gears',
                                    //'state'=> 'closed',
                                );
                                $child3[$ssk]= $tmp3;
                            }
                        }
                        //print_r($child3);die;
                        $tmp2= array(
                            'id'=> $sk,
                            'text'=> $sv['label'],
                            'iconCls'=> isset($sv['icon'])? $sv['icon']: 'icon-datagrid',
                            //'state'=> 'closed',
                            'children'=> $child3,
                        );
                        $child2[$sk]= $tmp2;
                    }
                }
                //print_r($child2);die;
    
                $tmp1= array(
                    'id'=> $k,
                    'text'=> $v['label'],
                    'iconCls'=> isset($v['icon'])? $v['icon']: 'icon-window',
                    //'state'=> 'closed',
                    'children'=> $child2,
                );
                $data[]= $tmp1;
            }
        }
        return json_encode($data);
    }
    
    /**
     * 根据字段的配置，显示form元素
     * @param Array $field
     * @deprecated 已废弃，专用于 easyUI下的表单元素输出
     */
    public function render_form($name, $config, $model)
    {
        $html= '';
        //主键字段自动设置为hidden
//         if($model->table_primary_key()== $name){
//             $config['type']= 'hidden';
//             //print_r($config);die;
//         }
        
        /*label,text,textarea,checkbox,numberbox,validatebox,datebox,combobox,combotree*/
        $width= '350px';
        switch ($config['type']){
            case 'combobox':
                $options= $model->hash_to_optionhtml($config['select']);
                $html.= "<td>{$config['label']}</td><td>". 
                    "<select class='easyui-combobox' name='{$name}' data-options='{$config['form_ui']}' style='width:{$width}'>{$options}</select></td>";
                break;
            case 'checkbox':
                $html.= '';
                break;
            case 'numberbox':
                $html.= '';
                break;
            case 'validatebox':
                $html.= '';
                break;
            case 'datebox':
                $html.= "<td>{$config['label']}</td><td>". 
                    "<input class='easyui-datebox' name='{$name}' data-options='{$config['form_ui']}' style='width:{$width}'></td>";
                break;
            case 'datetimebox':
                $html.= "<td>{$config['label']}</td><td>". 
                    "<input class='easyui-datetimebox' name='{$name}' data-options='{$config['form_ui']},showSeconds:false' style='width:{$width}'></td>";
                break;
            case 'hidden':
                $html.= "<td colspan='2'><input class='easyui-textbox' name='{$name}' data-options='{$config['form_ui']},showSeconds:false' type='hidden' ></input></td>";
                break;
            case 'textbox':
            default:
                $html.= "<td>{$config['label']}</td><td>". 
                    "<input class='easyui-textbox' name='{$name}' data-options='{$config['form_ui']}' style='width:{$width}'></input></td>";
                break;
        }
        return $html;
    }
    
}
