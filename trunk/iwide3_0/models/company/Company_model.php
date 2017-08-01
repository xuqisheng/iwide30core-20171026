<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Company_model extends MY_Model {
    public function get_resource_name() {
        return '协议价';
    }
    public static function model($className = __CLASS__) {
        return parent::model ( $className );
    }


    /**
     * @return string the associated database table name
     */
    public function table_name() {
        return 'company_price';
    }
    public function table_primary_key() {
        return 'cp_id';
    }
    public function attribute_labels() {
        return array (
            'cp_id'=>'协议价编号',
            'hotel_id' => '所属酒店',
            'cp_code' => '协议代码',
            'price_code' => '价格代码',
            'valid_time' => '有效期',
            'company_id' => '公司名称',
            'status'=>'状态',
            'inter_id'=>'公众号',
//            'staff_id'=>'ID',
        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields() {
        return array (
            'cp_id',
            'company_id',
            'hotel_id',
            'cp_code',
            'price_code',
            'valid_time',
//            'status',
//            'inter_id',

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
    public function attribute_ui() {
        /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
        // type: numberbox数字框|combobox下拉框|text不写时默认|datebox
        $base_util = EA_base::inst ();
        $modules = config_item ( 'admin_panels' ) ? config_item ( 'admin_panels' ) : array ();
        // $parents= $this->get_cat_tree_option();

        $parents ['0'] = '一级分类';

        $inter_id = $this->session->get_admin_inter_id ();

        $db_read = $this->load->database('iwide_r1',true);

        $status = array (
            '1' => '可用',
            '2' => '不可用'
        );
        $star = array (
            '0'=>'无',
            '1' => '一星级',
            '2' => '二星级',
            '3' => '三星级',
            '4' => '四星级',
            '5' => '五星级',
            '6' => '六星级',
            '7' => '七星级',
            '8' => '八星级',
            '9' => '九星级',
        );

        /** 获取本管理员的酒店权限  */
        $this->_init_admin_hotels ();
        $publics = $hotels = array ();
        $filter = $filterH = NULL;

        if ($this->_admin_inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($this->_admin_inter_id)
            $filter = array (
                'inter_id' => $this->_admin_inter_id
            );
        if (is_array ( $filter )) {
            $this->load->model ( 'wx/publics_model' );
            $publics = $this->publics_model->get_public_hash ( $filter );
            $publics = $this->publics_model->array_to_hash ( $publics, 'name', 'inter_id' );
            // $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
        }

        if ($this->_admin_hotels == FULL_ACCESS)
            $filterH = array ();
        else if ($this->_admin_hotels)
            $filterH = array (
                'hotel_id' => $this->_admin_hotels
            );
        else
            $filterH = array ();
        if ($publics && is_array ( $filterH )) {
            $this->load->model ( 'company/Company_dispose_model' );
            $hotels = $this->Company_dispose_model->get_hotel_hash ( $filterH );
            $hotels_list=$hotels;
            $hotels = $this->Company_dispose_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
//            $hotels = $hotels + array (
//                    '0' => '-不限定-'
//                );

          //获取公众号内的公司列表

        }
        /** 获取本管理员的酒店权限  */

        $db_read->where ( array (
            'inter_id' => $inter_id
        ));

        $companies_list = $db_read->get ( 'company_list' )->result ();


        $db_read->where ( array (
            'type' => 'protrol'
        ));

        $price_list = $db_read->get ( 'hotel_price_info' )->result ();



        $hotels_list=$this->Company_dispose_model->get_hotel_list ( $inter_id );

        $hotels_name_list =array();

        foreach($hotels_list as $arr){

            $hotels_name_list[$arr['hotel_id']]=$arr['name'];

        }

        $companies_name_list =array();

        foreach($companies_list as $arr){

            $companies_name_list[$arr->company_id]=$arr->company_name;

        }


        $price_code_list =array();

        foreach($price_list as $arr){

            $price_code_list[$arr->price_code]=$arr->price_name;

        }



        return array (

            'cp_id' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'text',
            ) // textarea|text|combobox
        ,
//            'cp_name' => array (
//                'grid_ui' => '',
//                'grid_width' => '10%',
//                'type' => 'text',
//                'form_hide'=> TRUE,
//            ) // textarea|text|combobox
//        ,
            'cp_code' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'text',
            ) // textarea|text|combobox
        ,
            'price_code' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'combobox',
                'select' => $price_code_list,
            ) // textarea|text|combobox
        ,
            'valid_time' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'text'
            ) // textarea|text|combobox
        ,

            'company_id' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'combobox',
                'select' => $companies_name_list,
//                'form_ui'=>'disabled'
            ) // textarea|text|combobox
        ,
            'hotel_id' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'combobox',
                'select' => $hotels_name_list,
            ),
            'status' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'combobox',
                'select' => $status
            ),
            'inter_id' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'text',
                'form_hide'=>true,
            ),

            'status' => array (
                'grid_ui' => '',
                'grid_width' => '10%',
                'type' => 'combobox',
                'select' => $status,
            ),

        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field() {
        return array (
            'field' => 'cp_id',
            'sort' => 'desc'
        );
    }

    /* 以上为AdminLTE 后台UI输出配置函数 */
    public function get_cat_tree_option() {
        $array = '';
        // $array['_'. $k]= '+'. $v['label'];
        $tmp = $this->get_data_filter ( array (
            'parent_id' => '0'
        ) );
        // print_r($tmp);die;
        foreach ( $tmp as $sv ) {
            $array [$sv ['cat_id']] = '+' . $sv ['cat_name'];
            $tmp2 = $this->get_data_filter ( array (
                'parent_id' => $sv ['cat_id']
            ) );
            // print_r($array);die;
            foreach ( $tmp2 as $ssv ) {
                $array [$ssv ['cat_id']] = '+---' . $ssv ['cat_name'];
            }
        }
        // print_r($array);die;
        return $array;
    }
