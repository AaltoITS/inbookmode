<?php
class Learner_model extends MY_Model {

    const DB_TABLE = 'learner';
	const DB_TABLE_PK = 'LearnerId';
	
	function __construct() {
		parent::__construct();
	}
}