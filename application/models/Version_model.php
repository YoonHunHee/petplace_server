<?php 
class Version_model extends CI_Model {

    public $tableName = 'tb_version';
    public $historyTableName = 'tb_version_history';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function get() {
        $query = $this->db->get($this->tableName);
        
        if ($query->num_rows() > 0)
        {
            $row = $query->row(); 
            return $row;
        }
    }

    public function update($params)
    {
        return $this->db->update($this->tableName, $params); 
    }

    public function history() {

        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->historyTableName);
        return $query->result_array();
    }

    public function history_insert($params) {

        return $this->db->insert($this->historyTableName, $params);
    }
}