//    function get_focus_s(){
//        $this->_init_admin_hotels ();
//        $publics = $hotels = array ();
//        $filter = $filterH = NULL;
//        $inter_id = $this->_admin_inter_id;
//        if ($inter_id == FULL_ACCESS)
//            $filter = array ();
//        else if ($inter_id)
//            $filter = array ('inter_id' => $inter_id );
//        if (is_array ( $filter )) {
//            $this->load->model ( 'wx/publics_model' );
//            $publics = $this->publics_model->get_public_hash ( $filter );
//            $publics = $this->publics_model->array_to_hash ( $publics, 'cp_name', 'cp_id' );
//            // $publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
//        }
//
//        if ($this->_admin_hotels == FULL_ACCESS)
//            $filterH = array ();
//        else if ($this->_admin_hotels)
//            $filterH = array ('cp_id' => $this->_admin_hotels );
//        else
//            $filterH = array ();
//        $filterH ['status'] = array(1,2);
//        if(!isset($filterH['inter_id']))$filterH['inter_id'] = $this->session->get_admin_inter_id();
//        if ($publics && is_array ( $filterH )) {
//            $this->load->model ( 'company/Company_model' );
//            $hotels = $this->hotel_model->get_hotel_hash ( $filterH );
//            $hotels = $this->hotel_model->array_to_hash ( $hotels, 'cp_name', 'cp_id' );
//        }
//        $hotel_id = 0;
//        $keys = array_keys( $hotels);
//        if($this->input->get('hid')){
//            if(key_exists($this->input->get('hid'), $hotels))
//                $hotel_id = $this->input->get('hid');
//        }else{
//            $hotel_id = empty($keys[0])?0:$keys[0];
//        }
//        if ($inter_id == FULL_ACCESS) $inter_id = 'a429262687';
//        $this->db->where(array('inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'type'=>'hotel_lightbox','status'=>1));
//        $focus_query = $this->db->get('hotel_images')->result();
//        return array('hotels'=>$hotels,'focus'=>$focus_query,'hotel_id'=>$hotel_id,'inter_id'=>$inter_id);
//    }
    function save_focus(){
        $datas['image_url']  = trim($this->input->post('imgurl'));
        $datas['info']       = trim($this->input->post('imgalt'));
        $datas['sort']       = $this->input->post('sort');
        if(empty($this->input->post('key'))){
            $datas['inter_id']   = $this->input->post('inter_id');
            $datas['hotel_id']   = $this->input->post('hotel_id');
            $datas['status']     = 1;
            $datas['type']       = 'hotel_lightbox';
            return $this->db->insert('hotel_images',$datas) > 0;
        }else{
            $this->db->where(array('inter_id'=>$this->input->post('inter_id'),'hotel_id'=>$this->input->post('hotel_id'),'id'=>$this->input->post('key')));
            return $this->db->update('hotel_images',$datas) > 0;
        }
    }
    function del_focus(){
        $this->db->where(array('hotel_id'=>$this->input->get('hotel_id'),'inter_id'=>$this->input->get('inter_id'),'id'=>$this->input->get('key')));
        return $this->db->delete('hotel_images') > 0;
    }
