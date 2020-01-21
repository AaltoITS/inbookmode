<?php
class Queans_model extends MY_Model {

    const DB_TABLE = 'q&a';
	const DB_TABLE_PK = 'QAId';
	
	function __construct() {
		parent::__construct();
	}

	public function get_que_data($queid) {
		$this->db->select('QAId,qType,Question,options,Marks,Answer,img');
		$this->db->from('q&a');
		$this->db->where('QAId', $queid);
		return $this->db->get()->row();
	}

	public function get_marks_sum($quizid) {
		$this->db->select_sum('Marks');
		$this->db->from('q&a');
		$this->db->where_in('QAId', $quizid);
		return $this->db->get()->result();
	}

	//to show data in add from pool form 
	public function get_que_list() {
        $this->db->select("q&a.QAId,q&a.qType,q&a.Question,q&a.Marks,q&a.AuthorId,q&a.status,users.username");
        $this->db->from('q&a');
        $this->db->join('users', 'users.id = q&a.AuthorId');
        //$this->db->where('q&a.status', 'Active');
        $query = $this->db->get();
        return $query->result();
    }

    //to show data in question pool table
    public function get_pool_list() {
        $this->db->select("q&a.QAId,q&a.qType,q&a.Question,q&a.Marks,q&a.AuthorId,users.username");
        $this->db->from('q&a');
        $this->db->join('users', 'users.id = q&a.AuthorId');
        $this->db->where('q&a.status', 'Active');
        $query = $this->db->get();
        return $query->result();
    }

    //to show quiz specific data table
    public function get_quiz_questions($queArray) {
		$this->db->select('q&a.QAId,q&a.qType,q&a.Question,q&a.Marks,q&a.status,users.username');
		$this->db->from('q&a');
		$this->db->join('users', 'users.id = q&a.AuthorId');
		$this->db->where_in('QAId', $queArray);
		return $this->db->get()->result();
	}

}