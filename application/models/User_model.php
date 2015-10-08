<?php 
class User_model extends CI_Model {

    public $tableName = 'tb_user';

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

    public function is_check($email)
    {
        $this->db->where(array('email' => $email));
        return $this->db->count_all_results($this->tableName);
    }

    public function lists($where = '', $start = 0, $limit = 0)
    {
        $this->db->where($where);
        
        if($limit > 0)
            $this->db->limit($limit, $start);

        $this->db->order_by('id', 'desc');

        $query = $this->db->get($this->tableName); 
        $rows = $query->result_array();

        return $rows;
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

    public function get_email($email)
    {
        $this->db->where('email', $email);
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

    public function update($where = '', $params)
    {
        $this->db->where($where);
        return $this->db->update($this->tableName, $params); 
    }

    public function delete($id)
    {
        return $this->db->delete($this->tableName, array('id' => $id));
    }
}