//    function save_services($hotel_id = NULL){
//        $inter_id=$this->session->get_admin_inter_id();
//        if($this->input->post('hotel_id'))$hotel_id=$this->input->post('hotel_id');
//        $ser_kv = array('&#xe7;'=> '停车','&#xed;'=>'接机服务','&#xea'=>'餐厅','&#xe3;'=>'上网','&#xe5;'=>'叫醒服务','&#xe9;'=>'行李寄存','&#xe4;'=>'吹风机','&#xe8;'=>'Wifi','&#xeb;'=>'热水');
//        $services = $this->input->post('ser[]');
//        $this->db->where(array('inter_id'=>$inter_id,'hotel_id'=>$hotel_id,'room_id'=>0,'type'=>'hotel_service'));
//        $this->db->delete('hotel_images');
//        foreach ($services as $item) {
//            $sql = 'INSERT INTO '.$this->db->dbprefix('hotel_images')." (inter_id,hotel_id,room_id,sort,type,info,image_url,status) select ?,?,?,0,'hotel_service',info,image_url,1 FROM ".$this->db->dbprefix('hotel_images')." WHERE inter_id='defaultimg' AND type='hotel_service' AND image_url=?";
//            $this->db->query($sql,array($inter_id,$hotel_id,0,htmlspecialchars_decode($item)));
//        }
//    }
    public function load($id)
    {
        $pk= $this->table_primary_key();
        $values= $this->find(array($pk=> $id,'inter_id'=>$this->session->get_admin_inter_id()));
        if($values){
            $table= $this->table_name();
            $fields= $this->_db()->list_fields($table);
            $this->_attribute= array_values($fields);

            foreach ($fields as $v) {
                $this->_data[$v]= $values[$v];
            }
            //确保 $this->_data_org 的值是完整的
            $this->_data_org = $this->_data;
            return $this;

        } else {
            return NULL;
        }
    }
    public function m_save($data=NULL,$update = TRUE)
    {
        $pk= $this->table_primary_key();
        $table= $this->table_name();
        $fields= $this->_db()->list_fields($table);
        //手工生成主键字段，update=FALSE -- 2015-12-07 ounianfeng
        // 	    if( isset($this->_data[$pk]) && $this->_data[$pk]>0 ) {
        if(!isset($this->_data['inter_id']))$this->_data['inter_id'] = $this->session->get_admin_inter_id();
        if( isset($this->_data[$pk]) && !empty($this->_data[$pk]) && $update ) {
            if($data){
                foreach ($data as $k=>$v){
                    if(in_array($k,$fields)) $this->_data[$k]= $v;
                }
            }
            $where= array( $pk=> $this->_data[$pk] ,'inter_id'=>$this->session->get_admin_inter_id());
            $this->_db()->where($where);
            $result= $this->_db()->update($table, $this->_data);
            return $result;

        } else {
            if($data){
                foreach ($data as $k=>$v){
                    if(in_array($k,$fields)) $this->_data[$k]= $v;
                }
            }
            //手工生成主键字段时，不释放主键的变量 -- 2015-12-07 ounianfeng --
            if($update)unset($this->_data[$pk]);
            $result= $this->_db()->insert($table, $this->_data);
            //成功插入后返回last insert id

            if($result==TRUE){
                return $this->_db()->insert_id();
            } else {
                return $result;
            }
        }
    }



    public function checkCompanyCp($data){

        $db_read = $this->load->database('iwide_r1',true);

        if(!empty($data{'cp_id'})){

            $condition = "AND cp_id !={$data{'cp_id'}}";

        }else{

            $condition='';
        }

        $check = $db_read->query("SELECT
                                    * FROM
                                         `iwide_company_price`
                                    WHERE
                                        company_id={$data['company_id']}
                                    AND
                                        inter_id = '{$data['inter_id']}'
                                    AND
                                         hotel_id='{$data['hotel_id']}'$condition")->row_array();


        return $check;

    }

}


?>
