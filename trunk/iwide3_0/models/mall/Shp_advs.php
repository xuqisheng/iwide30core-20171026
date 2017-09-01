<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Shp_advs extends MY_Model_Mall {

	public function get_resource_name()
	{
		return 'shp_advs';
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'shp_advs';
	}

	public function table_primary_key()
	{
	    return 'id';
	}

	public function attribute_labels()
	{
	    return array(
	        'id'=> 'Id',
	        'name'=> '名称',
	        'cate'=> '位置',
	        'gs_id'=> '链接产品',
	        'cat_id'=> '链接分类',
	        'link'=> '自定义链接',
	        'hotel_id'=> '酒店ID',
	        'inter_id'=> '公众号',
	        'logo'=> 'LOGO',
	        'sort'=> '排序',
	        'last_edit_time'=> '最后更新时间',
	    );
	}
	
	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
	    return array(
	        'id',
	        'name',
	        'logo',
	        'cat_id',
	        'gs_id',
	        'hotel_id',
	        'inter_id',
	        'sort',
	        'cate',
	        //<list>'last_edit_time',
	    );
	}
	
	const CATE_TOP  =1;
	const CATE_GRID =2;
	const CATE_LIST =3;
	
	public function get_position_array()
	{
	    return array(
	        '1'=> '首页焦点图',
	        '2'=> '中间方格图',
	        '3'=> '底部列表图',
	    );
	}

	/**
	 * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
	 *   type: grid中的表头类型定义 
	 *   form_type: form中的元素类型定义
	 *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
	 *   form_tips: form中的label信息提示
	 *   form_hide: form中自动化输出中剔除
	 *   form_default: form中的默认值，请用字符类型，不要用数字
	 *   select: form中的类型为 combobox时，定义其下来列表
	 */
	public function attribute_ui()
	{
	    /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
	    //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
	    $base_util= EA_base::inst();
	    $modules= config_item('admin_panels')? config_item('admin_panels'): array();

	    /** 获取本管理员的酒店权限  */
	    $hotels_hash= $this->get_hotels_hash();
	    $publics = $hotels_hash['publics'];
	    $hotels = $hotels_hash['hotels'];
	    $filter = $hotels_hash['filter'];
	    $filterH = $hotels_hash['filterH'];
	    /** 获取本管理员的酒店权限  */
	    	  
	    $this->load->model('mall/shp_goods');
	    $products= $this->shp_goods->find_all( $filter );
	    $products= $this->shp_goods->array_to_hash($products, 'gs_name', 'gs_id');

	    $this->load->model('mall/shp_category');
	    if( $this->_admin_inter_id== FULL_ACCESS ) $inter_id= NULL;
	    else $inter_id= $this->_admin_inter_id;
	    if( $this->_admin_hotels== FULL_ACCESS ) $hotel_id= NULL;
	    else $hotel_id= $this->_admin_hotels;
	    $categorys= $this->shp_category->get_cat_tree_option($inter_id, $hotel_id);
	     
	    return array(
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'cate' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $this->get_position_array(),
            ),
            'cat_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '优先级最高',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> array(0=>'不选择')+ $categorys,
            ),
            'gs_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                'form_tips'=> '优先级仅次于分类',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> array(0=>'不选择')+ $products,
            ),
            'link' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                'form_default'=> 'http://',
                'form_tips'=> '优先级次于分类,产品',
                //'form_tips'=> '暂不允许外链，链接不带域名：如“/public/medis/123.jpg”',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',
            ),
            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'type'=>'combobox',
                'select'=> $hotels,
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'combobox',
                'select'=> $publics,
            ),
            'logo' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'grid_function'=> 'show_admin_head|100',
                'type'=>'logo',
            ),
            'sort' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                //'form_ui'=> ' disabled ',
                'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'number',   //textarea|text|combobox
            ),
            'last_edit_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                'type'=>'text',	//textarea|text|combobox
            ),
	    );
	}
	
	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'id', 'sort'=>'desc');
	}
	
	/* 以上为AdminLTE 后台UI输出配置函数 */

	public function get_sdata_filter( $params=array(), $sort='cate asc, sort desc' )
	{
	    $table= $this->table_name();
	    $select= array();
	    $format= 'array';
	    $select= count($select)==0? '*': implode(',', $select);
	    $this->_db()->select(" {$select} ");
	
	    $where= array();
	    $dbfields= array_values($fields= $this->_db()->list_fields($table));
	    foreach ($params as $k=>$v){
	        //过滤非数据库字段，以免产生sql报错
	        if(in_array($k, $dbfields) && is_array($v)){
	            $this->_db()->where_in($k, $v);
	        } else if(in_array($k, $dbfields)) {
	            $this->_db()->where($k, $v);
	        }
	    }
	    $result= $this->_db()->order_by($sort)->get($table);
	    if($format=='object') return $result->result();
	    else return $result->result_array();
	}
	
	public function array_to_hash_multi( $array, $label_keys, $value_key=NULL )
	{
	    $data= array();
	    foreach ($array as $k=>$v) {
	        if( $value_key==NULL ) {
	            $key= $k;
	        } else {
	            $key= $v[$value_key];
	        }
	        $labels= '';
	        $cate_array= self::get_position_array();
	        foreach( explode('|', $label_keys) as $sv){
	            if($sv=='cate') $labels.= '['. $cate_array[$v[$sv]]. '] |';
	            else $labels.=  $v[$sv]. ' |';
	        }
	        $data[$key]= substr($labels, 0, -1);
	    }
	    return $data;
	}

	public function render_base_anchor($adv, $append='')
	{
	    if(count($adv)>0){
	        if($adv['cat_id']){
	            return site_url('mall/wap/plist/'. $adv['cat_id']). $append;
	        } elseif($adv['gs_id']){
	            return site_url('mall/wap/goods_buy/'. $adv['gs_id']). $append;
	        } else {
	            return $adv['link']. $append;
	        }
	    } else {
	        return '';
	    }
	}
	
	/**
	 * @param $category
	 */
	function get_ads_by_category($category, $inter_id = null, $hotel_id = null) {
	    $where = array ('inter_id' => $inter_id);
	    if (! empty ( $hotel_id ))
	        $where['hotel_id'] = $hotel_id;
	    $this->db->where ( $where );
	    if(!empty($category)){
	        if (is_array ( $category )) {
	            $this->db->where_in ( 'cate', $category );
	        } else {
	            $this->db->where ( 'cate', $category );
	        }
	    }
	    return $this->db->get ( 'shp_advs' );
	}
	function update_ad($array,$inter_id,$hotel_id,$id){
	    $this->db->where(array('inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'id'=>$id));
	    return $this->db->update('shp_advs',$array) > 0;
	}
	function del_adv($inter_id,$hotel_id,$id){
	    if (! empty ( $hotel_id ))
	        $this->db->where ( 'hotel_id', $hotel_id );
	    $this->db->where ( array ('inter_id' => $inter_id,'id' => $id ) );
	    return $this->db->delete ( 'shp_advs' ) > 0;
	}
	function create_adv($inter_id,$hotel_id,$cate,$logo,$link){
	    return $this->db->insert('shp_advs',array('inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'cate'=>$cate,'logo'=>$logo,'link'=>$link)) > 0;
	}

}
