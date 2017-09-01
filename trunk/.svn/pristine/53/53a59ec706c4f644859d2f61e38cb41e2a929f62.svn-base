<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model_Mall extends MY_Model {
    
    public function virtual_field()
    {
        return array();
    }

    public function get_field_config($type='grid')
    {
        $data= array();
        if($type=='grid'){
            $show= $this->grid_fields();
            //grid多选状态必须有主键
            array_unshift( $show, $this->table_primary_key() );
            
        } else {
            //有时需要取数据库以外的字段，如 密码确认字段，在模板手动添加
            $show= $this->_db()->list_fields($this->table_name());
        }

$virtual_field= $this->virtual_field();
$show= array_merge($show, $virtual_field);

        $fields= $this->attribute_labels();

        $fields_ui= $this->attribute_ui();
        foreach ($show as $v){
if( !isset($fields[$v]) || !isset($fields_ui[$v])  ) continue;

            $data[$v]['label']= $fields[$v];
            
            if($type=='grid'){
                //grid所需配置信息
                if( array_key_exists($v, $fields_ui) ){
                    $data[$v]['grid_ui'] = isset($fields_ui[$v]['grid_ui'])?$fields_ui[$v]['grid_ui']: '';
                    $data[$v]['grid_width'] = isset($fields_ui[$v]['grid_width'])?$fields_ui[$v]['grid_width']: "";
                    $data[$v]['grid_function'] = isset($fields_ui[$v]['grid_function'])? $fields_ui[$v]['grid_function']: FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type'])?$fields_ui[$v]['type']: 'text';
                    if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                }
                
            } else if($type=='form') {
                //form所需配置信息
                $data[$v]['js_config'] = isset($fields_ui[$v]['js_config'])? $fields_ui[$v]['js_config']: '';
                $data[$v]['input_unit'] = isset($fields_ui[$v]['input_unit'])? "<div class='input-group-addon'>{$fields_ui[$v]['input_unit']}</div>" : '';
                $data[$v]['form_ui'] = isset($fields_ui[$v]['form_ui'])? $fields_ui[$v]['form_ui']: '';
                $data[$v]['form_tips'] = !empty($fields_ui[$v]['form_tips'])? $fields_ui[$v]['form_tips']: NULL;
                $data[$v]['form_default'] = isset($fields_ui[$v]['form_default'])? $fields_ui[$v]['form_default']: NULL;
                $data[$v]['form_hide'] = isset($fields_ui[$v]['form_hide'])? $fields_ui[$v]['form_hide']: FALSE;
                $data[$v]['function'] = isset($fields_ui[$v]['function'])? $fields_ui[$v]['function']: FALSE;
                $data[$v]['type'] = isset($fields_ui[$v]['type'])? $fields_ui[$v]['type']: 'text';
                if( $data[$v]['type']=='combobox' ) $data[$v]['select'] = $fields_ui[$v]['select'];
                if( isset($fields_ui[$v]['form_type'])) $data[$v]['type'] = $fields_ui[$v]['form_type'];
            }
        }
        return $data;
    }

    public function get_hotels_hash()
    {
        $this->_init_admin_hotels();
        $publics = $hotels= array();
        $filter= $filterH= NULL;
         
        if( $this->_admin_inter_id== FULL_ACCESS ) $filter= array();
        else if( $this->_admin_inter_id ) $filter= array('inter_id'=> $this->_admin_inter_id);
        if(is_array($filter)){
            $this->load->model('wx/publics_model');
            $publics= $this->publics_model->get_public_hash($filter);
            $publics= $this->publics_model->array_to_hash($publics, 'name', 'inter_id');
            //$publics= $publics+ array(FULL_ACCESS=>'-所有公众号-');
        }

        if( $this->_admin_hotels== FULL_ACCESS ) $filterH= array();
        else if( is_array($this->_admin_hotels) && count($this->_admin_hotels)>0 ) $filterH= array(
            'inter_id'=> $this->_admin_inter_id, 'hotel_id'=> $this->_admin_hotels);
        else $filterH= array( 'inter_id'=> $this->_admin_inter_id );
         
        if( $publics && is_array($filterH)){
            $this->load->model('hotel/hotel_model');
            $hotels= $this->hotel_model->get_hotel_hash($filterH);
            $hotels= $this->hotel_model->array_to_hash($hotels, 'name', 'hotel_id');
            //$hotels= $hotels+ array('0'=>'-不限定-');
        }
        return array('filter'=> $filter, 'filterH'=> $filterH, 'publics'=> $publics, 'hotels'=>$hotels );
    }
    
}
