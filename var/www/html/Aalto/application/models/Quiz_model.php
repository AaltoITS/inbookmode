<?php
class Quiz_model extends MY_Model {

    const DB_TABLE = 'quiz';
	const DB_TABLE_PK = 'QuizId';
	
	function __construct() {
		parent::__construct();
	}

	public function last_uniquequiz() {
		$this->db->select('QuizUniqueId');
		$this->db->from('quiz');
		$this->db->order_by('QuizId',"desc");
		$this->db->limit(1);

		return $this->db->get()->row();
	}

	public function remove_rejectedUser($id) {

		$where = "FIND_IN_SET('".$id."', assigned_users)";
		$this->db->select('QuizId,assigned_users');
		$this->db->from('quiz');
		$this->db->where($where);
		return $this->db->get()->result();
	}

}