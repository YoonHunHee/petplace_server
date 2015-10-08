<?php 
class Version_model extends CI_Model {

    public $tableName = 'tb_version';

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
}