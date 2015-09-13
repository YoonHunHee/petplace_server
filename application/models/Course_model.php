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
        $this->db->select('id, kind, title, desc, AsText(st_pt) as st_pt, AsText(end_pt) as end_pt, AsText(way_pt) as way_pt, distance, walk_time, create_at, create_id, update_at, update_id');
        $this->db->where($where);

        if($limit > 0)
            $this->db->limit($limit, $start);

        $this->db->order_by('id', 'desc');

        $query = $this->db->get($this->tableName);
        return $query->result_array();
    }

    public function get($id)
    {

        $this->db->select('id, kind, title, desc, AsText(st_pt) as st_pt, AsText(end_pt) as end_pt, AsText(way_pt) as way_pt, distance, walk_time, create_at, create_id, update_at, update_id');
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
        $this->db->set('title', $params['title']);
        $this->db->set('desc', $params['desc']);
        $this->db->set('distance', $params['distance']);
        $this->db->set('walk_time', $params['walk_time']);

        $this->db->set('create_at', $params['create_at']);
        $this->db->set('create_id', $params['create_id']);
        $this->db->set('update_at', $params['update_at']);
        $this->db->set('update_id', $params['update_id']);    

        $this->db->set('st_pt', 'GeomFromText(\'' . $params['st_pt'] . '\')', false);
        $this->db->set('end_pt', 'GeomFromText(\'' . $params['end_pt'] . '\')', false);
        $this->db->set('way_pt', 'GeomFromText(\'' . $params['way_pt'] . '\')', false);

        return $this->db->insert($this->tableName);
    }

    public function update($where = '', $params)
    {

        $this->db->set('title', $params['title']);
        $this->db->set('desc', $params['desc']);
        $this->db->set('distance', $params['distance']);
        $this->db->set('walk_time', $params['walk_time']);
        $this->db->set('update_at', $params['update_at']);
        $this->db->set('update_id', $params['update_id']);    

        $this->db->set('st_pt', 'GeomFromText(\'' . $params['st_pt'] . '\')', false);
        $this->db->set('end_pt', 'GeomFromText(\'' . $params['end_pt'] . '\')', false);
        $this->db->set('way_pt', 'GeomFromText(\'' . $params['way_pt'] . '\')', false);

        $this->db->where($where);
        return $this->db->update($this->tableName); 
    }

    public function delete($id)
    {
        return $this->db->delete($this->tableName, array('id' => $id));
    }
}
