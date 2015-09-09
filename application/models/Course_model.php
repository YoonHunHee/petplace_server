<?php 
class Course_model extends CI_Model {

    public $tableName = 'tb_course';

  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  public function total_count($where = '')
  {
      $this->db->where($where);
      return $this->db->count_all($this->tableName);
  }

  public function lists($where = '', $start = 0, $limit = 0)
  {
    $this->db->where($where);
    
    if($limit > 0)
        $this->db->limit($limit, $start);

    $this->db->order_by('id', 'desc');

    $query = $this->db->get($this->tableName);
    return $query->result_array();
  }
}
