<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auto_send extends MY_Model
{

    const OPENID_LENGTH = 28;

    const TABLE_AUTO = 'auto_send_record';

    protected $table_field = array(
        'id',
        'open_id',
        'inter_id',
        'is_send',
        'send_count',
        'createtime'
    );

    public function add($open_id, $inter_id)
    {
        $data = [
            'open_id' => $open_id,
            'inter_id' => $inter_id,
            'createtime' => date("Y-m-d H:i:s", time())
        ];
        $writeAdapter = $this->load->database('member_write', true);
        return $writeAdapter->insert(self::TABLE_AUTO, $data);
    }

    public function getNotsent($inter_id, $starttime, $endtime = '')
    {
        $readAdapter = $this->load->database('member_read', true);
        $result = $readAdapter->from(self::TABLE_AUTO)
            ->where('inter_id', $inter_id)
            ->where('is_send', '1')
            ->where('(' . 'createtime  BETWEEN ' . ' \'' . $starttime . '\' ' . ' and ' . '\'' . $endtime . '\' ' . ')')
            ->get();
        return $result->result_array();
    }

    public function update($open_id, $inter_id, $id, $data)
    {
        $where = [
            'open_id' => $open_id,
            'inter_id' => $inter_id,
            'id' => $id
        ];
        $readAdapter = $this->load->database('member_write', true);
        $result = $readAdapter->update(self::TABLE_AUTO, $data, $where);
        return $result;
    }
}