<?php

class User_model extends MY_Model

{

    const DB_TABLE = 'users';

    const DB_TABLE_PK = 'id';



    function __construct()

    {

        parent::__construct();

    }

    public function get_UserEmail($userIds) {
		$this->db->select('email');
		$this->db->from('users');
		$this->db->where_in('id', $userIds);
		return $this->db->get()->result();
	}

}