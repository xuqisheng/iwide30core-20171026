<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Statis_product_model extends MY_Model_Soma {

	protected $_env;
	protected $inter_hotels = array();

	/**
     * 加载mongo_db库
     * @return [type] [description]
     */
    protected function _init_mongo_db() {
        if(!isset($this->mongo_db) 
            || $this->mongo_db == null) { 
            $this->load->library('mongo_db', array('activate' => 'soma'));
        }
        return $this;
    }

    protected function _get_mongo_db() {
    	return $this->_init_mongo_db()->mongo_db;
    }

    /**
     * 获取数据库分片信息
     * @return [type] [description]
     */
    protected function _get_table_suffix() {
        $shards= $this->_db()->get('soma_shard')->result_array();
        $data = array();
        foreach ($shards as $row) { $data[] = $row['table_suffix']; }
        return $data;
    }

    protected function _init_order_statis_env($s_time, $e_time) {

    	$this->load->model('soma/sales_order_model', 'o_model');
        $o_model = $this->o_model;

		$_env['statis_db'] = "statis_product_order";
		$_env['statis_pk'] = array(
            'inter_id', 'hotel_id', 'product_id',
            'business', 'settlement', 'statis_date',
        );
        $_env['statis_type'] = 'order';

		$_env['o_tb'] = "soma_sales_order";
		$_env['i_tb'] = "soma_sales_order_item_package";
		$_env['o_pk'] = "order_id";

		$_env['o_where'] = array(
            'create_time >' => $s_time,
            'create_time <' => $e_time,
            'status' => $o_model::STATUS_PAYMENT
        );

        $_env['i_extra'] = array(
        	'payment_time' => 'payment_time',
            'statis_time'  => 'create_time',
            'order_status' => 'status',
            'settlement' => 'settlement',
            'business' => 'business',
            // 'grand_total' => 'grand_total',
            'real_grand_total' => 'real_grand_total',
        );

        return $_env;
	}

	protected function _init_consumer_statis_env($s_time, $e_time) {

		$_env['statis_db'] = "statis_product_consumer";
		$_env['statis_pk'] = array(
			'inter_id', 'hotel_id', 'product_id',
			'statis_date',
		);
		$_env['statis_type'] = 'consumer';

		$_env['o_tb'] = "soma_consumer_order";
		$_env['i_tb'] = "soma_consumer_order_item_package";
		$_env['o_pk'] = "consumer_id";

		$_env['o_where'] = array(
			'consumer_time >' => $s_time,
			'consumer_time <' => $e_time
		);

		$_env['i_extra'] = array(
			'statis_time' => 'consumer_time',
			'order_status' => 'status',
			'consumer_type' => 'consumer_type',
		);

		return $_env;
	}

	protected function _init_gift_statis_env($s_time, $e_time) {

		$_env['statis_db'] = "statis_product_gift";
		$_env['statis_pk'] = array(
			'inter_id', 'hotel_id', 'product_id',
			'statis_date',
		);
		$_env['statis_type'] = 'gift';

		$_env['o_tb'] = "soma_gift_order";
		$_env['i_tb'] = "soma_gift_order_item_package";
		$_env['o_pk'] = "gift_id";

		$_env['o_where'] = array(
			'create_time >' => $s_time,
			'create_time <' => $e_time
		);

		$_env['i_extra'] = array(
			'statis_time' => 'create_time',
			'order_status' => 'status',
		);

		return $_env;
	}

    /**
     * 更新统计数据
     * 更新产品销售数据
     * 更新产品消费数据
     * 更新产品转赠数据
     * 
     * @param  [type] $s_time [description]
     * @param  [type] $e_time [description]
     * @return [type]         [description]
     */
	public function update_statis_data($s_time, $e_time) {
		$this->update_order_statis_data($s_time, $e_time);
		$this->update_consumer_statis_data($s_time, $e_time);
		$this->update_gift_statis_data($s_time, $e_time);
	}

	/**
	 * 更新统计数据
     * 初始化环境 -> 抽取数据 -> 统计数据 -> 保存数据
     * 
     * 核对数据sql
	 * select os.*, (os.transaction/os.qty) as avg_price from (select oi.product_id, sum(oo.grand_total) as transaction, sum(oi.qty) as qty, max(oi.price_package) as max_price from iwide_soma_sales_order_item_package_1001 as oi left join iwide_soma_sales_order_1001 as oo on oi.order_id = oo.order_id where oo.payment_time >= '2016-05-24 00:00:00' and oo.payment_time <= '2016-05-24 23:59:59' group by oi.product_id) as os;
	 * 
	 * @param  [type] $s_time [description]
	 * @param  [type] $e_time [description]
	 * @return [type]         [description]
	 */
	public function update_order_statis_data($s_time, $e_time) {
		$env = $this->_init_order_statis_env($s_time, $e_time);
		$data = $this->_fecth_origin_data($env);
		$s_data = $this->_statis_product_data($data,$env);
		return $this->_save_statis_data($s_data, $env);
	}

	/**
	 * 更新统计数据
     * 初始化环境 -> 抽取数据 -> 统计数据 -> 保存数据
     *
     * 核对数据sql
     * select ci.product_id, sum(ci.consumer_qty) as consumer_qty from iwide_soma_consumer_order_item_package_1001 as ci left join iwide_soma_consumer_order_1001 as co on ci.consumer_id = co.consumer_id where co.consumer_time >= '2016-05-24 00:00:00' and co.consumer_time <= '2016-05-24 23:59:59' and co.consumer_type = 1 group by ci.product_id;
     * 
	 * @param  [type] $s_time [description]
	 * @param  [type] $e_time [description]
	 * @return [type]         [description]
	 */
	public function update_consumer_statis_data($s_time, $e_time) {
		$env = $this->_init_consumer_statis_env($s_time, $e_time);
		$data = $this->_fecth_origin_data($env);
		$s_data = $this->_statis_product_data($data,$env);
		return $this->_save_statis_data($s_data, $env);
	}

	/**
	 * 更新统计数据
     * 初始化环境 -> 抽取数据 -> 统计数据 -> 保存数据
     *
     * 核对数据sql
     * select gs.* from (select gi.product_id, sum(gi.qty) as qty from iwide_soma_gift_order_item_package_1001 as gi left join iwide_soma_gift_order_1001 as go on gi.gift_id = go.gift_id where go.create_time >= '2016-05-24 00:00:00' and go.create_time <= '2016-05-24 23:59:59' group by gi.product_id) as gs;
     * 
	 * @param  [type] $s_time [description]
	 * @param  [type] $e_time [description]
	 * @return [type]         [description]
	 */
	public function update_gift_statis_data($s_time, $e_time) {
		$env = $this->_init_gift_statis_env($s_time, $e_time);
		$data = $this->_fecth_origin_data($env);
		$s_data = $this->_statis_product_data($data,$env);
		return $this->_save_statis_data($s_data, $env);
	}

	/**
	 * 按分片抽出数据
	 * 
	 * @param  [type] $env [description]
	 * @return [type]      [description]
	 */
	protected function _fecth_origin_data($env) {
		$suffixs = $this->_get_table_suffix();
		$items = array();
		foreach ($suffixs as $suffix) {
			$o_tb = $env['o_tb'] . $suffix;
			$i_tb = $env['i_tb'] . $suffix;
            // 按分页拉取数据，防止读取数据库时数据量过大，只要有数据就继续拉
            $offset = 0;
            $limit  = 3000;
            while ($order = $this->_get_order_origin_data($o_tb, $env['o_pk'], $env['o_where'], $offset, $limit)) {
    			$item = $this->_get_item_origin_data($i_tb, $env['o_pk'], $order, $env['i_extra'], $suffix);
    			$items += $item;
                $offset += $limit;
            }
            // var_dump($offset, count($items));exit;
		}
		return $items;
	}

	/**
     * 获取总单信息
     * 
     * @param  [type] $table 数据表
     * @param  [type] $pk    主键
     * @param  [type] $where 过滤条件
     * @return [type]        array
     */
    protected function _get_order_origin_data($table, $pk, $where, $offset = 0, $limit = 5000) {
    	$result = $this->_shard_db_r('iwide_soma_r')
    		            ->where($where)
                        ->limit($limit, $offset)
                        ->get($table)
                        ->result_array();

    	$ret_data = array();
    	foreach ($result as $row) {
    		$ret_data[ $row[$pk] ] = $row;
    	}
    	return $ret_data;
    }

    /**
     * 查询细单数据，在细单里面加上总单的额外字段，
     * 相当于用总单主键做总细单表的关联:item_table left join order_table on order_id
     * 
     * @param  [type] $table  细单表名
     * @param  [type] $pk     总单主键
     * @param  [type] $orders 总单数据
     * @param  array  $extra  额外关联数据
     * @param  string $suffix 分片数据标识
     * @return [type]         扩展后的细单数据
     */
    protected function _get_item_origin_data($table, $pk, $orders, $extra = array(), $suffix = '_1001') {
    	if(count($orders) <= 0) { return array(); }
		
		$result = $this->_shard_db_r('iwide_soma_r')
    		            ->where_in($pk, array_keys($orders))
                        ->get($table)
                        ->result_array();

    	$ret_data = array();
    	foreach ($result as $row) {
            $order_id = $row[$pk];
            if(isset($orders[$order_id])) {
                foreach ($extra as $key => $column) {   
                    $value = '';
                    if(isset($orders[$order_id][$column])) { 
                        $value = $orders[$order_id][$column];
                    }
                    $row[$key] = $value;
                }
            }
    		
            // 附加两条数据：统计日期：statis_date，时间statis_time
            // statis_date：用于查看
            // mongo_date：mongodb日期区间筛选有点麻烦，添加这个字段快速查找数据
            if(isset($row['statis_time'])) {
                $row['statis_date'] = date('Y-m-d', strtotime($row['statis_time']));
                $row['mongo_date'] = $this->_get_mongo_db()->date(strtotime($row['statis_date']));
            }

            $ret_data[ $suffix . '_' . $row['item_id'] ] = $row;
    	}
    	return $ret_data;
    }
	
	/**
	 * 统计数据
	 * 
	 * @param  [type] $data 数据
	 * @param  [type] $env  统计环境
	 * @return [type]       array
	 */
	protected function _statis_product_data($data, $env) {
		$primary_keys = $env['statis_pk'];
		$type = $env['statis_type'];
		$statis = array();
        foreach ($data as $row) {
            // 找到最小颗粒的统计数据
            $_item_statis = array();
            foreach ($statis as $key => $item) {
                $flag = true;
                foreach ($primary_keys as $pk) {
                    if($row[$pk] != $item[$pk]) { $flag = false; }
                }
                if($flag) {
                    $_item_statis = $item;
                    unset($statis[$key]);
                    break;
                }
            }
            // 没有找到数据，初始化数据
            if(count($_item_statis) == 0) {
                foreach ($primary_keys as $pk) {
                    $_item_statis[$pk] = $row[$pk];
                }
            }
            // 设置这两个数据存入数据库
            $_item_statis['mongo_date'] = $row['mongo_date'];
            $_item_statis['name'] = $row['name'];
            
            // 针对不同类型进行数据叠加统计
            $statis_func = "_statis_product_{$type}_data";
            $statis[] = $this->$statis_func($row, $_item_statis);
        }

        // 将数据以日期进行分类
        $ret_data = array();
        foreach ($statis as $row) {
            if(!isset($ret_data[$row['statis_date']])) {
                $ret_data[$row['statis_date']] = array();
            }
            if(isset($row['order_ids'])) {
            	$row['order_cnt'] = count($row['order_ids']);
            }
            $ret_data[$row['statis_date']][] = $row;
        }

        return $ret_data;
	}

	/**
     * 统计产品订单数据
     * 成交额(transcation),销售数量(sales_qty),平均价格(avg_price),最高价格(max_price)
     * 
     * @param  [type] $row    被统计叠加的某一行数据
     * @param  array  $statis 最小粒度的统计数据
     * @return [type]         叠加后最小粒度的统计数据
     */
    protected function _statis_product_order_data($row, $statis = array()) {
        if(!isset($statis['transaction'])) {
            $statis['transaction'] = $statis['sales_qty'] = 0;
            $statis['avg_price'] = $statis['max_price'] = -1;
            $statis['order_id'] = array();
        }
        // $statis['transaction'] += $row['price_package'] * $row['qty'];
        $statis['transaction'] += $row['real_grand_total'];

        // 分时住修改为购买数量
        // $statis['sales_qty'] += $row['qty'];
        $pay_qty = $row['qty'];
        if(isset($row['can_split_use']) 
        	&& $row['can_split_use'] == Soma_base::STATUS_TRUE
        	&& $row['use_cnt'] > 1
        	&& !empty($row['payment_time'])) {
        	// 后台礼品卡券兑换没有走支付过程，没有支付时间并且分时住属性没有生效，不能除
        	$pay_qty = $row['qty'] / $row['use_cnt'];
        }
        $statis['sales_qty'] += $pay_qty;
        
        $statis['avg_price'] = $statis['transaction'] / $statis['sales_qty'];
        if($row['price_package'] > $statis['max_price']) {
            $statis['max_price'] = $row['price_package'];
        }
        if(!in_array($row['order_id'], $statis['order_id'])) {
        	$statis['order_ids'][] = $row['order_id'];
        }
        return $statis;
    }

    /**
     * 统计产品消费数据
     * 核销数量(consumer_qty),邮寄数量(shipped_qty)
     * 
     * @param  [type] $row    被统计叠加的某一行数据
     * @param  array  $statis 最小粒度的统计数据
     * @return [type]         叠加后最小粒度的统计数据
     */
    protected function _statis_product_consumer_data($row, $statis = array()) {
        if(!isset($statis['consumer_qty'])) {
            $statis['consumer_qty'] = $statis['shipped_qty'] = 0;
        }
        if(!isset($this->c_model)) {
            $this->load->model('soma/Consumer_order_model', 'c_model');
        }
        $c_model = $this->c_model;
        if($row['consumer_type'] == $c_model::CONSUME_TYPE_DEFAULT) {
            $statis['consumer_qty'] += $row['consumer_qty'];
        }
        if($row['consumer_type'] == $c_model::CONSUME_TYPE_SHIPPING) {
            $statis['shipped_qty'] += $row['consumer_qty'];
        }
        return $statis;
    }

    /**
     * 统计产品转赠数据
     * 赠送数量(gift_qty)
     * 
     * @param  [type] $row    被统计叠加的某一行数据
     * @param  array  $statis 最小粒度的统计数据
     * @return [type]         叠加后最小粒度的统计数据
     */
    protected function _statis_product_gift_data($row, $statis = array()) {
        if(!isset($statis['gift_qty'])) {
            $statis['gift_qty'] = 0;
        }
        $statis['gift_qty'] += $row['qty'];
        return $statis;
    }

    /**
     * 保存数据，保存某天的数据先删除相应的数据
     * 
     * @param  [type] $data [description]
     * @param  [type] $env  [description]
     * @return [type]       [description]
     */
	protected function _save_statis_data($data, $env) {
		// var_dump($data);
		$table = $env['statis_db'];
		$this->_init_mongo_db();
		foreach ($data as $date => $rows) {
			$this->mongo_db->where(array('statis_date' => $date))->delete_all($table);
			$this->mongo_db->batch_insert($table, $rows);
		}
	}

	/**
	 * 获取产品销售数据
	 * 
	 * @param  [type] $filter   过滤条件
	 * @param  [type] $hash_pks 最小粒度字段
	 * @return [type]           [description]
	 */
	public function get_order_statis_data($filter, $hash_pks = array()) {

		$env = $this->_init_order_statis_env('', '');
		if(count($hash_pks) == 0) {
			$hash_pks = $env['statis_pk'];
		}
		
		// 抽取数据
		$data = $this->_fetch_data_from_statis_db($env['statis_db'], $filter);
		$hash = $this->_data_hash($data, $hash_pks);

		// 合并数据
		$fmt_data = array();
		foreach ($hash as $key => $rows) {
			$_tmp = array();
			foreach ($hash_pks as $pk) {
				$_tmp[$pk] = $rows[0][$pk];
			}
			$_tmp['name'] = $rows[0]['name'];
			$_tmp['transaction'] = $_tmp['sales_qty'] = 0;
			$_tmp['avg_price'] = $_tmp['max_price'] = -1;
			$_tmp['order_cnt'] = 0;
			foreach ($rows as $row) {
				$_tmp['transaction'] += $row['transaction'];
				$_tmp['sales_qty'] += $row['sales_qty'];
				$_tmp['avg_price'] = $_tmp['transaction'] / $_tmp['sales_qty'];
				if($_tmp['max_price'] < $row['max_price']) {
					$_tmp['max_price'] = $row['max_price'];
				}
				$_tmp['order_cnt'] += $row['order_cnt'];
			}
			$fmt_data[$key] = $_tmp;
		}

		return $fmt_data;
	}

	/**
	 * 获取产品消费统计信息
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function get_consumer_statis_data($filter, $hash_pks) {
		
		$env = $this->_init_consumer_statis_env('', '');
		if(count($hash_pks) == 0) {
			$hash_pks = $env['statis_pk'];
		}
		
		$data = $this->_fetch_data_from_statis_db($env['statis_db'], $filter);
		$hash = $this->_data_hash($data, $hash_pks);

		// 合并数据
		$fmt_data = array();
		foreach ($hash as $key => $rows) {
			$_tmp = array();
			foreach ($hash_pks as $pk) {
				$_tmp[$pk] = $rows[0][$pk];
			}
			$_tmp['name'] = $rows[0]['name'];
			$_tmp['consumer_qty'] = $_tmp['shipped_qty'] = 0;
			foreach ($rows as $row) {
				$_tmp['consumer_qty'] += $row['consumer_qty'];
				$_tmp['shipped_qty'] += $row['shipped_qty'];
			}
			$fmt_data[$key] = $_tmp;
		}

		return $fmt_data;
	}

	/**
	 * 获取产品转赠统计信息
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function get_gift_statis_data($filter, $hash_pks) {
		
		$env = $this->_init_gift_statis_env('', '');
		if(count($hash_pks) == 0) {
			$hash_pks = $env['statis_pk'];
		}

		$data = $this->_fetch_data_from_statis_db($env['statis_db'], $filter);
		$hash = $this->_data_hash($data, $hash_pks);

		// 合并数据
		$fmt_data = array();
		foreach ($hash as $key => $rows) {
			$_tmp = array();
			foreach ($hash_pks as $pk) {
				$_tmp[$pk] = $rows[0][$pk];
			}
			$_tmp['name'] = $rows[0]['name'];
			$_tmp['gift_qty'] = 0;
			foreach ($rows as $row) {
				$_tmp['gift_qty'] += $row['gift_qty'];
			}
			$fmt_data[$key] = $_tmp;
		}
		
		return $fmt_data;
	}

	protected function _fetch_data_from_statis_db($table, $filter) {

		$_mongo_db = $this->_get_mongo_db();

		if(!isset($filter['where'])) { $filter['where'] = array(); }
		if(!isset($filter['order_by'])) { $filter['order_by'] = array(); }
		if(!isset($filter['page'])) { $filter['page'] = array(); }
		
		foreach ($filter['where'] as $key => $value) {
			if($key == 'statis_date') {
				$s_date = $_mongo_db->date(strtotime($value['s_date']));
				$e_date = $_mongo_db->date(strtotime($value['e_date']));
				$_mongo_db->where_between('mongo_date', $s_date, $e_date);
			} else if(is_array($value)) {
				$_mongo_db->where_in($key, $value);
			} else {
				$_mongo_db->where($key, $value);
			}
		}

		if(count($filter['order_by']) > 0) {
			foreach ($filter['order_by'] as $order_by) {
				$_mongo_db->order_by($order_by);
			}
		}
		if(isset($filter['page']['limit'])) {
			$_mongo_db->limit($filter['page']['limit']);
		}
		if(isset($filter['page']['offset'])) {
			$_mongo_db->offset($filter['page']['offset']);
		}

		return $_mongo_db->get($table);
	}

	protected function _data_hash($data, $hash_pks) {
		$_hash_data = array();
		foreach ($data as $row) {
			$_hash_key = '';
			foreach ($hash_pks as $pk) {
				$_hash_key .= ('|' . $row[$pk]);
			}
			$_hash_key .= '|';
			$_hash_data[$_hash_key][] = $row;
		}
		return $_hash_data;
	}

	/**
	 * 获取汇总数据
	 * 
	 * @param  [type] $filter   [description]
	 * @param  [type] $hash_pks [description]
	 * @return [type]           [description]
	 */
	public function get_summary_statis_data($filter, $hash_pks) {
		
		$order = $this->get_order_statis_data($filter, $hash_pks);
		$consumer = $this->get_consumer_statis_data($filter, $hash_pks);
		$gift = $this->get_gift_statis_data($filter, $hash_pks);
		// var_dump($order, $consumer, $gift);exit;

		$fields = $this->get_summary_statis_fields();
		$init_row = array();
		foreach ($fields as $field) {
			$init_row[$field] = 0;
		}

		$s_keys = array_keys(array_merge($order, $consumer, $gift));
		$summary = $sort = array();
		foreach ($s_keys as $key) {
			$_tmp = $init_row;
			$_arr = explode('|', $key);
			foreach ($hash_pks as $index => $pk) {
				$_tmp[$pk] = $_arr[$index + 1];
			}
			$p_name = '产品名称';
			if(isset($order[$key])) {
				$p_name = $order[$key]['name'];
				$_tmp['transaction'] = $order[$key]['transaction'];
				$_tmp['sales_qty'] = $order[$key]['sales_qty'];
				$_tmp['avg_price'] = $order[$key]['avg_price'];
				$_tmp['max_price'] = $order[$key]['max_price'];
				$_tmp['order_cnt'] = $order[$key]['order_cnt'];
			}
			if(isset($consumer[$key])) {
				$p_name = $consumer[$key]['name'];
				$_tmp['consumer_qty'] = $consumer[$key]['consumer_qty'];
				$_tmp['shipped_qty'] = $consumer[$key]['shipped_qty'];
			}
			if(isset($gift[$key])) {
				$p_name = $gift[$key]['name'];
				$_tmp['gift_qty'] = $gift[$key]['gift_qty'];
			}
			$_tmp['name'] = $p_name;
			$summary[$key] = $_tmp;
			$sort[] = $_tmp['product_id'];
		}
		array_multisort($sort, SORT_ASC, $summary);
		return $summary;

	}

	/**
	 * 控制表格数据显示顺序
	 * @return [type] [description]
	 */
	public function get_summary_statis_fields() {
		return array(
			'product_id', 'inter_id', 'hotel_id', 'name', 'order_cnt',
			'transaction', 'sales_qty', 'avg_price', 'max_price',
			'consumer_qty', 'shipped_qty', 'gift_qty', // 'statis_date',
		);
	}

	/**
	 * 控制表头显示顺序，与get_summary_statis_fields()相对应
	 * @return [type] [description]
	 */
	public function get_summary_header() {
		return array(
			'product_id' => '产品编号',
			'inter_id' => '公众号',
			'hotel_id' => '酒店名称',
			'name' => '产品名称',
			'order_cnt' => '成交单数',
			'transaction' => '成交额',
			'sales_qty' => '售出数量',
			'avg_price' => '平均售价',
			'max_price' => '最高售价',
			'consumer_qty' => '核销数量',
			'shipped_qty' => '邮寄数量',
			'gift_qty' => '转赠数量',
			// 'statis_date' => '统计日期',
		);
	}

	/**
	 * 将表头转化为data_grid显示格式
	 * 
	 * @param  [type] $header [description]
	 * @return [type]         [description]
	 */
	public function format_grid_header($header) {
		$_fmt_data = array();
		foreach ($header as $index => $title) {
			$_fmt_data[] = array('title' => $title);
		}
		return $_fmt_data;
	}

	/**
	 * 将数据转化为data_grid显示格式
	 * 
	 * @param  [type] $content  [description]
	 * @param  [type] $inter_id [description]
	 * @return [type]           [description]
	 */
	public function format_grid_content($content, $inter_id = FULL_ACCESS) {

		$inter_ids = $this->_get_total_inter_ids();
		$hotel_ids = array();

		$_fmt_data = $sort = array();
		foreach ($content as $index => $row) {
			$_tmp_row = array();
			foreach ($row as $index => $column) {
				
				if($index == 'product_id') {
					$sort[] = $column;
				}

				if($index == 'inter_id') {
					$_tmp_row[] = isset($inter_ids[$column])?$inter_ids[$column]:$column;
					$hotel_ids = $this->_get_inter_hotels($column);
					continue;
				}
				
				if($index == 'hotel_id') {
					$_tmp_row[] = isset($hotel_ids[$column])?$hotel_ids[$column]:$column;
					continue;
				}
				
				if(in_array($index, array('transaction', 'avg_price', 'max_price'))) {
					$_tmp_row[] = number_format($column, 2);
					continue;
				}

				$_tmp_row[] = $column;
			}
			$_fmt_data[] = $_tmp_row;
		}
		array_multisort($sort, SORT_ASC, $_fmt_data);
		return $_fmt_data;
	}

	protected function _get_total_inter_ids() {
		$this->load->model('wx/publics_model');
		$publics_array= $this->publics_model->get_public_hash();
		$publics_hash= $this->publics_model->array_to_hash($publics_array, 'name', 'inter_id');
		return $publics_hash;
	}

	/**
	 * 性能瓶颈，出现问题时，优化从缓存中获取酒店信息
	 * @param  [type] $inter_id [description]
	 * @return [type]           [description]
	 */
	protected function _get_inter_hotels($inter_id) {
		if(isset($this->inter_hotels[$inter_id])) {
			return $this->inter_hotels[$inter_id];
		}
		$hotels = $this->_get_total_hotel_ids($inter_id);
		$this->inter_hotels[$inter_id] = $hotels;
		return $hotels;
	}

	protected function _get_total_hotel_ids($inter_id) {
		$this->load->model('hotel/Hotel_model');
		$hotels_array = $this->Hotel_model->get_all_hotels($inter_id);
		$hotels_hash = $this->Hotel_model->array_to_hash($hotels_array, 'name', 'hotel_id');
		return $hotels_hash;
	}

	public function check_statis_data() {
		$db = $this->_get_mongo_db();
		$config = $this->config->item('mongo_db');
		$db_name = $config['soma']['database'];
		$db_names = $db->list_databases();
		foreach ($db_names['databases'] as $k => $v) {
			if($v['name'] == $db_name) { return true; }
		}
		return false;
	}

	/**
	 * 获取数据直播的产品销售排行
	 *
	 * @param      <type>  $inter_id  The inter identifier
	 * @param      <type>  $s_time    The s time
	 */
	public function get_live_sales_data($inter_id, $s_time) {

		// 获取总销售数据
		$hash_pks = array('product_id');
		$where['statis_date'] = array('s_date' => $s_time, 'e_date' => date('Y-m-d'));
		$order_by = array(array('sales_qty' => 'desc'));
		$filter = array('where' => $where, 'order_by' => $order_by);
		$total_data = $this->get_order_statis_data($filter, $hash_pks);

		// 获取48小时内的销售数据
		$start = date('Y-m-d H:i:s', strtotime("-2 day"));
		if(strtotime($start) < strtotime($s_time)) { $start = $s_time; }
		$_48_data = $this->_get_live_sales_data_by_time($start);

		// 获取24小时内的销售数据
		$start = date('Y-m-d H:i:s', strtotime("-1 days"));
		if(strtotime($start) < strtotime($s_time)) { $start = $s_time; }
		$_24_data = $this->_get_live_sales_data_by_time($start);

		// 24小时排序
		$_24_sales_qty = array();
		foreach ($_24_data as $key => $row) {
			$_24_sales_qty[] = $row['sales_qty'];
		}
		array_multisort($_24_sales_qty, SORT_DESC, $_24_data);

		// var_dump($_24_data, $total_data);exit;

		$ret_data = $fmt_data = array();
		$cnt = 0;
		foreach ($_24_data as $key => $row) {
			// 大于前五取消,??无销售数据取消，现在无数据的取有销售过的显示
			if($cnt >=5 ) { break; }
			// if(!isset($total_data[$key]['sales_qty'])) { $cnt++; continue; }

			// 计算较上期增长率：48小时内销售/48-24小时内销售
			$inc_per = '0%'; // 无销售默认0%
			$_48_sales = isset($_48_data[$key]) ? $_48_data[$key]['sales_qty'] : 0;
			$_24_sales = isset($_24_data[$key]) ? $_24_data[$key]['sales_qty'] : 0;
			$last_sales = $_48_sales - $_24_sales;
			if($last_sales != 0) {
				$percent = ($_48_sales / $last_sales) * 100;
				$inc_per = floor($percent) . '%';
			} else {
				// 48小时内有销售，但是24小时前无销售，按100%进行计算
				if($_48_sales > 0) { $inc_per = '100%'; }
			}

			$sales_total = isset($total_data[$key]['sales_qty']) ? $total_data[$key]['sales_qty'] : 0;

			$ret_data[$key] = $row;
			$ret_data[$key]['sales_total'] = $sales_total;
			$ret_data[$key]['inc_per'] = $inc_per;
			$fmt_data[] = $ret_data[$key];
			$cnt ++;
		}

		return $fmt_data;
	}

	protected function _get_live_sales_data_by_time($s_time, $e_time = null ) {

		$ret_data = array();
		if($e_time == null) { $e_time = date('Y-m-d H:i:s'); }
		$env = $this->_init_order_statis_env($s_time, $e_time);
		$data = $this->_fecth_origin_data($env);

		foreach ($data as $key => $row) {

			$index = '|' . $row['product_id'] . '|';
			if(!isset($ret_data[ $index ])) {
				$ret_data[ $index ]['sales_qty'] = 0;
			}
			$ret_data[ $index ]['name'] = $row['name'];
			$ret_data[ $index ]['product_id'] = $row['product_id'];
			$ret_data[ $index ]['sales_qty']  += $row['qty'];
			$ret_data[ $index ]['face_img']   = $row['face_img'];
		}

		return $ret_data;
	}

    /**
     * Gets the product total sales qty.
     *
     * @param      string   $product_id  The product identifier
     *
     * @return     integer  The product total sales qty.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function get_product_total_sales_qty($product_id)
    {
		if (ENVIRONMENT == 'dev') return 0;

        $filter = array('where' => array('product_id' => $product_id));
        $data = $this->get_order_statis_data($filter);

        $sales_qty = 0;
        foreach($data as $row)
        {
            $sales_qty += $row['sales_qty'];
        }

        return $sales_qty;
    }
}