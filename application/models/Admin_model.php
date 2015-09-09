<?php 
class Admin_model extends CI_Model {

    public $tableName = 'tb_admin';

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

        $this->db->order_by('admin_id', 'desc');

        $query = $this->db->get($this->tableName); 
        $rows = $query->result_array();

        return $rows;
    }

    public function get($id)
    {
        $this->db->where('admin_id', $id);
    	$query = $this->db->get($this->tableName);
    	
		if ($query->num_rows() > 0)
		{
   			$row = $query->row(); 
			return $row;
		}
    }

    public function insert($params) 
    {
    	return $this->db->insert('tb_admin', $params);
    }

    public function update()
    {
        $this->db->set('admin_name', $this->input->post('admin_name'));
        $this->db->set('update_at', date('Y-m-d H:i:s', time()));
        $this->db->where('admin_id', $this->input->post('admin_id'));
        return $this->db->update($this->tableName); 
    }

    public function delete($id)
    {
        return $this->db->delete($this->tableName, array('admin_id' => $id));
    }

}