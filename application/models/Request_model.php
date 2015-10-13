<?php 
class Request_model extends CI_Model {

    public $tableName = 'tb_request';

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    public function total_count($where = '')
    {
        $this->db->where($where);
        return $this->db->count_all_results($this->tableName);
    }

    public function lists($where = '', $start = 0, $limit = 0)
    {
        $this->db->where($where);
        
        if($limit > 0)
            $this->db->limit($limit, $start);

        $this->db->order_by('id', 'desc');

        $query = $this->db->get($this->tableName);
        return $query->result_array();

        //return $query;
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $row = $query->row(); 
            return $row;
        }
    }

    public function insert($params)
    {
        return $this->db->insert($this->tableName, $params);
    }
}