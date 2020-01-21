<?php
class QuizRecord_model extends MY_Model {

    const DB_TABLE = 'quiz_record';
	const DB_TABLE_PK = 'Id';
	
	function __construct() {
		parent::__construct();
	}

	public function get_attempted_data($quizid) {
		$this->db->select('user_id');
		$this->db->from('quiz_record');
		$this->db->where('quiz_id', $quizid);
		$this->db->group_by('user_id'); 

		return $this->db->get()->result();
	}

	public function getRecordData($arr){
		$this->db->select("quiz_record.Id,quiz_record.answer,quiz_record.obtained_marks,q&a.Question,q&a.Marks");
		$this->db->from('quiz_record');
		$this->db->join('q&a', 'quiz_record.question_id = q&a.QAId');
		$this->db->where($arr);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_obtainedmarks_sum($whereArr) {
		$this->db->select_sum('obtained_marks');
		$this->db->from('quiz_record');
		$this->db->where($whereArr);

		return $this->db->get()->row();
	}
}