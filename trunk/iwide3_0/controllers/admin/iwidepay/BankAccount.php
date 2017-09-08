<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class BankAccount
 * 银行账户
 * 沙沙
 * 2017-06-27
 */

class BankAccount extends MY_Admin
{
    protected $label_module = '列表';
    protected $label_controller = '列表';
    protected $label_action = '列表';
    public $username = '';

    public function __construct()
    {
        parent::__construct();
        $this->admin_profile = $this->session->userdata('admin_profile');
        $this->load->helper('appointment');
    }


    /**
     * 首页管理列表
     *
     */
    public function index()
    {
        $param = request();
        $return = array(
            'param'      => $param,
        );

        echo $this->_render_content($this->_load_view_file('index'), $return, TRUE);
    }

    /**
     * 校验 转账账号
     */
    public function check_bank_account()
    {
        $param = request();
        $return = array(
            'param' => $param,
        );
        echo $this->_render_content($this->_load_view_file('check'), $return, TRUE);
    }

    /**
     * 导出校验数据
     */
    public function ext_check_data()
    {
        $param = request();
        $filter['inter_id'] = !empty($param['inter_id']) ? addslashes($param['inter_id']) : '';
        $filter['hotel_id'] = !empty($param['hotel_id']) ? intval($param['hotel_id']) : '';
        $filter['status'] = !empty($param['status']) ? intval($param['status']) : '';
        $filter['start_time'] = !empty($param['start_time']) ? addslashes($param['start_time']) : '';
        $filter['end_time'] = !empty($param['end_time']) ? addslashes($param['end_time']) : '';

        $inter_id = $this->admin_profile['inter_id'];

        //获取数据
        $this->load->model('iwidepay/iwidepay_merchant_model' );

        if (empty($filter['inter_id']))
        {
            $filter['inter_id'] = $inter_id;
        }

        if (empty($filter['hotel_id']))
        {
            $filter['hotel_id'] = $this->admin_profile['entity_id'];
        }

        $status = array(''=>'待验证',1=>'验证成功',2=>'验证失败',3=>'验证异常');

        $select = 'mi.id,mi.inter_id,mi.hotel_id,mi.type,mi.account_aliases,mi.is_company,mi.branch_id,mi.bank,mi.bank_card_no,mi.type,mi.bank_user_name,mi.created_at';
        $list = $this->iwidepay_merchant_model->get_check_accounts($select,$filter,'','');
        if ($list)
        {
            foreach ($list as $key => $value)
            {
                $item['created_at'] = !empty($value['created_at']) ? $value['created_at'] : '--';
                if ($value['type'] == 'jfk')
                {
                    $value['name'] = $value['hotel_name'] = $value['account_aliases'] = '金房卡';
                }
                else if($value['type'] == 'group')
                {
                    $value['hotel_name'] = '集团';
                }
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';
                $item['amount'] = formatMoney($value['amount']/100);
                $item['add_time'] = !empty($value['add_time']) ? $value['add_time'] : '--';
                $item['status_name'] = $status[$value['status']];
                $item['remark'] = !empty($value['remark']) ? $value['remark'] : '--';
                $list[$key] = $item;
            }
        }

        $headArr = array('新增时间','所属公众号','所属门店','验证金额','验证时间','验证状态','备注');
        $widthArr = array(20,25,25,20,20,20,20);
        getExcel('账户验证',$headArr,$list,$widthArr);

    }


    /**
     * 编辑
     */
    public function edit()
    {
        $param = request();
        $return = array(
            'param' => $param,
        );

        echo $this->_render_content($this->_load_view_file('edit'), $return, TRUE);
    }

    /**
     * 导出账户
     */
    public function ext_data()
    {
        $param = request();
        $type = !empty($param['type']) ? intval($param['type']) : 0;
        $keyword = !empty($param['keyword']) ? addslashes(trim($param['keyword'])) : '';

        $inter_id = $this->admin_profile['inter_id'];

        $filter = array(
            'is_company' => $type,
            'inter_id' => $inter_id,
            'hotel_id' => $this->admin_profile['entity_id'],
        );

        if (!empty($keyword))
        {
            $filter['wd'] = $keyword;
        }

        $this->load->model('iwidepay/iwidepay_merchant_model' );
        $select = 'mi.jfk_no,mi.type,mi.account_aliases,mi.is_company,mi.status,mi.type';
        $list = $this->iwidepay_merchant_model->get_band_accounts($select,$filter,'','');

        if ($list)
        {
            $status = array(1=>'有效',2=>'无效');
            $is_company = array(1=>'对公',2=>'对私');
            foreach ($list as $key => $value)
            {
                $item = array();
                $item['jfk_no'] = $value['jfk_no'];
                if ($value['type'] == 'jfk')
                {
                    $value['name'] = $value['hotel_name'] = '金房卡分成';
                }
                else if($value['type'] == 'group')
                {
                    $value['hotel_name'] = '集团';
                }
                $item['name'] = !empty($value['name']) ? $value['name'] : '';
                $item['hotel_name'] = !empty($value['hotel_name']) ? $value['hotel_name'] : '';
                $item['account_aliases'] = $value['account_aliases'];
                $item['is_company'] = $is_company[$value['is_company']];
                $item['status'] = $status[$value['status']];
                unset($value['type']);
                $list[$key] = $item;
            }
        }
        $headArr = array('账户ID','所属公众号','酒店名称','账户别名','账户类型','账户状态');
        $widthArr = array(20,20,20,20,12,12);
        getExcel('分账银行账户',$headArr,$list,$widthArr);
    }

