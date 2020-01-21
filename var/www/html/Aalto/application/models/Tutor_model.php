<?php
class Tutor_model extends MY_Model {

    const DB_TABLE = 'tutor';
	const DB_TABLE_PK = 'TutorId';
	
	function __construct() {
		parent::__construct();
	}
}