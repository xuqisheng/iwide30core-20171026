<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
*	后台优惠券
*	@author Frandon
*	@time 四月十一号
*	@version www.iwide.cn
*	@
*/
class Memberexport extends MY_Admin
{
    //导出会员资料
    public function member(){
        ini_set('memory_limit','512M');
        $inter_id = $this->session->get_admin_inter_id();
        if(!empty($this->input->get('inter_id'))){
            $inter_id = $this->input->get('inter_id');
        }
        $keys = $this->uri->segment(4);
        $begin_time = $this->input->post('begin_time');
        $end_time   = $this->input->post('end_time');
        $keys = explode('_', $keys);
        if(!empty($keys[0])){
            $begin_time = $keys[0];
        }
        if(!empty($keys[1])){
            $end_time = $keys[1];
        }

        if(!empty($begin_time)){
            $begin_time = strtotime($begin_time);
        }

        if(!empty($end_time)){
            $end_time = strtotime($end_time);
        }

        if(empty($begin_time) && empty($end_time)){
            $begin_time = 0;
            $end_time   = 0;
        }
        $this->load->model('membervip/admin/report_member_model','rem');
        $params['inter_id'] = $inter_id;
        $res = $this->rem->get_member_info($params,$begin_time,$end_time);
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, 1, '会员ID' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 1, 1, '会员昵称' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2, 1, '会员类型' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, 1, '会员名称' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 4, 1, '会员卡号' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 5, 1, '手机号码' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 6, 1, '会员等级' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 7, 1, '会员积分' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 8, 1, '储值余额' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 9, 1, '有效卡券总数' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 10, 1, '是否冻结' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 11, 1, '是否登录' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 12, 1, '注册时间' );


        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        // Fetching the table data
        $row = 2;
        foreach ( $res as $item ) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, $row, isset($item['member_info_id']) ? $item['member_info_id'] : '------' );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 1, $row, isset($item['nickname']) ? $item['nickname'] : '------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2, $row, isset($item['member_mode']) ? $item['member_mode'] : '------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, $row, !empty($item['name'])?$item['name']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 4, $row, !empty($item['membership_number'])?$item['membership_number']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 5, $row, !empty($item['telephone'])?$item['telephone']:(!empty($item['cellphone'])?$item['cellphone']:'------'));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 6, $row, !empty($item['lvl_name'])?$item['lvl_name']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 7, $row, !empty($item['credit'])?$item['credit']:'0');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 8, $row, !empty($item['balance'])?$item['balance']:'0');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 9, $row, !empty($item['member_card_count'])?$item['member_card_count']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 10, $row, !empty($item['is_active'])?$item['is_active']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 11, $row, !empty($item['is_login'])?$item['is_login']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 12, $row, !empty($item['createtime'])?date('Y-m-d H:i:s',$item['createtime']):'------');
            $row ++;
        }
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        // 发送标题强制用户下载文件
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="会员资料明细' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }

    public function package_excel(){
        ini_set('memory_limit','512M');
        $inter_id = $this->session->get_admin_inter_id();
        if(!empty($this->input->get('inter_id'))){
            $inter_id = $this->input->get('inter_id');
        }
        $keys = $this->uri->segment(4);
        $begin_time = $this->input->get('bt');
        $end_time   = $this->input->get('et');
        $package_id = $this->input->get('pid');
        if(empty($package_id)){
            $this->session->put_error_msg('礼包ID不存在');
            $this->_redirect(EA_const_url::inst()->get_url('*/memberpackage/index'));
        }
        $keys = explode('_', $keys);
        if(!empty($keys[0])){
            $begin_time = $keys[0];
        }
        if(!empty($keys[1])){
            $end_time = $keys[1];
        }

        if(!empty($begin_time)){
            $begin_time = strtotime(date('Y-m-d 00:00:00',strtotime($begin_time)));
        }

        if(!empty($end_time)){
            $end_time = strtotime(date('Y-m-d 23:59:59',strtotime($end_time)));
        }

        if(empty($begin_time) && empty($end_time)){
            $begin_time = 0;
            $end_time   = 0;
        }
        $this->load->model('membervip/admin/report_member_model','rem');
        $params['package_id'] = $package_id;
        $params['inter_id'] = $inter_id;
        $params['select'] = 'COUNT(mp.member_package_id) as count,mp.member_package_id,mp.inter_id,mp.package_id,mp.createtime,m.member_info_id,m.name,m.membership_number';
        $result = $this->rem->get_package_mixed($params,$begin_time,$end_time);
        if(empty($result)){
            $this->session->put_error_msg('该领取时间内无领取记录，请调整领取时间再次导出！');
            $this->_redirect(EA_const_url::inst()->get_url('*/memberpackage/index'));
        }
        if(empty($result)) return false;
        $list = isset($result['list'])?$result['list']:array();
        $package = isset($result['package'])?$result['package']:array();
        $pname = isset($result['pname'])?$result['pname']:'';
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties()->setTitle ( "export" )->setDescription ( "none" );
        $col = 0;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'礼包名称');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,$pname); //礼包名称

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, 2, '会员卡号');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 1, 2, '获得礼包数量');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 2, 2, '会员名称');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 3, 2, '获得积分');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 4, 2, '获得储值');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 5, 2, '获得会员等级');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 6, 2, '领取时间');

        $rows = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        foreach ($rows as $vo){
            $objPHPExcel->getActiveSheet()->getStyle($vo.'1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle($vo.'2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension($vo)->setWidth(20);
        }
        // Fetching the table data
        $row = 3;
        foreach ( $list as $item ) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,!empty($item['membership_number']) ? $item['member_info_id'] : '------' );
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,!empty($item['count'])?$item['count']:0);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,!empty($item['name'])?$item['name']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,!empty($package['credit'])?$package['credit']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,!empty($package['balance'])?$package['balance']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,!empty($package['lvl_name'])?$package['lvl_name']:'------');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,!empty($item['createtime'])?date('Y-m-d H:i:s',$item['createtime']):'------');

            $row2=7;
            if(isset($package['card']) && !empty($package['card'])){
                foreach ($package['card'] as $card){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($row2,2,!empty($card['title'])?$card['title']:'------');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($row2,$row,!empty($card['count'])?$card['count']:'------');
                    $row2++;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($row2,2,'是否转赠');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($row2,$row,$card['can_give_friend']=='t'?'是':'否');

                    if($card['use_time_end_model']=='y'){
                        $end_time = time() + (( 3600*24 ) * $card['use_time_end_day']);
                        $use_time_end = strtotime(date('Y-m-d 23:59:59',$end_time));
                    }elseif ($card['use_time_end_model']=='g') {
                        $use_time_end = strtotime(date('Y-m-d 23:59:59',$card['use_time_end']));
                    }
                    $row2++;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($row2,2,'是否过期');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($row2,$row,$use_time_end>time()?'是':'否');
                    $row2++;
                }
            }
            $row ++;
        }
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        // 发送标题强制用户下载文件
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="大礼包领取明细' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }

    //会员数据导出(礼包、优惠券相关数据)
    public function export(){
        ini_set('memory_limit',-1); //无内存限制
        set_time_limit(0); //无时间限制
        $package_id = $this->input->get('pgid');
        $type = $this->input->get('tp');
        if(empty($type)) $type = 1;
        $inter_id = $this->session->get_admin_inter_id();
        if($this->input->get('inter_id')) $inter_id = $this->input->get('inter_id');
        $begin_time = $this->input->get('bt');
        $end_time = $this->input->get('et');

        if(!empty($begin_time)){
            $begin_time = strtotime(date('Y-m-d 00:00:00',strtotime($begin_time)));
        }

        if(!empty($end_time)){
            $end_time = strtotime(date('Y-m-d 23:59:59',strtotime($end_time)));
        }

        if(empty($begin_time) && empty($end_time)){
            $begin_time = 0;
            $end_time   = 0;
        }

        $this->load->model('membervip/admin/report_member_model','rem');
        $params['inter_id'] = $inter_id;

        $results = array();
        switch ($type){
            case '1':
                if(!empty($this->input->get('cid'))){
                    $params['card_id']=$this->input->get('cid');
                }
                $params['begin_time'] = $begin_time;
                $params['end_time'] = $end_time;
                $time_mode = $this->input->get('mode');
                $params['time_mode'] = $time_mode;
                $result = $this->rem->get_membercard_v($params);
                if(empty($result)){
                    $this->session->put_error_msg('该时间段无领取记录，请调整领取时间再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/membercard/card_user_info/'.$this->input->get('cid')));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '优惠券领取统计';
                break;
            case '2':
                $params['package_id'] = $package_id;
                $result = $this->rem->get_member_paceage_card($params,$begin_time,$end_time);
                if(empty($result)){
                    $this->session->put_error_msg('该时间段无领取记录，请调整领取时间再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/memberpackage/index'));
                }
                break;
            case '3':
                $result = $this->rem->get_member_info($params,$begin_time,$end_time);
                if(empty($result)){
                    $this->session->put_error_msg('该时间段无会员记录，请调整时间再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/membermanage/index'));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '会员信息';
                break;
            case '4':
                $btime = $this->input->get('bt');
                if(!empty($begin_time)){
                    $begin_time = strtotime(date('Y-m-01 00:00:00',strtotime($btime)));
                    $end_time = strtotime(date('Y-m-t 23:59:59',strtotime($btime)));
                }else{
                    $begin_time = 0;
                    $end_time = 0;
                }

                $result = $this->rem->get_invited_record($params,$begin_time,$end_time);
                if(empty($result)){
                    $this->session->put_error_msg('该时间段无被邀请记录，请调整时间再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/admininvited/statistics'));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '被邀会员报表';
                break;
            case '5':
                $result = $this->rem->buycard_record($params,$begin_time,$end_time);
                if(empty($result)){
                    $this->session->put_error_msg('该时间段无购卡记录，请调整时间再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/buycardrecord'));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '购卡记录';
                break;
            case '6':
                $result = $this->rem->get_credit_record($params,$begin_time,$end_time);
                if(empty($result)){
                    $this->session->put_error_msg('该时间段无积分记录，请调整时间再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/membercredit'));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '积分记录';
                break;
            case '7':
                $result = $this->rem->get_deposit_record($params,$begin_time,$end_time);
                if(empty($result)){
                    $this->session->put_error_msg('该时间段无储值记录，请调整时间再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/memberdeposit'));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '储值记录';
                break;
            case '8':
                $gets = $this->input->get();
                $params['_string'] = "cl.inter_id = '{$inter_id}' AND (mc.is_use = 't' OR mc.is_useoff = 't' OR mc.is_active = 'f') AND mc.is_giving = 'f'";
                if(!empty($gets['keywords'])){
                    $params['_string'] .= " AND (c.title = '{$gets['keywords']}' OR mc.card_id = '{$gets['keywords']}')";
                }

                if(!empty($gets['useoff_sttime'])){
                    $useoff_sttime = strtotime(date('Y-m-d',strtotime($gets['useoff_sttime'])));
                    $params['_string'] .= " AND mc.useoff_time >= '{$useoff_sttime}'";
                }

                if(!empty($gets['useoff_edtime'])){
                    $useoff_edtime = strtotime(date('Y-m-d 23:59:59',strtotime($gets['useoff_edtime'])));
                    $params['_string'] .= " AND mc.useoff_time <= '{$useoff_edtime}'";
                }

                if(!empty($gets['coupon_code'])){
                    $params['_string'] .= " AND mc.coupon_code = '{$gets['coupon_code']}'";
                }

                if(!empty($gets['status'])){
                    switch ($gets['status']){
                        case 1:
                            $params['_string'] .= " AND mc.is_use = 't' AND mc.is_useoff = 'f' AND mc.is_active = 't'";
                            break;
                        case 2:
                            $params['_string'] .= " AND mc.is_use = 't' AND mc.is_useoff = 't' AND mc.is_active = 't'";
                            break;
                        case 3:
                            $params['_string'] .= " AND mc.is_active = 'f'";
                            break;
                    }
                }

                $result = $this->rem->get_useoff_record($params);
                if(empty($result)){
                    $this->session->put_error_msg('你录入的信息无法找到对应的优惠券使用数据，请调整查询条件再次导出！');
                    $this->_redirect(EA_const_url::inst()->get_url('*/membercard/uselist'));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '优惠券使用数据';
                break;
            case '9':
                $params = $this->input->get();
                $result = $this->rem->get_task_item($params);
                if(empty($result)){
                    $id = !empty($params['id'])?$params['id']:0;
                    $this->session->put_error_msg('无法找到对应的优惠批量发送数据！');
                    $this->_redirect(EA_const_url::inst()->get_url('membervip/membertask/item',array('Id'=>$id)));
                }
                $results['s1'] = $result;
                $result = $results;
                $result['s1_name'] = '优惠批量发送数据';
                break;
            default:break;
        }

        $this->load->model('membervip/admin/config/report_fields_model','rfm');
        $fields_conf = $this->rfm->get_fields_conf($type,$results);
        if(empty($fields_conf)) return false;

        $filename = !empty($this->input->get('fn'))?$this->input->get('fn'):$result['s1_name'];
        if(count($fields_conf)==1){ //导出CSV
            
            if ($type=='3'){
                $admin_profile = $this->session->userdata('admin_profile');  
                $end_time = strtotime(date('Y-m-d 23:59:59',strtotime($this->input->get('et'))));
                $data = $this->rem->export_csv($result,$fields_conf,'member',$admin_profile['inter_id'],$begin_time,$end_time);
            }else {
                $data = $this->rem->export_csv($result,$fields_conf);
            }

            // 导出
            $filename = "{$filename}".date ('YmdHis').".csv";
            header('Content-Type: text/csv');
            header("Content-Type:text/html; charset=utf-8");
            header("Content-Disposition: attachment;filename={$filename}");
            $fp = fopen('php://output', 'w');
            fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            if(!empty($data)){
                foreach ($data as &$row){
                    foreach ($row as &$v){
                        if(is_numeric($v) && strlen($v) > 15){
                            $v = "'{$v}'";
                        }
                    }
                    fputcsv($fp, $row);
                }
            }
            fclose($fp);

        }elseif (count($fields_conf)>1){ //导出Excel
            $objPHPExcel = $this->rem->export_excel($result,$fields_conf);
            $objPHPExcel->setActiveSheetIndex (0);
            $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
            // 发送标题强制用户下载文件
            header ( 'Content-Type: application/vnd.ms-excel' );
            header ( 'Content-Disposition: attachment;filename="'.$filename . date ( 'YmdHis' ) . '.xls"' );
            header ( 'Cache-Control: max-age=0' );
            $objWriter->save ( 'php://output' );
        }
    }

    //导出邀请好友分析报表
    public function export_invite(){
        ini_set('memory_limit','512M');
        $inter_id = $this->session->get_admin_inter_id();
        if(!empty($this->input->get('inter_id'))){
            $inter_id = $this->input->get('inter_id');
        }

        $this->load->model('wx/Publics_model');
        $public = $this->Publics_model->get_public_by_id($inter_id);

        if(empty($public)){
            $this->session->put_error_msg('找不到'.$inter_id.'下的微信公众号信息！');
            $this->_redirect(EA_const_url::inst()->get_url('*/admininvited/statistics'));
        }

        $this->load->model('membervip/admin/report_member_model','rem');
        $this->load->model('membervip/admin/Public_model','pum');
        $member_lvl = $this->pum->get_field_by_level_config($inter_id,'member_lvl_id,lvl_name,lvl_up_sort');
        if(empty($member_lvl)){
            $this->session->put_error_msg('找不到'.$inter_id.'下的等级配置！');
            $this->_redirect(EA_const_url::inst()->get_url('*/admininvited/statistics'));
        }

        $params['inter_id'] = $inter_id;
        $params['member_lvl'] = $member_lvl;
        $result = $this->rem->get_invited_info($params);
        if(is_string($result)) {
            $this->session->put_error_msg($result);
            $this->_redirect(EA_const_url::inst()->get_url('*/admininvited/statistics'));
        }
        $this->load->library ( 'PHPExcel' );
        $this->load->library ( 'PHPExcel/IOFactory' );
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties()->setTitle ( "export" )->setDescription ( "none" );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, 1, $public['name'].'邀请好友分析报表' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2,'');

        $month = intval(date('m')); //统计到当月
        for ($i=1;$i<=$month;$i++){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 2,$i.'月');
        }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, 2,'合计');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, 3, '激活邀请资格人数' );
        $lvls = $result['invited_lvls'];
        $ik = 4;
        foreach ($lvls as $kid){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, $ik, '已邀请'.$member_lvl[$kid]);
            $ik++;
        }

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, $ik, '邀请会员总数');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow( 0, $ik+1, '邀请会员环比增长率');

        $t = $ik+1;
        for ($i=1;$i<=$t;$i++){
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        //每月邀请的等级统计
        $row = 4;
        foreach ($result['invited_lvl_count'] as $item){
            $vol_t = 0;
            for ($i=1;$i<=$month;$i++){
                $vol = !empty($item[$i])?$item[$i]:0;
                $vol_t = $vol_t + $vol;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $vol);
            }
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $vol_t); //合计
            $row++;
        }


        //每月邀请的总数
        $vol_t = 0;
        for ($i=1;$i<=$month;$i++){
            $vol = !empty($result['invited_totals'][$i])?$result['invited_totals'][$i]:0;
            $vol_t = $vol_t + $vol;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $vol);
        }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $vol_t); //合计
        $row++;

        //邀请新会员环比增长率
        for ($i=1;$i<=$month;$i++){
            $vol = !empty($result['ring_ratio'][$i])?$result['ring_ratio'][$i]:0;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $row, $vol);
        }

        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objWriter = IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
        // 发送标题强制用户下载文件
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="邀请好友分析报表' . date ( 'YmdHis' ) . '.xls"' );
        header ( 'Cache-Control: max-age=0' );
        $objWriter->save ( 'php://output' );
    }
}
?>