    /**
     * 导出账户信息
     */
    public function insert_account()
    {
        $param = request();
        $return = array(
            'param' => $param,
        );

        if (!empty($param['submit']))
        {
            $file = $this->img_upload_url();
            $excelData = $this->importExcel($file);
            if (!empty($excelData))
            {
                $this->load->model('iwidepay/iwidepay_bankcode_model');
                $this->load->model('iwidepay/iwidepay_merchant_model');
                $count = $fail_count = 0;
                foreach ($excelData as $value)
                {
                    //完善安全添加数据判断...
                    $bank = $this->iwidepay_bankcode_model->get_one('branch_id,branch,bank_code,clearBankNo,accBankNo', array('status'=>1,'branch' => trim($value['bank'])));
                    if (!empty($bank))
                    {
                        $value['inter_id'] = trim($value['inter_id']);
                        $value['type'] = trim($value['type']);
                        $value['hotel_id'] = trim($value['hotel_id']);
                        $value['bank_user_name'] = trim($value['bank_user_name']);
                        $value['account_aliases'] = trim($value['account_aliases']);
                        $value['bank'] = trim($value['bank']);
                        $value['bank_city'] = trim($value['bank_city']);
                        $value['bank_card_no'] = trim($value['bank_card_no']);
                        $value['is_company'] = trim($value['is_company']);

                        $value['branch_id'] = $bank['branch_id'];//支行
                        $value['bank'] = $bank['branch'];//支行
                        $value['clearBankNo'] = $bank['clearBankNo'];//清算行号
                        $value['accBankNo'] = $bank['accBankNo'];//受理行号
                        $value['bank_code'] = $bank['bank_code'];//行别代码

                        $value['registered_address'] = '';
                        $value['telephone'] = '';
                        $value['taxpayer_identity_number'] = '';//纳税人识别号
                        $value['status'] = 1;//状态
                        $value['updated_by'] = $this->admin_profile['username'];//修改人
                        $value['updated_at'] = date('Y-m-d H:i:s');//修改时间
                        $value['created_by'] = $this->admin_profile['username'];//状态
                        $value['created_at'] = date('Y-m-d H:i:s');//状态

                        $insert_id = $this->iwidepay_merchant_model->insert_account($value);
                        if ($insert_id > 0)
                        {
                            //更改 生成金房卡内部ID
                            $where = array(
                                'id' => $insert_id,
                            );
                            $update = array(
                                'jfk_no' => create_merchant_no(1200000 + $insert_id,'FZZH'),
                            );
                            $this->iwidepay_merchant_model->update_account($where,$update);

                            //添加日志
                            $inert['jfk_no'] = $update['jfk_no'];
                            add_iwidepay_admin_op_log($inert,'add');
                        }

                        $count++;
                    }
                    else
                    {
                        $fail_count++;
                    }
                }
            }

            echo '成功：' .$count."<br/>";
            echo '失败：' .$fail_count."<br/>";
            exit;
        }

        echo $this->_render_content($this->_load_view_file('insert_account'), $return, TRUE);
    }

    protected function img_upload_url()
    {
        $config ['upload_path'] = '../www_admin';
        $config ['allowed_types'] = '*';
        $config ['file_name'] = date ( 'YmdHis' ) . rand ( 10, 99 );
        // $config['allowed_types'] ='png|jpg|jpeg|bmp|gif';
        $config ['max_size'] = '20000';
        $this->load->library ( 'upload', $config );
        $this->upload->initialize ( $config );

        if ($this->upload->do_upload ( 'Filedata' )) {
            $a = $this->upload->data ();

//上传服务器后要更改地址
            return $config ['upload_path'] . '/' . $a ['file_name'];

//            return 'iwide30dev/www_admin' . '/' . $a ['file_name'];     //本地测试
        } else {
// 			$this->upload->display_errors('<p>', '</p>');
            echo  $this->upload->display_errors('<p>', '</p>');exit;
        }
    }


    /**
     * @desc PHPEXCEL导入
     * return array();
     */
    protected function importExcel($file)
    {
        $this->load->model('plugins/Excel_model','excel_model');
        $data = $this->excel_model->load_exl($file);
        $sheet = $data->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        //$highestColumm = $sheet->getHighestColumn(); // 取得总列数

        $new_data =  array ();
        for ($row = 2; $row <= $highestRow; $row++)
        {
            $item['inter_id'] = $sheet->getCell('A'.$row)->getValue();
            $item['type'] = $sheet->getCell('B'.$row)->getValue();
            $item['hotel_id'] = $sheet->getCell('C'.$row)->getValue();
            $item['bank_user_name'] = $sheet->getCell('D'.$row)->getValue();
            $item['account_aliases'] = $sheet->getCell('E'.$row)->getValue();
            $item['bank'] = $sheet->getCell('F'.$row)->getValue();
            $item['bank_city'] = $sheet->getCell('G'.$row)->getValue();
            $item['bank_card_no'] = $sheet->getCell('H'.$row)->getValue();
            $item['is_company'] = $sheet->getCell('I'.$row)->getValue();

            $new_data[] = $item;
        }
        unlink ( $file );

        return $new_data;
    }
}